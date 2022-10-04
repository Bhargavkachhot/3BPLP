<?php
 
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\EmailTemplateController;
use App\Http\Controllers\Dashboard\UserController; 
use App\Http\Controllers\Dashboard\RolesController;
use App\Http\Controllers\Dashboard\WebmasterSettingsController; 
use App\Http\Controllers\Dashboard\PlpCategories\PrimaryCategoryController;  
use App\Http\Controllers\Dashboard\PlpCategories\SubCategoryController;  
use App\Http\Controllers\Dashboard\PlpCategories\ProductCategoryController; 
use App\Http\Controllers\Dashboard\PlpCategories\ExampleProductController;  
use App\Http\Controllers\Dashboard\PlpAttributes\ProductAttributesController; 
use App\Http\Controllers\Dashboard\PlpAttributes\PrimaryPackagingController;
use App\Http\Controllers\Dashboard\PlpAttributes\PrimaryPackagingAttributesController; 
use App\Http\Controllers\Dashboard\PlpAttributes\SkuPackagingController;
use App\Http\Controllers\Dashboard\PlpAttributes\CountriesOfOriginController; 
use App\Http\Controllers\Dashboard\PlpAttributes\CountriesOfDestinationController;
use App\Http\Controllers\Dashboard\PlpAttributes\CapacityController; 
use App\Http\Controllers\Dashboard\PlpAttributes\CertificateController;
use App\Http\Controllers\Dashboard\CmsController;
use App\Http\Controllers\Dashboard\FaqController; 
use App\Http\Controllers\Dashboard\CustomerController;





/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "admin" middleware group. Now create something great!
|
*/


///////////////////////////************** Admin Home ********************//////////////////////////////// 

Route::get('/admin/login', [\App\Http\Controllers\Auth\LoginController::class, 'showMainuserLoginForm'])->name('admin');
Route::get('/admin-home', [DashboardController::class, 'index'])->name('adminHome');
Route::post('/filter', [DashboardController::class, 'index'])->name('dashboardfilter'); 


///////////////////////////************** Search ********************////////////////////////////////


Route::get('/search', [DashboardController::class, 'search'])->name('adminSearch');
Route::post('/find', [DashboardController::class, 'find'])->name('adminFind');
 

///////////////////////////************** Admins & Permissions ******************////////////////////////////////


Route::get('/change-password', [DashboardController::class, 'changePassword'])->name('admin-change-password');
Route::post('/update-password', [DashboardController::class, 'updatePassword'])->name('admin-update-password');
Route::get('/profile/{id}/edit', [DashboardController::class, 'edit'])->name('admin.profile'); 
Route::post('/profile/{id}/update', [DashboardController::class, 'update'])->name('admin.profile.update');
 

///////////////////////////************** emailtemplate Management *************////////////////////////////////


Route::get('/emailtemplate', [EmailTemplateController::class,'index'])->name('emailtemplate');
Route::get('/emailtemplate/create', [EmailTemplateController::class,'create'])->name('emailtemplate.create');
Route::post('/emailtemplate/store', [EmailTemplateController::class,'store'])->name('emailtemplate.store');
Route::get('/emailtemplate/edit/{id}',[EmailTemplateController::class,'edit'])->name('emailtemplate.edit');
Route::post('/emailtemplate/update/{id}',[EmailTemplateController::class,'update'])->name('emailtemplate.update');
Route::get('/emailtemplate/show/{id}',[EmailTemplateController::class,'show'])->name('emailtemplate.show');
Route::post('/emailtemplate/anyData',[EmailTemplateController::class,'anyData'])->name('emailtemplate.anyData');
Route::get('/emailtemplate/{parentId}/addlang/{langId}', [EmailTemplateController::class,'multiLang'])->name('emailtemplate.multiLang');
Route::post('emailtemplate/storeLang', [EmailTemplateController::class,'storeLang'])->name('emailtemplate.storeLang');

 

///////////////////////////************** Webmaster ********************////////////////////////////////


