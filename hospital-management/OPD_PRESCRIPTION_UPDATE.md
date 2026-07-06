# OPD Module - Prescription & Medicine Management Update

## Overview
Enhanced the OPD module with a dedicated prescription system and separate medicine management table for better data structure and print-ready prescriptions.

## New Features Added

### 1. Database Schema
- **New Table**: `opd_medicines`
  - Stores individual medicines prescribed during OPD visits
  - Fields: medicine_name, strength, dosage, frequency, duration, instructions
  - Foreign key relationship with `opd_visits` table

### 2. New Model
- **OpdMedicineModel.php**
  - Handles CRUD operations for medicines
  - Methods: `getMedicinesByOpdId()`, `saveMedicines()`
  - Supports batch insertion for multiple medicines

### 3. Enhanced Controller (OpdController.php)
- **New Method**: `prescription($id)`
  - Generates print-ready prescription view
  - Fetches patient, doctor, and medicine details
  
- **Updated Methods**:
  - `show()`: Now fetches medicines from new table
  - `edit()`: Loads existing medicines for editing
  - `update()`: Saves medicines separately via OpdMedicineModel

### 4. New View: prescription.php
- Professional, print-ready prescription format
- Includes:
  - Hospital header with branding
  - Patient demographics
  - Doctor details with registration number
  - Vitals section
  - Clinical assessment (complaints & diagnosis)
  - Medication table with columns: Name, Strength, Dosage, Frequency, Duration
  - Investigations and Advice sections
  - Doctor signature area
- Print-optimized CSS (hides buttons when printing)

### 5. Updated View: edit.php
- Enhanced medicine entry form with additional fields:
  - Medicine Name
  - Strength (e.g., 500mg, 10ml)
  - Dosage (e.g., 1 tablet, 5ml)
  - Frequency (e.g., BD, TDS, SOS)
  - Duration (e.g., 5 days, 2 weeks)
  - Instructions (e.g., "After food", "Empty stomach")
- Dynamic "Add Medicine" button for multiple entries
- Backward compatible with old JSON-based medication storage

### 6. Routes Configuration
```php
$routes->get('(:num)/prescription', 'OpdController::prescription/$1');
```

## Usage

### Access Prescription
```
http://localhost:8080/opd/{id}/prescription
```
Click the "Print Prescription" button to generate a printer-friendly version.

### Adding Medicines
1. Navigate to OPD visit edit page
2. Fill in medicine details in the Treatment Plan section
3. Click "Add Medicine" for additional prescriptions
4. Save the consultation

### Data Migration
The system is backward compatible:
- New visits use the `opd_medicines` table
- Old visits with JSON medications still display correctly
- No manual migration required

## Files Modified/Created

### Created:
1. `app/Database/Migrations/2023-10-28-000004_CreateOpdMedicinesTable.php`
2. `app/Models/OpdMedicineModel.php`
3. `app/Views/opd/prescription.php`

### Modified:
1. `app/Controllers/OpdController.php` - Added prescription method & medicine handling
2. `app/Views/opd/edit.php` - Enhanced medicine form fields
3. `app/Config/Routes.php` - Added prescription route

## Next Steps
1. Run migration: `php spark migrate`
2. Test prescription generation
3. Add medicine autocomplete/search feature (optional)
4. Integrate with pharmacy/inventory module (future)

## Benefits
- ✅ Structured medicine data (no more JSON parsing)
- ✅ Professional prescription printing
- ✅ Better reporting capabilities
- ✅ Easier integration with pharmacy systems
- ✅ Support for complex prescription formats
- ✅ Backward compatible with existing data
