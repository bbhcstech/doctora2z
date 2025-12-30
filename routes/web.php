<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DoctorListController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ContactUsController;

use App\Http\Controllers\CountryController;
use App\Http\Controllers\StateController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\DistrictAjaxcontroller;
use App\Http\Controllers\DoctorListAjax;

use App\Http\Controllers\TownVillageController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SubcategoryController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AdvertisementController;
use App\Http\Controllers\SocialLinkController;
use App\Http\Controllers\BannerImageController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\CategoryAjaxController;

use App\Http\Controllers\Frontend\FrontendController;
use App\Http\Controllers\Frontend\LocationFrontController;
use App\Http\Controllers\Frontend\SearchController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OTPController;
use App\Http\Controllers\TrendingDoctorController;
use App\Http\Controllers\TrendingClinicControllers;
use App\Http\Controllers\HospitalController;
use App\Http\Controllers\MedicaShopController;
use App\Http\Controllers\TrendingHospitalsControllers;
use App\Http\Controllers\TrendingMedicalShopControllers;

use App\Http\Controllers\CountryExcelController;
use App\Http\Controllers\StateExcelController;
use App\Http\Controllers\DistrictExcelController;
use App\Http\Controllers\DoctorExcelController;
use App\Http\Controllers\CategoryExcelController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\DoctorProfileController;

use App\Http\Controllers\Admin\DoctorController as AdminDoctorController;
use App\Http\Controllers\Admin\DoctorDataController;
use App\Http\Controllers\Admin\DoctorImportExportController;


Route::get('/sitemap', function () {
    $links = [
        ['url' => 'https://doctora2z.com/public/', 'title' => 'Home'],
        ['url' => 'https://doctora2z.com/public/doctors', 'title' => 'Doctors'],
        ['url' => 'https://doctora2z.com/public/clinics', 'title' => 'Clinics'],
        ['url' => 'https://doctora2z.com/public/hospitals', 'title' => 'Hospitals'],
        ['url' => 'https://doctora2z.com/public/medical-shop', 'title' => 'Medical Shop'],
        ['url' => 'https://doctora2z.com/public/contact', 'title' => 'Contact'],
        ['url' => 'https://doctora2z.com/public/about', 'title' => 'About Us'],
        ['url' => 'https://doctora2z.com/public/search', 'title' => 'Search Doctors'],
        ['url' => 'https://doctora2z.com/public/list-doctor', 'title' => 'List a Doctor'],
        ['url' => 'https://doctora2z.com/public/help', 'title' => 'Help Center'],
        ['url' => 'https://doctora2z.com/public/faqs', 'title' => 'FAQs'],
        ['url' => 'https://doctora2z.com/public/privacy', 'title' => 'Privacy Policy'],
        ['url' => 'https://doctora2z.com/public/terms', 'title' => 'Terms & Conditions'],
    ];

    return response()
        ->view('sitemap-html', compact('links'))
        ->header('Content-Type', 'text/html');
});

Route::get('/sitemap.xml', function () {
    $urls = [
        ['loc' => 'https://doctora2z.com/public/', 'lastmod' => now()->format('Y-m-d'), 'priority' => '1.0'],
        ['loc' => 'https://doctora2z.com/public/doctors', 'lastmod' => now()->format('Y-m-d'), 'priority' => '0.9'],
        ['loc' => 'https://doctora2z.com/public/clinics', 'lastmod' => now()->format('Y-m-d'), 'priority' => '0.8'],
        ['loc' => 'https://doctora2z.com/public/hospitals', 'lastmod' => now()->format('Y-m-d'), 'priority' => '0.8'],
        ['loc' => 'https://doctora2z.com/public/medical-shop', 'lastmod' => now()->format('Y-m-d'), 'priority' => '0.7'],
        ['loc' => 'https://doctora2z.com/public/contact', 'lastmod' => now()->format('Y-m-d'), 'priority' => '0.6'],
        ['loc' => 'https://doctora2z.com/public/about', 'lastmod' => now()->format('Y-m-d'), 'priority' => '0.7'],
        ['loc' => 'https://doctora2z.com/public/search', 'lastmod' => now()->format('Y-m-d'), 'priority' => '0.9'],
        ['loc' => 'https://doctora2z.com/public/list-doctor', 'lastmod' => now()->format('Y-m-d'), 'priority' => '0.8'],
        ['loc' => 'https://doctora2z.com/public/help', 'lastmod' => now()->format('Y-m-d'), 'priority' => '0.5'],
        ['loc' => 'https://doctora2z.com/public/faqs', 'lastmod' => now()->format('Y-m-d'), 'priority' => '0.5'],
        ['loc' => 'https://doctora2z.com/public/privacy', 'lastmod' => now()->format('Y-m-d'), 'priority' => '0.3'],
        ['loc' => 'https://doctora2z.com/public/terms', 'lastmod' => now()->format('Y-m-d'), 'priority' => '0.3'],
    ];

    return response()
        ->view('sitemap-xml', compact('urls'))
        ->header('Content-Type', 'application/xml');
});


