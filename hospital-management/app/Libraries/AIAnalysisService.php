<?php

namespace App\Libraries;

use CodeIgniter\HTTP\CURLRequest;
use Config\Services;

/**
 * AI Analysis Service for Medical Reports
 * Supports multiple AI providers (OpenAI, Google, Local ML)
 */
class AIAnalysisService
{
    protected $httpClient;
    protected $apiKey;
    protected $apiEndpoint;
    protected $provider;

    public function __construct()
    {
        $this->httpClient = Services::curlrequest();
        $this->provider = env('AI_PROVIDER', 'openai');
        
        if ($this->provider === 'openai') {
            $this->apiEndpoint = 'https://api.openai.com/v1/chat/completions';
            $this->apiKey = env('OPENAI_API_KEY', '');
        } elseif ($this->provider === 'google') {
            $this->apiEndpoint = 'https://generativelanguage.googleapis.com/v1/models/gemini-pro:generateContent';
            $this->apiKey = env('GOOGLE_AI_API_KEY', '');
        }
    }

    /**
     * Analyze medical symptoms and provide potential diagnosis
     */
    public function analyzeSymptoms(array $symptoms, array $patientHistory = []): array
    {
        $prompt = $this->buildSymptomAnalysisPrompt($symptoms, $patientHistory);
        $response = $this->sendRequest($prompt);
        
        return [
            'analysis' => $response['analysis'] ?? '',
            'potential_diagnoses' => $response['diagnoses'] ?? [],
            'confidence_score' => $response['confidence'] ?? 0,
            'recommendations' => $response['recommendations'] ?? [],
            'urgency_level' => $response['urgency'] ?? 'normal',
            'suggested_tests' => $response['tests'] ?? [],
            'raw_response' => $response
        ];
    }

    /**
     * Analyze lab results and provide interpretation
     */
    public function analyzeLabResults(array $labResults, array $referenceRanges = []): array
    {
        $prompt = $this->buildLabAnalysisPrompt($labResults, $referenceRanges);
        $response = $this->sendRequest($prompt);
        
        return [
            'interpretation' => $response['interpretation'] ?? '',
            'abnormal_values' => $response['abnormal'] ?? [],
            'critical_values' => $response['critical'] ?? [],
            'recommendations' => $response['recommendations'] ?? [],
            'follow_up_actions' => $response['follow_up'] ?? [],
            'raw_response' => $response
        ];
    }

    /**
     * Generate comprehensive medical report with AI insights
     */
    public function generateReport(array $medicalData): array
    {
        $prompt = $this->buildReportGenerationPrompt($medicalData);
        $response = $this->sendRequest($prompt);
        
        return [
            'summary' => $response['summary'] ?? '',
            'key_findings' => $response['findings'] ?? [],
            'risk_assessment' => $response['risks'] ?? [],
            'treatment_suggestions' => $response['treatments'] ?? [],
            'preventive_measures' => $response['prevention'] ?? [],
            'follow_up_schedule' => $response['follow_up'] ?? '',
            'patient_education' => $response['education'] ?? '',
            'raw_response' => $response
        ];
    }

    /**
     * Predict disease risk based on patient history
     */
    public function predictDiseaseRisk(array $patientData): array
    {
        $prompt = $this->buildRiskPredictionPrompt($patientData);
        $response = $this->sendRequest($prompt);
        
        return [
            'risk_factors' => $response['risk_factors'] ?? [],
            'predicted_conditions' => $response['predictions'] ?? [],
            'risk_scores' => $response['scores'] ?? [],
            'preventive_recommendations' => $response['prevention'] ?? [],
            'lifestyle_modifications' => $response['lifestyle'] ?? [],
            'screening_schedule' => $response['screening'] ?? [],
            'raw_response' => $response
        ];
    }

