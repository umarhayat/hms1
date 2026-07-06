# Hospital Management System (HMS Pro)

A production-ready Hospital Management System built with CodeIgniter 4, Bootstrap 5, and AI-powered analytics.

## Features

### Core Modules
- **Dashboard** - Real-time statistics and insights
- **Patient Management** - Complete patient records and history
- **Doctor Management** - Doctor profiles, schedules, and availability
- **Appointment Scheduling** - Smart appointment booking and management
- **Medical Records** - Electronic Health Records (EHR) with AI analysis
- **AI-Powered Analytics** - Advanced reporting and predictive insights

### AI Features
- Symptom Analysis with potential diagnoses
- Lab Results Interpretation
- Disease Risk Prediction
- Automated Medical Report Generation
- Treatment Recommendations
- Predictive Analytics for hospital operations

### Technical Features
- Role-based Access Control (Admin, Doctor, Nurse, Receptionist)
- Responsive Bootstrap 5 UI
- RESTful API endpoints
- Database migrations
- Form validation
- AJAX-powered interactions
- Chart.js visualizations
- Session management
- CSRF protection

## Requirements

- PHP 8.1 or higher
- MySQL 5.7+ or MariaDB 10.3+
- Composer
- Web server (Apache/Nginx)

## Installation

### 1. Clone or Copy the Project

```bash
cd /workspace/hospital-management
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Configure Environment

Copy `.env` file and configure:

```bash
# Set environment
CI_ENVIRONMENT = development

# Database configuration
database.default.hostname = localhost
database.default.database = hospital_db
database.default.username = root
database.default.password = your_password

# AI Configuration (optional)
AI_PROVIDER = openai
OPENAI_API_KEY = your-api-key
```

### 4. Create Database

```sql
CREATE DATABASE hospital_db CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
```

### 5. Run Migrations

```bash
php spark migrate
```

### 6. Set Permissions

```bash
chmod -R 777 writable/
```

### 7. Start Development Server

```bash
php spark serve
```

Visit `http://localhost:8080` in your browser.

## Project Structure

```
hospital-management/
├── app/
│   ├── Config/           # Configuration files
│   ├── Controllers/      # Application controllers
│   │   ├── Api/         # API controllers
│   │   └── ...
│   ├── Database/
│   │   └── Migrations/  # Database migrations
│   ├── Filters/         # Request filters
│   ├── Helpers/         # Custom helpers
│   ├── Libraries/       # Custom libraries
│   │   └── AIAnalysisService.php
│   ├── Models/          # Database models
│   └── Views/           # View templates
│       ├── layouts/     # Layout templates
│       ├── dashboard/   # Dashboard views
│       ├── patients/    # Patient views
│       ├── doctors/     # Doctor views
│       ├── appointments/# Appointment views
│       └── reports/     # Report views
├── public/              # Public assets
├── writable/            # Writable directories
└── composer.json        # Composer configuration
```

## Default User Credentials

After seeding the database:

- **Email:** admin@hospital.com
- **Password:** admin123

## API Endpoints

### Patients
- `GET /api/patients/search` - Search patients
- `POST /api/patients` - Create patient

### Doctors
- `GET /api/doctors/available` - Get available doctors
- `GET /api/doctors/schedule` - Get doctor schedule

### Appointments
- `GET /api/appointments/slots` - Get available time slots
- `POST /api/appointments` - Book appointment

### AI Services
- `POST /api/ai/analyze-symptoms` - Analyze symptoms
- `POST /api/ai/analyze-labs` - Analyze lab results
- `POST /api/ai/generate-report` - Generate medical report

## Security Features

- Password hashing (bcrypt)
- CSRF protection
- SQL injection prevention
- XSS protection
- Role-based access control
- Session security

## Customization

### Adding New AI Provider

Edit `app/Libraries/AIAnalysisService.php` to add support for additional AI providers.

### Theme Customization

Modify CSS variables in `app/Views/layouts/main.php`:

```css
:root {
    --primary-color: #0d6efd;
    --secondary-color: #6c757d;
    /* Add your custom colors */
}
```

## Production Deployment

1. Set `CI_ENVIRONMENT = production` in `.env`
2. Configure proper database credentials
3. Set up SSL certificate
4. Configure web server (Apache/Nginx)
5. Enable caching
6. Set up regular backups
7. Configure error logging

## Support

For issues and feature requests, please check the documentation or contact support.

## License

This project is proprietary software. All rights reserved.

---

**Built with ❤️ using CodeIgniter 4 & Bootstrap 5**
