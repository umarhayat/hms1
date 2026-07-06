<?php

use CodeIgniter\Router\RouteContainer;

/**
 * @var RouteContainer $routes
 */

// Public routes
$routes->get('/', 'Dashboard::index');

// Dashboard
$routes->get('dashboard', 'Dashboard::index');
$routes->get('reports/analytics', 'Dashboard::analytics');

// Patients
$routes->group('patients', ['namespace' => 'App\Controllers'], function($routes) {
    $routes->get('', 'Patients::index');
    $routes->get('create', 'Patients::create');
    $routes->post('', 'Patients::store');
    $routes->get('(:num)', 'Patients::show/$1');
    $routes->get('(:num)/edit', 'Patients::edit/$1');
    $routes->put('(:num)', 'Patients::update/$1');
    $routes->delete('(:num)', 'Patients::delete/$1');
    $routes->get('search', 'Patients::search');
});

// Doctors
$routes->group('doctors', ['namespace' => 'App\Controllers'], function($routes) {
    $routes->get('', 'Doctors::index');
    $routes->get('create', 'Doctors::create');
    $routes->post('store', 'Doctors::store');
    $routes->get('edit/(:num)', 'Doctors::edit/$1');
    $routes->post('update/(:num)', 'Doctors::update/$1');
    $routes->get('view/(:num)', 'Doctors::view/$1');
    $routes->delete('delete/(:num)', 'Doctors::delete/$1');
});

// Appointments
$routes->group('appointments', ['namespace' => 'App\Controllers'], function($routes) {
    $routes->get('', 'Appointments::index');
    $routes->get('create', 'Appointments::create');
    $routes->post('store', 'Appointments::store');
    $routes->get('edit/(:num)', 'Appointments::edit/$1');
    $routes->post('update/(:num)', 'Appointments::update/$1');
    $routes->post('cancel/(:num)', 'Appointments::cancel/$1');
    $routes->post('confirm/(:num)', 'Appointments::confirm/$1');
    $routes->get('calendar', 'Appointments::calendar');
});

// Medical Records
$routes->group('medical-records', ['namespace' => 'App\Controllers'], function($routes) {
    $routes->get('', 'MedicalRecords::index');
    $routes->get('create', 'MedicalRecords::create');
    $routes->post('store', 'MedicalRecords::store');
    $routes->get('edit/(:num)', 'MedicalRecords::edit/$1');
    $routes->post('update/(:num)', 'MedicalRecords::update/$1');
    $routes->get('view/(:num)', 'MedicalRecords::view/$1');
    $routes->post('ai-analyze/(:num)', 'MedicalRecords::aiAnalyze/$1');
});

// OPD Module
$routes->group('opd', ['namespace' => 'App\Controllers'], function($routes) {
    $routes->get('', 'OpdController::index');
    $routes->get('create', 'OpdController::create');
    $routes->post('store', 'OpdController::store');
    $routes->get('(:num)', 'OpdController::show/$1');
    $routes->get('(:num)/edit', 'OpdController::edit/$1');
    $routes->post('(:num)/update', 'OpdController::update/$1');
    $routes->post('(:num)/status', 'OpdController::updateStatus/$1');
    $routes->get('search/patients', 'OpdController::searchPatients');
    $routes->get('(:num)/prescription', 'OpdController::prescription/$1');
});

// Auth
$routes->group('auth', ['namespace' => 'App\Controllers'], function($routes) {
    $routes->get('login', 'AuthController::login');
    $routes->post('login', 'AuthController::attemptLogin');
    $routes->get('logout', 'AuthController::logout');
    $routes->get('register', 'AuthController::register');
    $routes->post('register', 'AuthController::attemptRegister');
});

// API Routes for AJAX
$routes->group('api', ['namespace' => 'App\Controllers\Api'], function($routes) {
    $routes->get('patients/search', 'PatientAPI::search');
    $routes->get('doctors/available', 'DoctorAPI::available');
    $routes->get('appointments/slots', 'AppointmentAPI::getSlots');
    $routes->post('ai/analyze-symptoms', 'AIController::analyzeSymptoms');
    $routes->post('ai/analyze-labs', 'AIController::analyzeLabs');
    $routes->post('ai/generate-report', 'AIController::generateReport');
});
