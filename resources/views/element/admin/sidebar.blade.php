@php
  $action =  Route::getCurrentRoute()->getName();
@endphp

 <aside id="layout-menu" class="layout-menu menu-vertical menu">
  <div class="app-brand demo">
    <a href="#" class="app-brand-link">
      <span class="app-brand-text demo menu-text fw-bold ms-3">Tirtheasy</span>
    </a>
    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
      <i class="icon-base ti menu-toggle-icon d-none d-xl-block"></i>
      <i class="icon-base ti tabler-x d-block d-xl-none"></i>
    </a>
  </div>
  <div class="menu-inner-shadow"></div>
  <ul class="menu-inner py-1">
    <!-- Dashboards -->
    <li class="menu-item {{$action =='admin.dashboard' ?'active':''}}">
      <a href="{{route('admin.dashboard')}}" class="menu-link">
        <i class="menu-icon icon-base ti tabler-smart-home"></i>
        <div data-i18n="Dashboards">Dashboards</div>
      </a>
    </li>
        @php
          $usersActive =
          false;         
          if($action =='admin.users'){
            $usersActive = true;
          }   
          @endphp
        <li class="menu-item  {{ $usersActive ? 'active open':'' }} ">
          <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon icon-base ti tabler-users"></i>
            <div data-i18n="Users">Users</div>
          </a>
          <ul class="menu-sub">
            <li class="menu-item {{ $usersActive ? 'active':'' }}">
              <a href="{{route('admin.users')}}" class="menu-link">
                <div data-i18n="Customers">Customers</div>
              </a>
            </li>
          </ul>
        </li>
        @php
        $generalManagerActive = 
        $statesActive = $citiesActive = $innerPagesActive = $bannerActive = $promotionActive = $popularDestinationActive = $premiumFacilitiesActive =
        $blogsActive = $premiumFaqsActive = false;         
        if($action =='admin.states'){
          $generalManagerActive = $statesActive = true;
        }  
        if($action =='admin.cities'){
          $generalManagerActive = $citiesActive = true;
        }    
        if($action =='admin.inner-pages' || $action =='admin.edit-inner-page'){
          $generalManagerActive = $innerPagesActive = true;
        } 
        if($action =='admin.banners' || $action =='admin.add-banner' || $action =='admin.edit-banner'){
          $generalManagerActive = $bannerActive = true;
        } 
        if($action =='admin.promotions' || $action =='admin.add-promotion' || $action =='admin.edit-promotion'){
          $generalManagerActive = $promotionActive = true;
        }
        if($action =='admin.popular-destinations' || $action =='admin.add-popular-destination' || $action =='admin.edit-popular-destination'){
          $generalManagerActive = $popularDestinationActive = true;
        } 
        if($action =='admin.premium-facilities'){
          $generalManagerActive = $premiumFacilitiesActive = true;
        } 
        if($action =='admin.faqs'){
          $generalManagerActive = $premiumFaqsActive = true;
        }
        if($action =='admin.blogs' || $action =='admin.add-blog' || $action =='admin.edit-blog'){
          $generalManagerActive = $blogsActive = true;
        } 
        @endphp
        <li class="menu-item  {{ $generalManagerActive ? 'active open':'' }}">
          <a href="javascript:void(0);" class="menu-link menu-toggle"> 
            <i class="menu-icon icon-base ti tabler-layout-grid"></i>
            <div data-i18n="General">General</div>
          </a>
          <ul class="menu-sub">
            <li class="menu-item  {{ $statesActive ? 'active':'' }}">
              <a href="{{route('admin.states')}}" class="menu-link">
                <div data-i18n="States">States</div>
              </a>
            </li>
            <li class="menu-item  {{ $citiesActive ? 'active':'' }}">
              <a href="{{route('admin.cities')}}" class="menu-link">
                <div data-i18n="Cities">Cities</div>
              </a>
            </li>
            <li class="menu-item d-none">
              <a href="{{route('admin.brands')}}" class="menu-link">
                <div data-i18n="Brands">Brands</div>
              </a>
            </li>
            <li class="menu-item  {{ $innerPagesActive ? 'active':'' }}" >
              <a href="{{route('admin.inner-pages')}}" class="menu-link">
                <div data-i18n="Inner Pages">Inner Pages</div>
              </a>
            </li>
            <li class="menu-item  {{ $bannerActive ? 'active':'' }}" >
              <a href="{{route('admin.banners')}}" class="menu-link">
                <div data-i18n="Banners">Banners</div>
              </a>
            </li>
            <li class="menu-item  {{ $promotionActive ? 'active':'' }}" >
              <a href="{{route('admin.promotions')}}" class="menu-link">
                <div data-i18n="Promotions">Promotions</div>
              </a>
            </li>
            <li class="menu-item  {{ $popularDestinationActive ? 'active':'' }}" >
              <a href="{{route('admin.popular-destinations')}}" class="menu-link">
                <div data-i18n="Popular Destinations">Popular Destinations</div>
              </a>
            </li>
            <li class="menu-item  {{ $premiumFacilitiesActive ? 'active':'' }}" >
              <a href="{{route('admin.premium-facilities')}}" class="menu-link">
                <div data-i18n="Premium Facilities">Premium Facilities</div>
              </a>
            </li>
            <li class="menu-item  {{ $premiumFaqsActive ? 'active':'' }}" >
              <a href="{{route('admin.faqs')}}" class="menu-link">
                <div data-i18n="Faqs">Faqs</div>
              </a>
            </li>
            <li class="menu-item  {{ $blogsActive ? 'active':'' }}" >
              <a href="{{route('admin.blogs')}}" class="menu-link">
                <div data-i18n="Blogs">Blogs</div>
              </a>
            </li>
          </ul>
        </li>
        @php
        $hotelManagerActive = 
        $hotelsActive =
        $amenitiesActive =
        $facilitiesActive =
        $amenityCategoriesActive =
        false;
        if($action =='admin.hotels' || $action =='admin.add-hotel' || $action =='admin.edit-hotel' || $action =='admin.rooms' || $action =='admin.add-room' || $action =='admin.edit-room'){
          $hotelManagerActive = $hotelsActive = true;
        }   
        if($action =='admin.amenities'){
          $hotelManagerActive = $amenitiesActive = true;
        } 
        if($action =='admin.facilities'){
          $hotelManagerActive = $facilitiesActive = true;
        } 
        if($action =='admin.amenity-categories' ){
          $hotelManagerActive = $amenityCategoriesActive = true;
        } 
        @endphp
        <li class="menu-item  {{ $hotelManagerActive ? 'active open':'' }}">
          <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon icon-base ti tabler-smart-home"></i>
            <div data-i18n="Hotels">Hotels</div>
          </a>
          <ul class="menu-sub">
            <li class="menu-item  {{ $amenityCategoriesActive ? 'active':'' }}">
              <a href="{{route('admin.amenity-categories')}}" class="menu-link">
                <div data-i18n="Amenity Categories">Amenity Categories</div>
              </a>
            </li>
            <li class="menu-item  {{ $amenitiesActive ? 'active':'' }}">
              <a href="{{route('admin.amenities')}}" class="menu-link">
                <div data-i18n="Amenities">Amenities</div>
              </a>
            </li>
            <li class="menu-item  {{ $facilitiesActive ? 'active':'' }}">
              <a href="{{route('admin.facilities')}}" class="menu-link">
                <div data-i18n="Facilities">Facilities</div>
              </a>
            </li>            
            <li class="menu-item  {{ $hotelsActive ? 'active':'' }}">
              <a href="{{route('admin.hotels')}}" class="menu-link">
                <div data-i18n="Hotels">Hotels</div>
              </a>
            </li>
            <!-- <li class="menu-item  {{ $hotelsActive ? 'active':'' }}">
              <a href="{{route('admin.coupon-codes')}}" class="menu-link">
                <div data-i18n="Hotels">Coupon Codes</div>
              </a>
            </li> -->
          </ul>
        </li>

         @php                        
         $managerActive = $coupon_codes =  $orders = false;
         if($action =='admin.coupon-codes' ||  $action =='admin.add-coupon-code' ||  $action =='admin.edit-coupon-code'){
         	$managerActive = $coupon_codes = true;
         }
         if( $action =='admin.orders'){
         	$managerActive = $orders = true;
         }
         @endphp
         <li class="menu-item  {{ $managerActive ? 'active open':'' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle"> 
              <i class="menu-icon icon-bar ti tabler-chart-histogram"></i>
              <div data-i18n="Orders">Orders</div>
            </a>
            <ul class="menu-sub">
              <li class="menu-item  {{ $coupon_codes ? 'active':'' }}">
                <a href="{{route('admin.coupon-codes')}}" class="menu-link">Coupon Codes</a>
              </li>
              <li class="menu-item  {{ $orders ? 'active':'' }}">
                <a href="{{route('admin.orders')}}" class="menu-link">Orders</a>
              </li>
            </ul>
        </li>

         @php                        
         $cmanagerActive = $contactUs =  $bookingEnquiries = $addDharmasala = false;
       
         if($action =='admin.contact-us'){
         	$cmanagerActive = $contactUs = true;
         }
         if($action =='admin.booking-enquiries' || $action =='view-booking-enquiries'){
         	$cmanagerActive = $contactUs = true;
         }
         if($action == 'add-dharmasala-enquiries' ){
         	$cmanagerActive = $addDharmasala = true;
         }
         @endphp
         <li class="menu-item  {{ $cmanagerActive ? 'active open':'' }}">
          <a href="javascript:void(0);" class="menu-link menu-toggle"> 
            <i class="menu-icon icon-bar ti tabler-message"></i>
            <div data-i18n="Contact Enquiries">Contact Enquiries</div>
          </a>
            <ul class="menu-sub">
              <li class="menu-item  {{ $contactUs ? 'active':'' }}">
                <a href="{{route('admin.contact-us')}}" class="menu-link">Contact Us</a>
              </li>
              <li class="menu-item  {{ $bookingEnquiries ? 'active':'' }}">
                <a href="{{route('admin.booking-enquiries')}}" class="menu-link">Bookings</a>
              </li>
              <li class="menu-item  {{ $addDharmasala ? 'active':'' }}">
                <a href="{{route('admin.add-dharmasala-enquiries')}}" class="menu-link">Add Dharmasala</a>
              </li>
            </ul>
        </li>
    </ul>
  </aside>