Route::get('/webmaster', [WebmasterSettingsController::class, 'edit'])->name('webmasterSettings');
Route::post('/webmaster', [WebmasterSettingsController::class, 'update'])->name('webmasterSettingsUpdate');
Route::post('/webmaster/languages/store', [WebmasterSettingsController::class, 'language_store'])->name('webmasterLanguageStore');
Route::post('/webmaster/languages/store', [WebmasterSettingsController::class, 'language_store'])->name('webmasterLanguageStore');
Route::post('/webmaster/languages/update', [WebmasterSettingsController::class, 'language_update'])->name('webmasterLanguageUpdate');
Route::get('/webmaster/languages/destroy/{id}', [WebmasterSettingsController::class, 'language_destroy'])->name('webmasterLanguageDestroy');
Route::get('/webmaster/seo/repair', [WebmasterSettingsController::class, 'seo_repair'])->name('webmasterSEORepair');
Route::post('/webmaster/mail/smtp', [WebmasterSettingsController::class, 'mail_smtp_check'])->name('mailSMTPCheck');
Route::post('/webmaster/mail/test', [WebmasterSettingsController::class, 'mail_test'])->name('mailTest');
 


///////////////////////////************** roles ********************////////////////////////////////

Route::get('/roles', [RolesController::class, 'index'])->name('roles'); 
Route::post('/roles/anyData',[RolesController::class,'anyData'])->name('roles.anyData');  
Route::get('/roles/create',[RolesController::class,'create'])->name('roles.create'); 
Route::post('/roles/store-permission',[RolesController::class,'StorePermission'])->name('role.store.permission');  
Route::get('/roles/edit/{id}',[RolesController::class,'edit'])->name('roles.edit');
Route::post('/roles/update/{id}',[RolesController::class,'update'])->name('roles.update');
Route::get('/roles/show/{id}',[RolesController::class,'show'])->name('roles.show');
Route::post('/roles/delete', [RolesController::class,'destroy'])->name('roles.delete'); 
Route::post('/roles/edit-permission-filter',[RolesController::class,'PermissionFilter'])->name('roles.edit.permission.filter'); 
Route::post('/roles/update-permission',[RolesController::class,'UpdatePermission'])->name('role.update.permission');   
Route::get('/export/roles', [RolesController::class,'export'])->name('export.roles'); 


///////////////////////////************** Users ********************////////////////////////////////

Route::get('/users', [UserController::class, 'index'])->name('users');
Route::get('/users/anyData',[UserController::class,'anyData'])->name('users.anyData'); 
Route::get('/users/create',[UserController::class,'create'])->name('user.create');  
Route::post('/users/store',[UserController::class,'Store'])->name('user.store'); 
Route::post('/users/delete', [UserController::class,'destroy'])->name('user.delete'); 
Route::get('/users/edit/{id}',[UserController::class,'edit'])->name('user.edit');
Route::post('/users/update',[UserController::class,'update'])->name('user.update');
Route::get('/users/show/{id}',[UserController::class,'show'])->name('user.show'); 
Route::get('/export/users', [UserController::class,'export'])->name('export.users'); 
Route::POST('/user/bulk-action', [UserController::class,'BulkAction'])->name('user.bulk.action'); 



///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////   PLP  Categories //////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////



///////////////////////////************** Primary Categories ********************////////////////////////////////