Route::post('/ratingstore', [FrontendController::class, 'ratingstore'])->name('frontend.ratingstore');







Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    
    // All doctor-related routes under /admin/doctors
    Route::prefix('doctors')->name('admin.doctors.')->group(function () {
        
        // DoctorController Routes (Main CRUD and pages)
      Route::get('/', [AdminDoctorController::class, 'index'])->name('index');
        Route::get('/create', [AdminDoctorController::class, 'create'])->name('create');
        Route::get('/import', [AdminDoctorController::class, 'importPage'])->name('import.page');
        Route::get('/{doctor}/edit', [AdminDoctorController::class, 'edit'])->name('edit');
        
        // CRUD operations
        Route::post('/', [AdminDoctorController::class, 'store'])->name('store');
        Route::get('/{doctor}', [AdminDoctorController::class, 'show'])->name('show');
        Route::put('/{doctor}', [AdminDoctorController::class, 'update'])->name('update');
        Route::delete('/{doctor}', [AdminDoctorController::class, 'destroy'])->name('destroy');
        
        // AJAX dropdowns
        Route::get('/states/{countryId}', [AdminDoctorController::class, 'getStates']);
        Route::get('/districts/{stateId}', [AdminDoctorController::class, 'getDistricts']);
        Route::get('/cities/{districtId}', [AdminDoctorController::class, 'getCities']);
        Route::get('/clinics/{categoryId}', [AdminDoctorController::class, 'getClinics']);
        
        // Bulk operations
        Route::post('/bulk-status', [AdminDoctorController::class, 'bulkUpdateStatus'])->name('bulk.status');
        Route::post('/bulk-delete', [AdminDoctorController::class, 'bulkDelete'])->name('bulk.delete');
        
        // Import/Export operations
        Route::get('/export/csv', [AdminDoctorController::class, 'exportCsv'])->name('export.csv');
        Route::get('/export/excel', [AdminDoctorController::class, 'exportExcel'])->name('export.excel');
        Route::get('/export/pdf', [AdminDoctorController::class, 'exportPdf'])->name('export.pdf');
        Route::get('/sample/download', [AdminDoctorController::class, 'downloadSample'])->name('sample.download');
        Route::post('/import', [AdminDoctorController::class, 'import'])->name('import');
        
        // Statistics and search
        Route::get('/statistics', [AdminDoctorController::class, 'statistics'])->name('statistics');
        Route::get('/search', [AdminDoctorController::class, 'search'])->name('search');
        
        // Datatable data
        Route::get('/datatable/data', [AdminDoctorController::class, 'datatable'])->name('datatable');
    
        
        // DoctorDataController Routes (AJAX/Data operations)
        Route::get('/datatable/data', [DoctorDataController::class, 'datatable'])->name('datatable');
        Route::get('/search', [DoctorDataController::class, 'search'])->name('search');
        Route::get('/statistics', [DoctorDataController::class, 'statistics'])->name('statistics');
        Route::post('/bulk-status', [DoctorDataController::class, 'bulkUpdateStatus'])->name('bulk.status');
        Route::post('/bulk-delete', [DoctorDataController::class, 'bulkDelete'])->name('bulk.delete');
        
        // DoctorImportExportController Routes (Import/Export operations)
        Route::get('/export/csv', [DoctorImportExportController::class, 'exportCsv'])->name('export.csv');
        Route::get('/export/excel', [DoctorImportExportController::class, 'exportExcel'])->name('export.excel');
        Route::get('/export/pdf', [DoctorImportExportController::class, 'exportPdf'])->name('export.pdf');
        Route::get('/sample/download', [DoctorImportExportController::class, 'downloadSample'])->name('sample.download');
        Route::post('/import', [DoctorImportExportController::class, 'import'])->name('import');
    });
});





