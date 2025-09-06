<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\APIController;

Route::prefix('api')->group(function(){
	Route::get('/sliders',[APIController::class, 'getBannersList'])->name('banners');
	Route::post('/promotions',[APIController::class, 'getPromotionsList'])->name('promotions');
	Route::post('/popular-destinations',[APIController::class, 'getPopularDestinationsList'])->name('popular-destinations');
	Route::post('/premium-facilities',[APIController::class, 'getPremiumFacilities'])->name('premium-facilities');
	Route::get('/hotels/{type}',[APIController::class, 'getHotelsList'])->name('hotels');
	Route::post('/save-newsletter',[APIController::class, 'saveNewsletter']);
	Route::get('/get-page-data/{id}',[APIController::class, 'getStaticPage']);
	Route::post('/get-property-list',[APIController::class, 'getPropertyList']);
	Route::post('/get-cities',[APIController::class, 'getCities']);
	Route::get('/get-property-data/{slug}',[APIController::class, 'getPropertyData']);
	Route::post('/get-state-cities',[APIController::class, 'getStateCities']);
	Route::post('/get-hotel-faqs',[APIController::class, 'getHotelFaqs']);
	Route::post('/recommended-hotels',[APIController::class, 'getRecommendedHotels']);
	Route::post('/get-hotel-rules',[APIController::class, 'getHotelRules']);
	Route::post('/get-hotel-facilities',[APIController::class, 'getHotelFacilities']);
	Route::post('/get-hotel-amenities',[APIController::class, 'getHotelAmenities']);
	Route::post('/get-hotel-details',[APIController::class, 'getHotelDetails']);
	Route::post('/get-hotel-landmarks',[APIController::class, 'getHotelLandmarks']);
	Route::post('/get-hotel-rooms',[APIController::class, 'getHotelRooms']);
	Route::post('/sign-in',[APIController::class, 'signIn']);
	Route::post('/verify-otp',[APIController::class, 'verifyOTP']);
	Route::post('/user-register',[APIController::class, 'userRegister']);
	Route::post('/get-room-price-details',[APIController::class, 'getRoomPriceDetails']);
	Route::get('/get-states',[APIController::class, 'getStates'])->name('get-states');
	Route::post('/get-cities-list',[APIController::class, 'getStateCitiesList'])->name('get-cities-list');

	Route::post('/create-order', [APIController::class, 'createOrder']);
	Route::post('/verify-payment', [APIController::class, 'verifyPayment']);
	Route::get('/get-offers', [APIController::class, 'getCouponCodes']);	
	Route::post('/delete-temp-order', [APIController::class, 'deleteTempOrder']);
});