Route::get('/primary-categories', [PrimaryCategoryController::class, 'index'])->name('primary.categories');
Route::get('/primary-categories/anyData',[PrimaryCategoryController::class,'anyData'])->name('primary.categories.anyData');  
Route::get('/primary-categories/create',[PrimaryCategoryController::class,'create'])->name('primary.categories.create');  
Route::post('/primary-categories/store',[PrimaryCategoryController::class,'Store'])->name('primary.categories.store'); 
Route::get('/primary-categories/edit/{id}',[PrimaryCategoryController::class,'edit'])->name('primary.categories.edit');
Route::get('/primary-categories/show/{id}',[PrimaryCategoryController::class,'show'])->name('primary.categories.show');
Route::post('/primary-categories/delete', [PrimaryCategoryController::class,'destroy'])->name('primary.categories.delete');
Route::post('/primary-categories/update',[PrimaryCategoryController::class,'update'])->name('primary.categories.update'); 
Route::get('/export/primary-categories', [PrimaryCategoryController::class,'export'])->name('export.primary.categories'); 
Route::POST('/primary-categories/bulk-action', [PrimaryCategoryController::class,'BulkAction'])->name('primary.categories.bulk.action'); 


///////////////////////////************** Sub Categories ********************////////////////////////////////


Route::get('/subcategories', [SubCategoryController::class, 'index'])->name('subcategories');
Route::get('/subcategories/anyData',[SubCategoryController::class,'anyData'])->name('subcategories.anyData');  
Route::get('/subcategories/create',[SubCategoryController::class,'create'])->name('subcategories.create');  
Route::post('/subcategories/store',[SubCategoryController::class,'Store'])->name('subcategories.store'); 
Route::get('/subcategories/edit/{id}',[SubCategoryController::class,'edit'])->name('subcategories.edit');
Route::get('/subcategories/show/{id}',[SubCategoryController::class,'show'])->name('subcategories.show');
Route::post('/subcategories/delete', [SubCategoryController::class,'destroy'])->name('subcategories.delete');
Route::post('/subcategories/update',[SubCategoryController::class,'update'])->name('subcategories.update'); 
Route::get('/export/subcategories', [SubCategoryController::class,'export'])->name('export.subcategories'); 
Route::POST('/get-url_key/subcategories', [SubCategoryController::class,'GetUrlKey'])->name('subcategories.get.primary_category_url_key');  
Route::POST('/subcategories/bulk-action', [SubCategoryController::class,'BulkAction'])->name('subcategories.bulk.action'); 

///////////////////////////************** Product Categories ********************////////////////////////////////


Route::get('/product-categories', [ProductCategoryController::class, 'index'])->name('product.categories');
Route::get('/product-categories/anyData',[ProductCategoryController::class,'anyData'])->name('product.categories.anyData');  
Route::get('/product-categories/create',[ProductCategoryController::class,'create'])->name('product.categories.create');  
Route::post('/product-categories/store',[ProductCategoryController::class,'Store'])->name('product.categories.store'); 
Route::get('/product-categories/edit/{id}',[ProductCategoryController::class,'edit'])->name('product.categories.edit');
Route::get('/product-categories/show/{id}',[ProductCategoryController::class,'show'])->name('product.categories.show');
Route::post('/product-categories/delete', [ProductCategoryController::class,'destroy'])->name('product.categories.delete');
Route::post('/product-categories/update',[ProductCategoryController::class,'update'])->name('product.categories.update'); 
Route::get('/export/product-categories', [ProductCategoryController::class,'export'])->name('export.product.categories');  
Route::POST('/get-url_key/product-categories', [ProductCategoryController::class,'GetUrlKey'])->name('product.categories.get.primary_category_url_key'); 
Route::POST('/get-subcategory-url_key/product-categories', [ProductCategoryController::class,'GetSubCategoryUrlKey'])->name('product.categories.get.subcategory_url_key'); 
Route::POST('/get-primary-category', [ProductCategoryController::class,'GetPrimaryCategory'])->name('product.get.primary.category'); 

Route::POST('/product-categories/bulk-action', [ProductCategoryController::class,'BulkAction'])->name('product.categories.bulk.action'); 

///////////////////////////************** Example Products ********************////////////////////////////////