    /**
     * Analyze medical imaging reports (text-based)
     */
    public function analyzeImagingReport(string $reportText, string $imagingType): array
    {
        $prompt = $this->buildImagingAnalysisPrompt($reportText, $imagingType);
        $response = $this->sendRequest($prompt);
        
        return [
            'findings_summary' => $response['summary'] ?? '',
            'abnormalities' => $response['abnormalities'] ?? [],
            'severity_assessment' => $response['severity'] ?? '',
            'differential_diagnosis' => $response['differential'] ?? [],
            'recommended_actions' => $response['actions'] ?? [],
            'raw_response' => $response
        ];
    }

    /**
     * Send request to AI provider
     */
    protected function sendRequest(string $prompt): array
    {
        try {
            if ($this->provider === 'openai') {
                return $this->sendOpenAIRequest($prompt);
            } elseif ($this->provider === 'google') {
                return $this->sendGoogleRequest($prompt);
            } else {
                return $this->getMockResponse($prompt);
            }
        } catch (\Exception $e) {
            log_message('error', 'AI Analysis Error: ' . $e->getMessage());
            return $this->getErrorFallback();
        }
    }

    /**
     * Send request to OpenAI API
     */
    protected function sendOpenAIRequest(string $prompt): array
    {
        $headers = [
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json'
        ];

        $body = json_encode([
            'model' => 'gpt-4-turbo-preview',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'You are an expert medical AI assistant. Provide accurate, evidence-based medical analysis. Always include disclaimers that your analysis should be reviewed by qualified healthcare professionals.'
                ],
                [
                    'role' => 'user',
                    'content' => $prompt
                ]
            ],
            'temperature' => 0.3,
            'max_tokens' => 2000,
            'response_format' => ['type' => 'json_object']
        ]);

        $response = $this->httpClient->request('POST', $this->apiEndpoint, [
            'headers' => $headers,
            'body' => $body,
            'timeout' => 30
        ]);

        $responseData = json_decode($response->getBody(), true);
        
        if (isset($responseData['choices'][0]['message']['content'])) {
            return json_decode($responseData['choices'][0]['message']['content'], true) ?? [];
        }

        return [];
    }

    /**
     * Send request to Google AI API
     */
    protected function sendGoogleRequest(string $prompt): array
    {
        $headers = [
            'Content-Type' => 'application/json'
        ];

        $body = json_encode([
            'contents' => [
                [
                    'parts' => [
                        [
                            'text' => $prompt
                        ]
                    ]
                ]
            ],
            'generationConfig' => [
                'temperature' => 0.3,
                'maxOutputTokens' => 2000,
            ]
        ]);

        $response = $this->httpClient->request('POST', 
            $this->apiEndpoint . '?key=' . $this->apiKey,
            [
                'headers' => $headers,
                'body' => $body,
                'timeout' => 30
            ]
        );

        $responseData = json_decode($response->getBody(), true);
        
        if (isset($responseData['candidates'][0]['content']['parts'][0]['text'])) {
            return json_decode($responseData['candidates'][0]['content']['parts'][0]['text'], true) ?? [];
        }

        return [];
    }

    /**
     * Get mock response for development/testing
     */
    protected function getMockResponse(string $prompt): array
    {
        return [
            'analysis' => 'AI analysis is currently in demo mode. Configure API keys for production use.',
            'diagnoses' => ['Demo Diagnosis 1', 'Demo Diagnosis 2'],
            'confidence' => 0.85,
            'recommendations' => ['Schedule follow-up appointment', 'Monitor symptoms'],
            'urgency' => 'normal',
            'tests' => ['Complete Blood Count', 'Basic Metabolic Panel']
        ];
    }

    /**
     * Get error fallback response
     */
    protected function getErrorFallback(): array
    {
        return [
            'analysis' => 'Unable to perform AI analysis at this time. Please try again later.',
            'diagnoses' => [],
            'confidence' => 0,
            'recommendations' => ['Consult with healthcare provider'],
            'urgency' => 'unknown',
            'tests' => []
        ];
    }

    /**
     * Build prompt for symptom analysis
     */
    protected function buildSymptomAnalysisPrompt(array $symptoms, array $patientHistory): string
    {
        $symptomsText = implode(', ', $symptoms);
        $historyText = !empty($patientHistory) ? json_encode($patientHistory) : 'None provided';
        
        return <<<PROMPT
Analyze the following medical case and provide a structured JSON response:

**Patient Symptoms:** {$symptomsText}

**Patient History:** {$historyText}

Provide your analysis in the following JSON format:
{
    "analysis": "Detailed analysis of symptoms",
    "diagnoses": ["Potential diagnosis 1", "Potential diagnosis 2"],
    "confidence": 0.85,
    "recommendations": ["Recommendation 1", "Recommendation 2"],
    "urgency": "low|normal|high|emergency",
    "tests": ["Suggested test 1", "Suggested test 2"]
}

Remember: This is for medical professional review only.
PROMPT;
    }

    /**
     * Build prompt for lab results analysis
     */
    protected function buildLabAnalysisPrompt(array $labResults, array $referenceRanges): string
    {
        $resultsText = json_encode($labResults);
        $rangesText = !empty($referenceRanges) ? json_encode($referenceRanges) : 'Standard reference ranges apply';
        
        return <<<PROMPT
Analyze these lab results and provide a structured JSON response:

**Lab Results:** {$resultsText}

**Reference Ranges:** {$rangesText}

Provide your analysis in the following JSON format:
{
    "interpretation": "Overall interpretation of lab results",
    "abnormal": ["Abnormal value 1", "Abnormal value 2"],
    "critical": ["Critical value if any"],
    "recommendations": ["Recommendation 1", "Recommendation 2"],
    "follow_up": ["Follow-up action 1", "Follow-up action 2"]
}
PROMPT;
    }

    /**
     * Build prompt for report generation
     */
    protected function buildReportGenerationPrompt(array $medicalData): string
    {
        $dataText = json_encode($medicalData);
        
        return <<<PROMPT
Generate a comprehensive medical report analysis in structured JSON format:

**Medical Data:** {$dataText}

Provide your analysis in the following JSON format:
{
    "summary": "Executive summary of the case",
    "findings": ["Key finding 1", "Key finding 2"],
    "risks": ["Risk factor 1", "Risk factor 2"],
    "treatments": ["Treatment suggestion 1", "Treatment suggestion 2"],
    "prevention": ["Preventive measure 1", "Preventive measure 2"],
    "follow_up": "Recommended follow-up schedule",
    "education": "Patient education points"
}
PROMPT;
    }

    /**
     * Build prompt for disease risk prediction
     */
    protected function buildRiskPredictionPrompt(array $patientData): string
    {
        $dataText = json_encode($patientData);
        
        return <<<PROMPT
Predict disease risks based on patient data and provide structured JSON response:

**Patient Data:** {$dataText}

Provide your analysis in the following JSON format:
{
    "risk_factors": ["Risk factor 1", "Risk factor 2"],
    "predictions": ["Predicted condition 1", "Predicted condition 2"],
    "scores": {"condition1": 0.75, "condition2": 0.45},
    "prevention": ["Preventive recommendation 1", "Preventive recommendation 2"],
    "lifestyle": ["Lifestyle modification 1", "Lifestyle modification 2"],
    "screening": "Recommended screening schedule"
}
PROMPT;
    }

    /**
     * Build prompt for imaging report analysis
     */
    protected function buildImagingAnalysisPrompt(string $reportText, string $imagingType): string
    {
        return <<<PROMPT
Analyze this {$imagingType} imaging report and provide structured JSON response:

**Report Text:** {$reportText}

Provide your analysis in the following JSON format:
{
    "summary": "Summary of findings",
    "abnormalities": ["Abnormality 1", "Abnormality 2"],
    "severity": "mild|moderate|severe|critical",
    "differential": ["Differential diagnosis 1", "Differential diagnosis 2"],
    "actions": ["Recommended action 1", "Recommended action 2"]
}
PROMPT;
    }
}