Route::get('/doctor/email/check', [\App\Http\Controllers\Frontend\FrontendController::class, 'checkEmail'])
     ->name('doctor.email.check');
     
     
     
     Route::get('/', function(){
         return view('welcome');
     });
     
     
     Route::delete('/doctor/profile/{doctor}/photo', [\App\Http\Controllers\DoctorProfileController::class, 'removePhoto'])
    ->name('doctor.profile.remove-photo')
    ->middleware('auth')
    ->whereNumber('doctor');
     
Route::middleware(['auth'])->prefix('doctor/profile')->name('doctor.profile.')->group(function () {
    // Auth protected: remove doctor's profile picture


    
    // View / Edit
    Route::get('edit/{doctor?}', [DoctorProfileController::class, 'edit'])
        ->name('edit')
        ->whereNumber('doctor');

    Route::get('show/{doctor?}', [DoctorProfileController::class, 'show'])
        ->name('show')
        ->whereNumber('doctor');

    // Tabbed edit pages (same controller method; tab handled by query/session)
    Route::get('personal/{doctor?}', [DoctorProfileController::class, 'edit'])->name('personal')->whereNumber('doctor');
    Route::get('professional/{doctor?}', [DoctorProfileController::class, 'edit'])->name('professional')->whereNumber('doctor');
    Route::get('location/{doctor?}', [DoctorProfileController::class, 'edit'])->name('location')->whereNumber('doctor');
    Route::get('education-schedule/{doctor?}', [DoctorProfileController::class, 'edit'])->name('educationSchedule')->whereNumber('doctor');

    /* ===== Update endpoints (tabbed) ===== */
    Route::post('update/personal/{doctor?}', [DoctorProfileController::class, 'updatePersonal'])
        ->name('updatePersonal')->whereNumber('doctor');

    Route::post('update/professional/{doctor?}', [DoctorProfileController::class, 'updateProfessional'])
        ->name('updateProfessional')->whereNumber('doctor');

    Route::post('update/location/{doctor?}', [DoctorProfileController::class, 'updateLocation'])
        ->name('updateLocation')->whereNumber('doctor');

    Route::post('update/education-schedule/{doctor?}', [DoctorProfileController::class, 'updateEducationSchedule'])
        ->name('updateEducationSchedule')->whereNumber('doctor');

    // Full-profile update (Save All)
    Route::post('update/{doctor?}', [DoctorProfileController::class, 'update'])
        ->name('update')->whereNumber('doctor');

    /* ================= AJAX HELPERS ================= */

    // Dependent dropdowns
    Route::get('get-states/{countryId}', [DoctorProfileController::class, 'getStates'])
        ->name('ajax.states')->whereNumber('countryId');

    Route::get('get-districts/{stateId}', [DoctorProfileController::class, 'getDistricts'])
        ->name('ajax.districts')->whereNumber('stateId');

    Route::get('get-cities/{districtId}', [DoctorProfileController::class, 'getCities'])
        ->name('ajax.cities')->whereNumber('districtId');

    // Clinics by category
    Route::get('clinics/{categoryId}', [DoctorProfileController::class, 'clinicsByCategory'])
        ->name('ajax.clinics')->whereNumber('categoryId');

    // Pincode lookup (auth-only version)
    Route::get('pincode/{pincode}/lookup', [DoctorProfileController::class, 'pincodeLookup'])
        ->name('ajax.pincode')
        ->where('pincode', '[0-9]{6}');

    /* ===== Clinic schedule CRUD (AJAX) ===== */
    Route::get('{doctor}/schedules', [DoctorProfileController::class, 'clinicSchedulesIndex'])
        ->name('ajax.schedules.index')->whereNumber('doctor');

    Route::post('{doctor}/schedules', [DoctorProfileController::class, 'clinicSchedulesStore'])
        ->name('ajax.schedules.store')->whereNumber('doctor');

    Route::put('{doctor}/schedules/{schedule}', [DoctorProfileController::class, 'clinicSchedulesUpdate'])
        ->name('ajax.schedules.update')->whereNumber(['doctor', 'schedule']);

    Route::delete('{doctor}/schedules/{schedule}', [DoctorProfileController::class, 'clinicSchedulesDestroy'])
        ->name('ajax.schedules.destroy')->whereNumber(['doctor', 'schedule']);

}); // end auth group