Route::get('/example-products', [ExampleProductController::class, 'index'])->name('example.products');
Route::get('/example-products/anyData',[ExampleProductController::class,'anyData'])->name('example.products.anyData');  
Route::get('/example-products/create',[ExampleProductController::class,'create'])->name('example.products.create');  
Route::post('/example-products/store',[ExampleProductController::class,'Store'])->name('example.products.store'); 
Route::get('/example-products/edit/{id}',[ExampleProductController::class,'edit'])->name('example.products.edit');
Route::get('/example-products/show/{id}',[ExampleProductController::class,'show'])->name('example.products.show');
Route::post('/example-products/delete', [ExampleProductController::class,'destroy'])->name('example.products.delete');
Route::post('/example-products/update',[ExampleProductController::class,'update'])->name('example.products.update'); 
Route::get('/export/example-products', [ExampleProductController::class,'export'])->name('export.example.products');  
Route::POST('/get-example-products-relational-data', [ExampleProductController::class,'GetRelationalData'])->name('example.product.get.data'); 
Route::POST('/example-products/bulk-action', [ExampleProductController::class,'BulkAction'])->name('example.products.bulk.action'); 



///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////   PLP  Attributes //////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////


///////////////////////////************** Product Products ********************////////////////////////////////


Route::get('/product-attributes', [ProductAttributesController::class, 'index'])->name('product.attributes');
Route::get('/product-attributes/anyData',[ProductAttributesController::class,'anyData'])->name('product.attributes.anyData');  
Route::get('/product-attributes/create',[ProductAttributesController::class,'create'])->name('product.attributes.create');  
Route::post('/product-attributes/store',[ProductAttributesController::class,'Store'])->name('product.attributes.store'); 
Route::get('/product-attributes/edit/{id}',[ProductAttributesController::class,'edit'])->name('product.attributes.edit');
Route::get('/product-attributes/show/{id}',[ProductAttributesController::class,'show'])->name('product.attributes.show');
Route::post('/product-attributes/delete', [ProductAttributesController::class,'destroy'])->name('product.attributes.delete');
Route::post('/product-attributes/update',[ProductAttributesController::class,'update'])->name('product.attributes.update'); 
Route::get('/export/product-attributes', [ProductAttributesController::class,'export'])->name('export.product.attributes');  
Route::get('/product-attributes/import', [ProductAttributesController::class,'import'])->name('product.attributes.import'); 
Route::POST('/import-store/product-attributes', [ProductAttributesController::class,'importStore'])->name('product.attributes.import.store');   
Route::POST('/product-attributes/tags-subcategory', [ProductAttributesController::class,'SubcategoryTags'])->name('product.attributes.tags.subcategory');
Route::POST('/product-attributes/tags-product-category', [ProductAttributesController::class,'ProductCategoryTags'])->name('product.attributes.tags.product.category');   
Route::POST('/product-attributes/bulk-action', [ProductAttributesController::class,'BulkAction'])->name('product.attributes.bulk.action');



///////////////////////////************** Primary Packaging ********************////////////////////////////////


Route::get('/primary-packaging', [PrimaryPackagingController::class, 'index'])->name('primary.packaging');
Route::get('/primary-packaging/anyData',[PrimaryPackagingController::class,'anyData'])->name('primary.packaging.anyData');  
Route::get('/primary-packaging/create',[PrimaryPackagingController::class,'create'])->name('primary.packaging.create');  
Route::post('/primary-packaging/store',[PrimaryPackagingController::class,'Store'])->name('primary.packaging.store'); 
Route::get('/primary-packaging/edit/{id}',[PrimaryPackagingController::class,'edit'])->name('primary.packaging.edit');
Route::get('/primary-packaging/show/{id}',[PrimaryPackagingController::class,'show'])->name('primary.packaging.show');
Route::post('/primary-packaging/delete', [PrimaryPackagingController::class,'destroy'])->name('primary.packaging.delete');
Route::post('/primary-packaging/update',[PrimaryPackagingController::class,'update'])->name('primary.packaging.update'); 
Route::get('/export/primary-packaging', [PrimaryPackagingController::class,'export'])->name('export.primary.packaging');  
Route::get('/primary-packaging/import', [PrimaryPackagingController::class,'import'])->name('primary.packaging.import'); 
Route::POST('/import-store/primary-packaging', [PrimaryPackagingController::class,'importStore'])->name('primary.packaging.import.store');   
Route::POST('/primary-packaging/tags-subcategory', [PrimaryPackagingController::class,'SubcategoryTags'])->name('primary.packaging.tags.subcategory');
Route::POST('/primary-packaging/tags-product-category', [PrimaryPackagingController::class,'ProductCategoryTags'])->name('primary.packaging.tags.product.category');   
Route::POST('/primary-packaging/bulk-action', [PrimaryPackagingController::class,'BulkAction'])->name('primary.packaging.bulk.action');


