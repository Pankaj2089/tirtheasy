<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoriesController;
use App\Http\Controllers\Admin\AjaxController;
use App\Http\Controllers\Admin\BrandsController;
use App\Http\Controllers\Admin\InnerPagesController;
use App\Http\Controllers\Admin\ModelsController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ManyfacturerController;
use App\Http\Controllers\Admin\ModifiersController;
use App\Http\Controllers\Admin\DistributerController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\HotelsController;
use App\Http\Controllers\Admin\StatesController;
use App\Http\Controllers\Admin\CitiesController;
use App\Http\Controllers\Admin\AmenitiesController;
use App\Http\Controllers\Admin\FacilitiesController;
use App\Http\Controllers\Admin\AmenityCategoriesController;
use App\Http\Controllers\Admin\RoomsController;
use App\Http\Controllers\Admin\BannersController;
use App\Http\Controllers\Admin\PromotionsController;
use App\Http\Controllers\Admin\PopularDestinationsController;
use App\Http\Controllers\Admin\PremiumFacilitiesController;
use App\Http\Controllers\Admin\CouponCodesController;

Route::prefix('panel')->group(function(){
	
	#account setup
	Route::get('/',[AdminController::class, 'login'])->name('admin.login');
	Route::get('/login',[AdminController::class, 'login'])->name('admin.login');
	Route::post('/admin-login',[AdminController::class, 'admin_login'])->name('admin.admin_login');
	Route::get('/logout',[AdminController::class, 'logout'])->name('admin.logout');

	#settings
    Route::get('/settings',[ProfileController::class, 'settings'])->name('admin.settings');
	Route::post('/save-setting',[ProfileController::class, 'saveSetting'])->name('admin.save-setting');
	
	#dashboard setup
	Route::get('/dashboard',[AdminController::class, 'dashboard'])->name('admin.dashboard');
	Route::get('/profile',[ProfileController::class, 'profile'])->name('admin.profile');
	Route::post('/save-profile',[ProfileController::class, 'saveProfile'])->name('admin.saveProfile');
	
	#states
    Route::get('/states',[StatesController::class, 'getList'])->name('admin.states');
    Route::any('/states_paginate',[StatesController::class, 'listPaginate'])->name('admin.states_paginate');
	Route::any('/get-state',[StatesController::class, 'getPage'])->name('admin.get-state');
	Route::any('/add-state',[StatesController::class, 'addPage'])->name('admin.add-state');

	#ciites
    Route::get('/cities',[CitiesController::class, 'getList'])->name('admin.cities');
    Route::any('/cities_paginate',[CitiesController::class, 'listPaginate'])->name('admin.cities_paginate');
	Route::any('/get-city',[CitiesController::class, 'getPage'])->name('admin.get-city');
	Route::any('/add-city',[CitiesController::class, 'addPage'])->name('admin.add-city');

	#categories
    Route::get('/categories',[CategoriesController::class, 'getList'])->name('admin.categories');
    Route::any('/categories_paginate',[CategoriesController::class, 'listPaginate'])->name('admin.categories_paginate');
	Route::any('/get-category',[CategoriesController::class, 'getPage'])->name('admin.get-category');
	Route::any('/add-category',[CategoriesController::class, 'addPage'])->name('admin.add-category');
	
	#ajax
	Route::post('/change-status',[AjaxController::class, 'changeStatus'])->name('admin.change-status');
	Route::post('/change-browse-top-status',[AjaxController::class, 'changeBrowseTopStatus'])->name('admin.change-browse-top-status');
    Route::post('/delete-record',[AjaxController::class, 'deleteRecord'])->name('admin.delete-record');
    Route::post('/get-cities',[AjaxController::class, 'getCities'])->name('admin.get-cities');
	
	#brands
    Route::get('/brands',[BrandsController::class, 'getList'])->name('admin.brands');
    Route::any('/brands_paginate',[BrandsController::class, 'listPaginate'])->name('admin.brands_paginate');
	Route::any('/get-brand',[BrandsController::class, 'getPage'])->name('admin.get-brand');
	Route::any('/add-brand',[BrandsController::class, 'addPage'])->name('admin.add-brand');
	
	#innerpages
    Route::get('/inner-pages',[InnerPagesController::class, 'getList'])->name('admin.inner-pages');
    Route::any('/inner-pages_paginate',[InnerPagesController::class, 'listPaginate'])->name('admin.inner-pages_paginate');
	Route::any('/edit-inner-page/{row_id}',[InnerPagesController::class, 'editPage'])->name('admin.edit-inner-page');
	
	#models
    Route::get('/models',[ModelsController::class, 'getList'])->name('admin.models');
    Route::any('/models_paginate',[ModelsController::class, 'listPaginate'])->name('admin.models_paginate');
	Route::any('/get-model',[ModelsController::class, 'getPage'])->name('admin.get-model');
	Route::any('/add-model',[ModelsController::class, 'addPage'])->name('admin.add-model');
	
	#manufacturers
    Route::get('/manufacturers',[ManyfacturerController::class, 'getList'])->name('admin.manufacturers');
    Route::any('/manufacturers_paginate',[ManyfacturerController::class, 'listPaginate'])->name('admin.manufacturers_paginate');
	Route::any('/get-manufacturer',[ManyfacturerController::class, 'getPage'])->name('admin.get-manufacturer');
	Route::any('/add-manufacturer',[ManyfacturerController::class, 'addPage'])->name('admin.add-manufacturer');
	
	#modifiers
    Route::get('/modifiers',[ModifiersController::class, 'getList'])->name('admin.modifiers');
    Route::any('/modifiers_paginate',[ModifiersController::class, 'listPaginate'])->name('admin.modifiers_paginate');
	Route::any('/get-modifier',[ModifiersController::class, 'getPage'])->name('admin.get-modifier');
	Route::any('/add-modifier',[ModifiersController::class, 'addPage'])->name('admin.add-modifier');
	
	#modifiers
    Route::get('/distributers',[DistributerController::class, 'getList'])->name('admin.distributers');
    Route::any('/distributers_paginate',[DistributerController::class, 'listPaginate'])->name('admin.distributers_paginate');
	Route::any('/get-distributer',[DistributerController::class, 'getPage'])->name('admin.get-distributer');
	Route::any('/add-distributer',[DistributerController::class, 'addPage'])->name('admin.add-distributer');
	
	#users
    Route::get('/users',[UsersController::class, 'getList'])->name('admin.users');
    Route::any('/users_paginate',[UsersController::class, 'listPaginate'])->name('admin.users_paginate');
	Route::any('/get-user',[UsersController::class, 'getPage'])->name('admin.get-user');
	Route::any('/add-user',[UsersController::class, 'addPage'])->name('admin.add-user');
	
	#hotels
    Route::get('/hotels',[HotelsController::class, 'getList'])->name('admin.hotels');
    Route::any('/hotels_paginate',[HotelsController::class, 'listPaginate'])->name('admin.hotels_paginate');
	Route::any('/edit-hotel/{row_id}',[HotelsController::class, 'editPage'])->name('admin.edit-hotel');
	Route::any('/add-hotel',[HotelsController::class, 'addPage'])->name('admin.add-hotel');
	Route::any('/get-hotel-faqs',[HotelsController::class, 'getHotelFaqs'])->name('admin.get-hotel-faqs');
	Route::any('/add-hotel-faqs',[HotelsController::class, 'addHotelFaqs'])->name('admin.add-hotel-faqs');
	Route::any('/get-hotel-rules',[HotelsController::class, 'getHotelRules'])->name('admin.get-hotel-rules');
	Route::any('/add-hotel-rules',[HotelsController::class, 'addHotelRules'])->name('admin.add-hotel-rules');
	Route::any('/upload-product-images',[HotelsController::class, 'uploadProductImages'])->name('admin.upload-product-images');
	Route::any('/update-product-title',[HotelsController::class, 'updateProductTitle'])->name('admin.update-product-title');
	Route::any('/update-product-file',[HotelsController::class, 'updateProductFile'])->name('admin.update-product-file');
	Route::any('/get-landmarks-rules',[HotelsController::class, 'getLandmarksRules'])->name('admin.get-landmarks-rules');
	Route::any('/add-hotel-landmark',[HotelsController::class, 'addHotelLandmark'])->name('admin.add-hotel-landmark');
	
	#rooms
    Route::get('/rooms/{hotel_id}',[RoomsController::class, 'getList'])->name('admin.rooms');
    Route::any('/rooms_paginate/{hotel_id}',[RoomsController::class, 'listPaginate'])->name('admin.rooms_paginate');
	Route::any('/edit-room/{row_id}',[RoomsController::class, 'editPage'])->name('admin.edit-room');
	Route::any('/add-room/{hotel_id}',[RoomsController::class, 'addPage'])->name('admin.add-room');
	
	Route::any('/get-price-faqs',[RoomsController::class, 'getPrice'])->name('admin.get-room-price');
	Route::any('/add-price-faqs',[RoomsController::class, 'addPrice'])->name('admin.add-room-price');
	Route::any('/upload-room-images',[RoomsController::class, 'uploadProductImages'])->name('admin.upload-room-images');
	Route::any('/update-room-title',[RoomsController::class, 'updateProductTitle'])->name('admin.update-room-title');
	Route::any('/update-room-file',[RoomsController::class, 'updateProductFile'])->name('admin.update-room-file');
	
	#amenities
    Route::get('/amenity-categories',[AmenityCategoriesController::class, 'getList'])->name('admin.amenity-categories');
    Route::any('/amenity_categories_paginate',[AmenityCategoriesController::class, 'listPaginate'])->name('admin.amenity_categories_paginate');
	Route::any('/get-amenity-category',[AmenityCategoriesController::class, 'getPage'])->name('admin.get-amenity-category');
	Route::any('/add-amenity-category',[AmenityCategoriesController::class, 'addPage'])->name('admin.add-amenity-category');
	
	#amenities
    Route::get('/amenities',[AmenitiesController::class, 'getList'])->name('admin.amenities');
    Route::any('/amenities_paginate',[AmenitiesController::class, 'listPaginate'])->name('admin.amenities_paginate');
	Route::any('/get-amenity',[AmenitiesController::class, 'getPage'])->name('admin.get-amenity');
	Route::any('/add-amenity',[AmenitiesController::class, 'addPage'])->name('admin.add-amenity');
	
	#facilities
    Route::get('/facilities',[FacilitiesController::class, 'getList'])->name('admin.facilities');
    Route::any('/facilities_paginate',[FacilitiesController::class, 'listPaginate'])->name('admin.facilities_paginate');
	Route::any('/get-facility',[FacilitiesController::class, 'getPage'])->name('admin.get-facility');
	Route::any('/add-facility',[FacilitiesController::class, 'addPage'])->name('admin.add-facility');

	#banners
	Route::get('/banners',[BannersController::class, 'getList'])->name('admin.banners');
    Route::any('/banners_paginate',[BannersController::class, 'listPaginate'])->name('admin.banners_paginate');
	Route::any('/edit-banner/{row_id}',[BannersController::class, 'editPage'])->name('admin.edit-banner');
	Route::any('/add-banner',[BannersController::class, 'addPage'])->name('admin.add-banner');
	
	#promotions
	Route::get('/promotions',[PromotionsController::class, 'getList'])->name('admin.promotions');
    Route::any('/promotions_paginate',[PromotionsController::class, 'listPaginate'])->name('admin.promotions_paginate');
	Route::any('/edit-promotion/{row_id}',[PromotionsController::class, 'editPage'])->name('admin.edit-promotion');
	Route::any('/add-promotion',[PromotionsController::class, 'addPage'])->name('admin.add-promotion');
	
	#popular-destinations
	Route::get('/popular-destinations',[PopularDestinationsController::class, 'getList'])->name('admin.popular-destinations');
    Route::any('/popular_destinations_paginate',[PopularDestinationsController::class, 'listPaginate'])->name('admin.popular_destinations_paginate');
	Route::any('/edit-popular-destination/{row_id}',[PopularDestinationsController::class, 'editPage'])->name('admin.edit-popular-destination');
	Route::any('/add-popular-destination',[PopularDestinationsController::class, 'addPage'])->name('admin.add-popular-destination');
	
	#premium facilities
    Route::get('/premium-facilities',[PremiumFacilitiesController::class, 'getList'])->name('admin.premium-facilities');
    Route::any('/premium_facilities_paginate',[PremiumFacilitiesController::class, 'listPaginate'])->name('admin.premium_facilities_paginate');
	Route::any('/get-premium-facility',[PremiumFacilitiesController::class, 'getPage'])->name('admin.get-premium-facility');
	Route::any('/add-premium-facility',[PremiumFacilitiesController::class, 'addPage'])->name('admin.add-premium-facility');

	#couponcode
    Route::get('/coupon-codes',[CouponCodesController::class, 'getList'])->name('admin.coupon-codes');
    Route::any('/coupon_codes_paginate',[CouponCodesController::class, 'listPaginate'])->name('admin.coupon_codes_paginate');
    Route::any('/add-coupon-code',[CouponCodesController::class, 'addPage'])->name('admin.add-coupon-code');
    Route::any('/edit-coupon-code/{row_id}',[CouponCodesController::class, 'editPage'])->name('admin.edit-coupon-code');
	
});