// Public pincode lookup (throttled)
Route::get('/pincode/{pincode}/lookup', [DoctorProfileController::class, 'pincodeLookup'])
    ->where('pincode', '[0-9]{6}')
    ->middleware('throttle:30,1')
    ->name('pincode.lookup');
Route::get('/get-areas/{districtId}/{pincode?}', [FrontendController::class, 'getAreas']);
// // Doctor profile password routes
Route::middleware(['auth'])->group(function () {
    Route::get('/doctor/profile/password', [DoctorProfileController::class, 'editPassword'])
        ->name('doctor.profile.password.edit');
    Route::post('/doctor/profile/password', [DoctorProfileController::class, 'updatePassword'])
        ->name('doctor.profile.password.update');
});





 Route::post('/send-otp', [OTPController::class, 'sendOtp']);
// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/verify-otp', function () {
    return view('auth.verify-otp');
})->name('verify-otp');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');
    
// Route::middleware(['auth', 'role:admin'])->group(function () {
//     Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
// });

Route::middleware('auth')->group(function () {
    
// Route::get('/import-districts', [DistrictExcelController::class, 'showImportForm'])->name('district.import.form');
// Route::post('/import-districts', [DistrictExcelController::class, 'import'])->name('district.import');
Route::get('/import-districts', [DistrictExcelController::class, 'showImportForm'])
    ->name('district.import.form');

Route::post('/import-districts', [DistrictExcelController::class, 'import'])
    ->name('district.import');

Route::get('/import-districts/sample', [DistrictExcelController::class, 'downloadSample'])
    ->name('district.sample.download');
    
Route::get('/states/import', [StateExcelController::class, 'showImportForm'])->name('states.import.form');
Route::post('/states/import', [StateExcelController::class, 'import'])->name('states.import');


Route::get('/doctors/import', [DoctorExcelController::class, 'showImportForm'])->name('doctors.import.form');
Route::post('/doctors/import', [DoctorExcelController::class, 'import'])->name('doctors.import');


Route::get('/category/import', [CategoryExcelController::class, 'showImportForm'])->name('category.import.form');
Route::post('/category/import', [CategoryExcelController::class, 'import'])->name('category.import');

Route::post('/doctors/bulk-delete', [DoctorListController::class, 'bulkDelete'])->name('doctorsbulkDelete');


// Route::get('/doctor', [DoctorListAjax::class, 'index'])->name('doctor.index');
// Route::post('/doctor/store-or-update', [DoctorListAjax::class, 'storeOrUpdate'])->name('doctor.storeOrUpdate');
// Route::get('/doctor/edit/{id}', [DoctorListAjax::class, 'edit'])->name('doctor.edit');


// Route::delete('/doctor/destroy/{id}', [DoctorListAjax::class, 'destroy'])->name('doctor.destroy');



// Route::get('/doctor/getStatesByCountry/{country_id}', [DoctorListAjax::class, 'getStatesByCountry'])->name('doctor.getStatesByCountry');
// Route::get('/doctor/getDistrictsByState/{state_id}', [DoctorListAjax::class, 'getDistrictsByState'])->name('doctor.getDistrictsByState');
// Route::get('/doctor/getCitiesByDistrict/{district_id}', [DoctorListAjax::class, 'getCitiesByDistrict'])->name('doctor.getCitiesByDistrict');

// Route::get('/doctor/getCategories', [DoctorListAjax::class, 'getCategories'])->name('doctor.getCategories');
// Route::get('/doctor/getClients', [DoctorListAjax::class, 'getClients'])->name('doctor.getClients');
// Route::post('/doctor/import', [DoctorListAjax::class, 'import'])->name('doctor.import');

Route::middleware(['auth', 'web']) // Added 'web' middleware for session
    ->prefix('doctor-inline')
    ->name('doctor_inline.')
    ->group(function () {
    
    // ==================== PAGES ====================
    Route::get('/', [DoctorListAjax::class, 'index'])->name('index');
    Route::get('/create', [DoctorListAjax::class, 'create'])->name('create');
    Route::get('/{id}/edit', [DoctorListAjax::class, 'edit'])->name('edit');

    // ==================== DATA OPERATIONS ====================
    Route::get('/list', [DoctorListAjax::class, 'list'])->name('list');
    Route::post('/', [DoctorListAjax::class, 'store'])->name('store');
    
    // Single doctor data (for populating edit forms)
    Route::get('/{id}', [DoctorListAjax::class, 'show'])->name('show')
        ->where('id', '[0-9]+');

    // ==================== UPDATE OPERATIONS ====================
    // Use PUT for full updates - this is fine
    Route::put('/{id}', [DoctorListAjax::class, 'update'])
        ->name('update')
        ->where('id', '[0-9]+');
    
    // Consider adding PATCH for partial updates if needed
    // Route::patch('/{id}', [DoctorListAjax::class, 'updatePartial'])
    //     ->name('update.partial')
    //     ->where('id', '[0-9]+');

    // ==================== DELETE OPERATIONS ====================
    Route::delete('/{id}', [DoctorListAjax::class, 'destroy'])
        ->name('destroy')
        ->where('id', '[0-9]+');

    // ==================== BULK OPERATIONS ====================
    Route::post('/bulk/update', [DoctorListAjax::class, 'bulkUpdate'])->name('bulk.update');
    Route::post('/bulk/destroy', [DoctorListAjax::class, 'bulkDestroy'])->name('bulk.destroy');
    
    // You might want to add bulk status update
    Route::post('/bulk/status', [DoctorListAjax::class, 'bulkStatusUpdate'])->name('bulk.status');

    // ==================== FILE OPERATIONS ====================
    Route::post('/upload/photo', [DoctorListAjax::class, 'uploadPhoto'])->name('upload.photo');
    Route::post('/import', [DoctorListAjax::class, 'import'])->name('import');
    
    // Export routes
    Route::get('/export/sample', [DoctorListAjax::class, 'downloadSample'])->name('export.sample');
    Route::get('/export/csv', [DoctorListAjax::class, 'exportCsv'])->name('export.csv');
    Route::get('/export/excel', [DoctorListAjax::class, 'exportExcel'])->name('export.excel');
    Route::get('/export/pdf', [DoctorListAjax::class, 'exportPdf'])->name('export.pdf');

    // ==================== CASCADING DROPDOWNS ====================
    Route::get('/countries/{countryId}/states', [DoctorListAjax::class, 'statesByCountry'])
        ->name('states.by_country')
        ->where('countryId', '[0-9]+');
    
    Route::get('/states/{stateId}/districts', [DoctorListAjax::class, 'districtsByState'])
        ->name('districts.by_state')
        ->where('stateId', '[0-9]+');
    
    Route::get('/districts/{districtId}/cities', [DoctorListAjax::class, 'citiesByDistrict'])
        ->name('cities.by_district')
        ->where('districtId', '[0-9]+');
    
    Route::get('/categories/{categoryId}/clinics', [DoctorListAjax::class, 'clinicsByCategory'])
        ->name('clinics.by_category')
        ->where('categoryId', '[0-9]+');
        
    // ==================== SEARCH/STATISTICS ====================
    // Add if your controller has these methods
    Route::post('/search', [DoctorListAjax::class, 'search'])->name('search');
    Route::get('/statistics', [DoctorListAjax::class, 'statistics'])->name('statistics');
});









Route::resource('trending-doctors', TrendingDoctorController::class);
Route::resource('trending-clinic', TrendingClinicControllers::class);
Route::resource('trending-hospital', TrendingHospitalsControllers::class);
Route::resource('trending-shop', TrendingMedicalShopControllers::class);
Route::resource('medicashop', MedicaShopController::class);


    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('banner', BannerImageController::class);
    Route::resource('doctors', DoctorListController::class);
     Route::post('doctorupdateStatus', [DoctorListController::class, 'doctorupdateStatus'])->name('doctors.update-status');
    Route::resource('about', AboutController::class);
    Route::resource('hospital', HospitalController::class);
    
    Route::get('/country/upload', [CountryExcelController::class, 'showUploadForm'])->name('country.upload');
    Route::post('/country/import', [CountryExcelController::class, 'import'])->name('country.import');

    Route::resource('clients', ClientController::class);
    Route::post('updateStatus', [ClientController::class, 'updateStatus'])->name('clients.update-status');
    
    Route::delete('imageremove/{imageId}', [ClientController::class, 'removeImage'])->name('imageremove');
    
    
    Route::resource('country', CountryController::class);
    Route::resource('state', StateController::class);
    Route::resource('district', DistrictController::class);
    Route::resource('town-village', TownVillageController::class);
    //Route::get('/town-village/edit/{id}', [TownVillageController::class, 'edit']);
 

// View inline form + table

// Page + CRUD
// Page


/*
|--------------------------------------------------------------------------
| Admin: Districts / Pincodes routes
|--------------------------------------------------------------------------
|
| Grouped routes for the district/city/pincode UI. Adjust the middleware
| / prefix as needed for your project (example uses 'auth').
|
*/
Route::middleware(['web', 'auth'])->group(function () {

    /* -------------------- Districts (page + CRUD) -------------------- */
    Route::prefix('districts')->name('districts.')->group(function () {
        // Page (blade)
        Route::get('/ajax', [DistrictAjaxController::class, 'index'])->name('ajax');

        // Import (Excel/CSV)
        Route::post('/import', [DistrictAjaxController::class, 'import'])->name('import');

        // CRUD
        Route::post('/store-or-update', [DistrictAjaxController::class, 'storeOrUpdate'])->name('ajax.store');
        Route::delete('/{id}', [DistrictAjaxController::class, 'destroy'])->name('ajax.delete');

        // Lookups (AJAX)
        Route::get('/ajax/states/all', [DistrictAjaxController::class, 'getAllStates'])->name('ajax.getAllStates');
        Route::get('/ajax/states',     [DistrictAjaxController::class, 'getStatesByCountry'])->name('ajax.getStates');
        Route::get('/ajax/districts',  [DistrictAjaxController::class, 'getDistrictsByState'])->name('ajax.getDistricts');
        Route::get('/ajax/cities',     [DistrictAjaxController::class, 'getCitiesByDistrict'])->name('ajax.getCities');

        // DataTables server-side (if used elsewhere)
        Route::get('/datatable', [DistrictAjaxController::class, 'datatable'])->name('datatable');
        
        // Exports
        Route::get('/export/excel', [DistrictAjaxController::class, 'exportExcel'])->name('export.excel');
        Route::get('/export/csv',   [DistrictAjaxController::class, 'exportCsv'])->name('export.csv');
        Route::get('/export/pdf',   [DistrictAjaxController::class, 'exportPdf'])->name('export.pdf');
    });

    /* -------------------- Pincodes -------------------- */
    Route::prefix('pincodes')->name('pincodes.')->group(function () {
        // New: returns names + ids for the table
        Route::get('/all', [DistrictAjaxController::class, 'listAllPincodes'])->name('all');

        Route::get('/by-city',     [DistrictAjaxController::class, 'getPincodesByCity'])->name('by.city');
        Route::get('/by-district', [DistrictAjaxController::class, 'getPincodesByDistrict'])->name('by.district');
        Route::get('/find',        [DistrictAjaxController::class, 'findByPincode'])->name('find');

        Route::post('/store-or-update', [DistrictAjaxController::class, 'storeOrUpdatePincode'])->name('store_or_update');
        Route::delete('/{id}',          [DistrictAjaxController::class, 'destroyPincode'])->name('delete');
    });

    /* -------------------- Misc / Samples -------------------- */
    Route::prefix('locations')->name('locations.')->group(function () {
        Route::post('/store-city-pincode', [DistrictAjaxController::class, 'storeCityWithPincode'])->name('store_city_pincode');
        Route::get('/sample',              [DistrictAjaxController::class, 'downloadPincodeSample'])->name('sample');
    });
});







// Display the inline form
// Route::get('/doctors/inline-form', [DoctorController::class, 'inlineForm'])->name('doctors.inlineForm');

// // Bulk store or update doctors
// Route::post('/doctors/bulk-store', [DoctorController::class, 'bulkStore'])->name('doctors.bulkStore');

// // Bulk delete doctors
// Route::delete('/doctors/bulk-delete', [DoctorController::class, 'bulkDelete'])->name('doctors.bulkDelete');

// // Inline save (AJAX)
// Route::post('/doctors/inline-save', [DoctorController::class, 'inlineSave'])->name('doctors.inlineSave');

// // Delete doctor via AJAX
// Route::delete('/doctors/ajax-destroy/{id}', [DoctorController::class, 'ajaxDestroy'])->name('doctors.ajaxDestroy');




    
    
    
    
    
    
    
    
    
    
    
    
Route::prefix('location')->name('location.')->group(function () {
    // country → states
    Route::get('/states/{country}', [LocationController::class, 'getStates'])
        ->name('states')->whereNumber('country');

    // state → districts
    Route::get('/districts/{state}', [LocationController::class, 'getDistricts'])
        ->name('districts')->whereNumber('state');

    // district → towns/villages
    Route::get('/towns/{district}', [LocationController::class, 'getTowns'])
        ->name('towns')->whereNumber('district');
});
    
// Normal Category Routes
Route::prefix('category')->name('category.')->group(function () {
    Route::get('/', [CategoryController::class, 'index'])->name('index');
    Route::get('/create', [CategoryController::class, 'create'])->name('create');
    Route::post('/store', [CategoryController::class, 'store'])->name('store');
    Route::get('/edit/{id}', [CategoryController::class, 'edit'])->name('edit');
    Route::put('/update/{id}', [CategoryController::class, 'update'])->name('update');
    Route::delete('/delete/{id}', [CategoryController::class, 'destroy'])->name('destroy');
    Route::delete('/bulk-delete', [CategoryController::class, 'bulkDelete'])->name('bulkDelete');
});

// Ajax Category Routes
// Ajax Category Routes (fixed ordering + ID constraint)
Route::prefix('categories')->name('categories.')->group(function () {
    // Page
    Route::get('/',               [CategoryAjaxController::class,'index'])->name('index');

    // Data API
    Route::get('/list',           [CategoryAjaxController::class,'list'])->name('list');
    Route::post('/',              [CategoryAjaxController::class,'store'])->name('store');

    // Bulk routes (must come BEFORE parameterized {id} routes)
    Route::post('/bulk-update',   [CategoryAjaxController::class,'bulkUpdate'])->name('bulkUpdate');
    Route::delete('/bulk-delete', [CategoryAjaxController::class,'bulkDelete'])->name('bulkDelete');

    // File/image
    Route::post('/upload-image',  [CategoryAjaxController::class,'uploadImage'])->name('uploadImage');

    // Import/Export
    Route::post('/import',        [CategoryAjaxController::class,'importExcel'])->name('import');
    Route::get('/sample',         [CategoryAjaxController::class,'downloadSample'])->name('sample');
    Route::get('/export/csv',     [CategoryAjaxController::class,'exportCsv'])->name('export.csv');
    Route::get('/export/excel',   [CategoryAjaxController::class,'exportExcel'])->name('export.excel');
    Route::get('/export/pdf',     [CategoryAjaxController::class,'exportPdf'])->name('export.pdf');

    // Single-item routes (constrained to numeric id so 'bulk-delete' won't match)
    Route::post('/{id}',          [CategoryAjaxController::class,'update'])->name('update')->whereNumber('id'); // using POST for updates
    Route::delete('/{id}',        [CategoryAjaxController::class,'destroy'])->name('destroy')->whereNumber('id');
});




        Route::prefix('subcategory')->name('subcategory.')->group(function () {
            Route::get('/', [SubcategoryController::class, 'index'])->name('index');
            Route::get('/create', [SubcategoryController::class, 'create'])->name('create');
            Route::post('/store', [SubcategoryController::class, 'store'])->name('store');
            Route::get('/edit/{id}', [SubcategoryController::class, 'edit'])->name('edit');
            Route::put('/update/{id}', [SubcategoryController::class, 'update'])->name('update');
            Route::delete('/delete/{id}', [SubcategoryController::class, 'destroy'])->name('destroy');
        });
        Route::prefix('pages')->name('pages.')->group(function () {
            Route::get('/', [PageController::class, 'index'])->name('index');
            Route::get('/create', [PageController::class, 'create'])->name('create');
            Route::post('/store', [PageController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [PageController::class, 'edit'])->name('edit');
            Route::put('/{id}', [PageController::class, 'update'])->name('update');
            Route::delete('/{id}', [PageController::class, 'destroy'])->name('destroy');
        });
        
        Route::prefix('advertisement')->name('advertisement.')->group(function () {
            Route::get('/', [AdvertisementController::class, 'index'])->name('index');
            Route::get('/create', [AdvertisementController::class, 'create'])->name('create');
            Route::post('/', [AdvertisementController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [AdvertisementController::class, 'edit'])->name('edit');
            Route::put('/{id}', [AdvertisementController::class, 'update'])->name('update');
            Route::delete('/{id}', [AdvertisementController::class, 'destroy'])->name('destroy');
        });
        Route::prefix('social_links')->name('social_links.')->group(function () {
            Route::get('/', [SocialLinkController::class, 'index'])->name('index');
            Route::get('/create', [SocialLinkController::class, 'create'])->name('create');
            Route::post('/', [SocialLinkController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [SocialLinkController::class, 'edit'])->name('edit');
            Route::put('/{id}', [SocialLinkController::class, 'update'])->name('update');
            Route::delete('/{id}', [SocialLinkController::class, 'destroy'])->name('destroy');
        });
        
        Route::prefix('about')->group(function () {
            Route::get('/about-us/edit', [AboutController::class, 'edit'])->name('about-us.edit');
            Route::put('/about-us/update', [AboutController::class, 'update'])->name('about-us.update');
        });
        Route::prefix('contact')->group(function () {
            Route::get('/contact-us/edit', [ContactUsController::class, 'edit'])->name('contact-us.edit');
            Route::put('/contact-us/update', [ContactUsController::class, 'update'])->name('contact-us.update');
        });
    });
    
    
Route::get('/', [FrontendController::class, 'index'])->name('home');
Route::get('/about', [FrontendController::class, 'about'])->name('about');
Route::get('/contact', [FrontendController::class, 'contact'])->name('contact');


Route::get('/terms', [FrontendController::class, 'terms'])->name('terms');
Route::get('/privacy-policy', [FrontendController::class, 'privacy'])->name('privacy');   

Route::get('/search/suggestions', [FrontendController::class, 'suggestions'])->name('search.suggestions');
Route::match(['get','post'], '/search', [FrontendController::class, 'search'])->name('home.search');
Route::get('/generalSearch', [FrontendController::class, 'generalSearch'])->name('generalSearch');

Route::get('/clinicDetails/{id}', [FrontendController::class, 'clinicDetails'])->name('clinic.details');
Route::get('/categoryDetails/{slug}', [FrontendController::class, 'categoryDetails'])->name('category.details');

Route::get('/doctor/{id}', [FrontendController::class, 'show'])->name('doctor.details');

Route::post('/rate-doctor', [FrontendController::class, 'ratingstore'])->name('rate.doctor');
Route::post('/ratedoctor', [FrontendController::class, 'rateDoctor'])->name('rating.doctor');

Route::get('/listclinic', [FrontendController::class, 'listclinic'])->name('listclinic');
Route::post('/listclinicstore', [FrontendController::class, 'listclinicstore'])->name('listclinicstore');

Route::get('/listdoctor', [FrontendController::class, 'listdoctor'])->name('listdoctor');
Route::post('/listdoctorstore', [FrontendController::class, 'listdoctorstore'])->name('listdoctorstore');
Route::get('/listdoctor/success', [FrontendController::class, 'doctorSuccess'])->name('listdoctor.success');

Route::get('/get-clinics-by-city/{city_name}', [FrontendController::class, 'getClinicsByCity'])->name('getClinicsByCity');
Route::get('/get-clinics-by-state/{state_name}', [FrontendController::class, 'getClinicsByState'])->name('getClinicsByState');
Route::get('/get-top-categories', [FrontendController::class, 'getTopCategories'])->name('getTopCategories');

Route::get('/get-districts/{state_id}', [FrontendController::class, 'getDistricts'])->name('getDistricts');
Route::get('/pincode/lookup/{pincode}', [FrontendController::class, 'lookupPincode'])->name('pincode.lookup');

Route::post('/send-email', [FrontendController::class, 'sendEmail'])->name('send.email');

Route::post('/ajax/check-email', [FrontendController::class, 'ajaxCheckEmail'])->name('ajax.check-email');   












Route::get('/get-states/{country_id}', [LocationFrontController::class, 'getStates']);
    Route::get('/get-districts/{state_id}', [LocationFrontController::class, 'getDistricts']);
    Route::get('get-towns/{district_id}', [LocationFrontController::class, 'getTowns']);
    Route::get('/get-doctors/{cityName}', [LocationFrontController::class, 'getDoctors']);
    Route::get('/doctor-rating/{id}', [LocationFrontController::class, 'getDoctorRating']);
 
    
    Route::get('/search', [SearchController::class, 'search'])->name('search');
 
Route::get('/search-suggestions', [SearchController::class, 'getSuggestions'])->name('search.suggestions');

Route::get('/logout', function () {
    Session::forget('auth_id'); // Remove auth_id from session
    return redirect()->route('home');
})->name('logout');

require __DIR__.'/auth.php';