///////////////////////////************** Primary Packaging ********************////////////////////////////////


Route::get('/primary-packaging', [PrimaryPackagingController::class, 'index'])->name('primary.packaging');
Route::get('/primary-packaging/anyData',[PrimaryPackagingController::class,'anyData'])->name('primary.packaging.anyData');  
Route::get('/primary-packaging/create',[PrimaryPackagingController::class,'create'])->name('primary.packaging.create');  
Route::post('/primary-packaging/store',[PrimaryPackagingController::class,'Store'])->name('primary.packaging.store'); 
Route::get('/primary-packaging/edit/{id}',[PrimaryPackagingController::class,'edit'])->name('primary.packaging.edit');
Route::get('/primary-packaging/show/{id}',[PrimaryPackagingController::class,'show'])->name('primary.packaging.show');
Route::post('/primary-packaging/delete', [PrimaryPackagingController::class,'destroy'])->name('primary.packaging.delete');
Route::post('/primary-packaging/update',[PrimaryPackagingController::class,'update'])->name('primary.packaging.update'); 
Route::get('/export/primary-packaging', [PrimaryPackagingController::class,'export'])->name('export.primary.packaging');  
Route::get('/primary-packaging/import', [PrimaryPackagingController::class,'import'])->name('primary.packaging.import'); 
Route::POST('/import-store/primary-packaging', [PrimaryPackagingController::class,'importStore'])->name('primary.packaging.import.store');   
Route::POST('/primary-packaging/tags-subcategory', [PrimaryPackagingController::class,'SubcategoryTags'])->name('primary.packaging.tags.subcategory');
Route::POST('/primary-packaging/tags-product-category', [PrimaryPackagingController::class,'ProductCategoryTags'])->name('primary.packaging.tags.product.category');   
Route::POST('/primary-packaging/bulk-action', [PrimaryPackagingController::class,'BulkAction'])->name('primary.packaging.bulk.action');


///////////////////////////************** Primary Packaging attributes ********************////////////////////////////////


Route::get('/packaging-attributes', [PrimaryPackagingAttributesController::class, 'index'])->name('primary.packaging.attributes');
Route::get('/packaging-attributes/anyData',[PrimaryPackagingAttributesController::class,'anyData'])->name('primary.packaging.attributes.anyData');  
Route::get('/packaging-attributes/create',[PrimaryPackagingAttributesController::class,'create'])->name('primary.packaging.attributes.create');  
Route::post('/packaging-attributes/store',[PrimaryPackagingAttributesController::class,'Store'])->name('primary.packaging.attributes.store'); 
Route::get('/packaging-attributes/edit/{id}',[PrimaryPackagingAttributesController::class,'edit'])->name('primary.packaging.attributes.edit');
Route::get('/packaging-attributes/show/{id}',[PrimaryPackagingAttributesController::class,'show'])->name('primary.packaging.attributes.show');
Route::post('/packaging-attributes/delete', [PrimaryPackagingAttributesController::class,'destroy'])->name('primary.packaging.attributes.delete');
Route::post('/packaging-attributes/update',[PrimaryPackagingAttributesController::class,'update'])->name('primary.packaging.attributes.update'); 
Route::get('/export/packaging-attributes', [PrimaryPackagingAttributesController::class,'export'])->name('export.primary.packaging.attributes');  
Route::POST('/packaging-attributes/bulk-action', [PrimaryPackagingAttributesController::class,'BulkAction'])->name('primary.packaging.attributes.bulk.action');


///////////////////////////************** SKU Packaging ********************////////////////////////////////


Route::get('/sku-packaging', [SkuPackagingController::class, 'index'])->name('sku.packaging');
Route::get('/sku-packaging/anyData',[SkuPackagingController::class,'anyData'])->name('sku.packaging.anyData');  
Route::get('/sku-packaging/create',[SkuPackagingController::class,'create'])->name('sku.packaging.create');  
Route::post('/sku-packaging/store',[SkuPackagingController::class,'Store'])->name('sku.packaging.store'); 
Route::get('/sku-packaging/edit/{id}',[SkuPackagingController::class,'edit'])->name('sku.packaging.edit');
Route::get('/sku-packaging/show/{id}',[SkuPackagingController::class,'show'])->name('sku.packaging.show');
Route::post('/sku-packaging/delete', [SkuPackagingController::class,'destroy'])->name('sku.packaging.delete');
Route::post('/sku-packaging/update',[SkuPackagingController::class,'update'])->name('sku.packaging.update'); 
Route::get('/export/sku-packaging', [SkuPackagingController::class,'export'])->name('export.sku.packaging');  
Route::get('/sku-packaging/import', [SkuPackagingController::class,'import'])->name('sku.packaging.import'); 
Route::POST('/import-store/sku-packaging', [SkuPackagingController::class,'importStore'])->name('sku.packaging.import.store');   
Route::POST('/sku-packaging/tags-subcategory', [SkuPackagingController::class,'SubcategoryTags'])->name('sku.packaging.tags.subcategory');
Route::POST('/sku-packaging/tags-product-category', [SkuPackagingController::class,'ProductCategoryTags'])->name('sku.packaging.tags.product.category');   
Route::POST('/sku-packaging/bulk-action', [SkuPackagingController::class,'BulkAction'])->name('sku.packaging.bulk.action');


///////////////////////////************** Countries of origin  ********************////////////////////////////////


Route::get('/countries-of-origin', [CountriesOfOriginController::class, 'index'])->name('countries.of.origin');
Route::get('/countries-of-origin/anyData',[CountriesOfOriginController::class,'anyData'])->name('countries.of.origin.anyData');  
Route::get('/countries-of-origin/create',[CountriesOfOriginController::class,'create'])->name('countries.of.origin.create');  
Route::post('/countries-of-origin/store',[CountriesOfOriginController::class,'Store'])->name('countries.of.origin.store'); 
Route::get('/countries-of-origin/edit/{id}',[CountriesOfOriginController::class,'edit'])->name('countries.of.origin.edit');
Route::get('/countries-of-origin/show/{id}',[CountriesOfOriginController::class,'show'])->name('countries.of.origin.show');
Route::post('/countries-of-origin/delete', [CountriesOfOriginController::class,'destroy'])->name('countries.of.origin.delete');
Route::post('/countries-of-origin/update',[CountriesOfOriginController::class,'update'])->name('countries.of.origin.update'); 
Route::get('/export/countries-of-origin', [CountriesOfOriginController::class,'export'])->name('export.countries.of.origin'); 
Route::get('/countries-of-origin/import', [CountriesOfOriginController::class,'import'])->name('countries.of.origin.import'); 
Route::POST('/import-store/countries-of-origin', [CountriesOfOriginController::class,'importStore'])->name('countries.of.origin.import.store');   
Route::POST('/countries-of-origin/tags-subcategory', [CountriesOfOriginController::class,'SubcategoryTags'])->name('countries.of.origin.tags.subcategory');
Route::POST('/countries-of-origin/tags-product-category', [CountriesOfOriginController::class,'ProductCategoryTags'])->name('countries.of.origin.tags.product.category');   
Route::POST('/countries-of-origin/bulk-action', [CountriesOfOriginController::class,'BulkAction'])->name('countries.of.origin.bulk.action');



///////////////////////////************** Countries of destination  ********************////////////////////////////////


Route::get('/countries-of-destination', [CountriesOfDestinationController::class, 'index'])->name('countries.of.destination');
Route::get('/countries-of-destination/anyData',[CountriesOfDestinationController::class,'anyData'])->name('countries.of.destination.anyData');  
Route::get('/countries-of-destination/create',[CountriesOfDestinationController::class,'create'])->name('countries.of.destination.create');  
Route::post('/countries-of-destination/store',[CountriesOfDestinationController::class,'Store'])->name('countries.of.destination.store'); 
Route::get('/countries-of-destination/edit/{id}',[CountriesOfDestinationController::class,'edit'])->name('countries.of.destination.edit');
Route::get('/countries-of-destination/show/{id}',[CountriesOfDestinationController::class,'show'])->name('countries.of.destination.show');
Route::post('/countries-of-destination/delete', [CountriesOfDestinationController::class,'destroy'])->name('countries.of.destination.delete');
Route::post('/countries-of-destination/update',[CountriesOfDestinationController::class,'update'])->name('countries.of.destination.update'); 
Route::get('/export/countries-of-destination', [CountriesOfDestinationController::class,'export'])->name('export.countries.of.destination'); 
Route::get('/countries-of-destination/import', [CountriesOfDestinationController::class,'import'])->name('countries.of.destination.import'); 
Route::POST('/import-store/countries-of-destination', [CountriesOfDestinationController::class,'importStore'])->name('countries.of.destination.import.store');   
Route::POST('/countries-of-destination/tags-subcategory', [CountriesOfDestinationController::class,'SubcategoryTags'])->name('countries.of.destination.tags.subcategory');
Route::POST('/countries-of-destination/tags-product-category', [CountriesOfDestinationController::class,'ProductCategoryTags'])->name('countries.of.destination.tags.product.category');   
Route::POST('/countries-of-destination/bulk-action', [CountriesOfDestinationController::class,'BulkAction'])->name('countries.of.destination.bulk.action');



///////////////////////////************** Capacities  ********************////////////////////////////////


Route::get('/capacities', [CapacityController::class, 'index'])->name('capacities');
Route::get('/capacities/anyData',[CapacityController::class,'anyData'])->name('capacities.anyData');  
Route::get('/capacities/create',[CapacityController::class,'create'])->name('capacities.create');  
Route::post('/capacities/store',[CapacityController::class,'Store'])->name('capacities.store'); 
Route::get('/capacities/edit/{id}',[CapacityController::class,'edit'])->name('capacities.edit');
Route::get('/capacities/show/{id}',[CapacityController::class,'show'])->name('capacities.show');
Route::post('/capacities/delete', [CapacityController::class,'destroy'])->name('capacities.delete');
Route::post('/capacities/update',[CapacityController::class,'update'])->name('capacities.update'); 
Route::get('/export/capacities', [CapacityController::class,'export'])->name('export.capacities'); 
Route::get('/capacities/import', [CapacityController::class,'import'])->name('capacities.import'); 
Route::POST('/import-store/capacities', [CapacityController::class,'importStore'])->name('capacities.import.store');   
Route::POST('/capacities/tags-subcategory', [CapacityController::class,'SubcategoryTags'])->name('capacities.tags.subcategory');
Route::POST('/capacities/tags-product-category', [CapacityController::class,'ProductCategoryTags'])->name('capacities.tags.product.category');   
Route::POST('/capacities/bulk-action', [CapacityController::class,'BulkAction'])->name('capacities.bulk.action');


///////////////////////////************** Certificates  ********************////////////////////////////////


Route::get('/certificates', [CertificateController::class, 'index'])->name('certificates');
Route::get('/certificates/anyData',[CertificateController::class,'anyData'])->name('certificates.anyData');  
Route::get('/certificates/create',[CertificateController::class,'create'])->name('certificates.create');  
Route::post('/certificates/store',[CertificateController::class,'Store'])->name('certificates.store'); 
Route::get('/certificates/edit/{id}',[CertificateController::class,'edit'])->name('certificates.edit');
Route::get('/certificates/show/{id}',[CertificateController::class,'show'])->name('certificates.show');
Route::post('/certificates/delete', [CertificateController::class,'destroy'])->name('certificates.delete');
Route::post('/certificates/update',[CertificateController::class,'update'])->name('certificates.update'); 
Route::get('/export/certificates', [CertificateController::class,'export'])->name('export.certificates'); 
Route::get('/certificates/import', [CertificateController::class,'import'])->name('certificates.import'); 
Route::POST('/import-store/certificates', [CertificateController::class,'importStore'])->name('certificates.import.store');   
Route::POST('/certificates/tags-subcategory', [CertificateController::class,'SubcategoryTags'])->name('certificates.tags.subcategory');
Route::POST('/certificates/tags-product-category', [CertificateController::class,'ProductCategoryTags'])->name('certificates.tags.product.category');   
Route::POST('/certificates/bulk-action', [CertificateController::class,'BulkAction'])->name('certificates.bulk.action');





///////////////////////////************** CMS Management ********************////////////////////////////////


Route::get('/cms', [CmsController::class,'index'])->name('cms');
Route::get('/cms/create', [CmsController::class,'create'])->name('cms.create');
Route::post('/cms/store', [CmsController::class,'store'])->name('cms.store');
Route::POST('/cms/delete/', [CmsController::class,'destroy'])->name('cms.delete');
Route::get('/cms/show/{id}', [CmsController::class,'show'])->name('cms.show');
Route::get('cms/edit/{id}', [CmsController::class,'edit'])->name('cms.edit');
Route::post('cms/update/{id}', [CmsController::class,'update'])->name('cms.update');
Route::get('cms/anyData', [CmsController::class,'anyData'])->name('cms.anyData');
Route::get('cms/cms-edit/{parentId}/{langId}', [CmsController::class,'cmsedit'])->name('cms.editCms');
Route::post('cms/storeLang', [CmsController::class,'storeLang'])->name('cms.storeLang');



///////////////////////////************** FAQ ********************////////////////////////////////


Route::get('/faq', [FaqController::class, 'index'])->name('faq');
Route::get('/faq/anyData',[FaqController::class,'anyData'])->name('faq.anyData');  
Route::get('/faq/create',[FaqController::class,'create'])->name('faq.create');  
Route::post('/faq/store',[FaqController::class,'Store'])->name('faq.store'); 
Route::get('/faq/edit/{id}',[FaqController::class,'edit'])->name('faq.edit');
Route::get('/faq/show/{id}',[FaqController::class,'show'])->name('faq.show');
Route::post('/faq/delete', [FaqController::class,'destroy'])->name('faq.delete');
Route::post('/faq/update',[FaqController::class,'update'])->name('faq.update');  
Route::POST('/faq/bulk-action', [FaqController::class,'BulkAction'])->name('faq.bulk.action'); 


///////////////////////////************** Customers Module ********************////////////////////////////////


Route::get('/customers', [CustomerController::class, 'index'])->name('customers');
Route::get('/customers/anyData',[CustomerController::class,'anyData'])->name('customers.anyData');  
Route::get('/customers/create',[CustomerController::class,'create'])->name('customers.create');  
Route::post('/customers/store',[CustomerController::class,'Store'])->name('customers.store'); 
Route::get('/customers/edit/{id}',[CustomerController::class,'edit'])->name('customers.edit');
Route::get('/customers/show/{id}',[CustomerController::class,'show'])->name('customers.show');
Route::post('/customers/delete', [CustomerController::class,'destroy'])->name('customers.delete');
Route::post('/customers/update',[CustomerController::class,'update'])->name('customers.update');  
Route::POST('/customers/bulk-action', [CustomerController::class,'BulkAction'])->name('customers.bulk.action'); 



