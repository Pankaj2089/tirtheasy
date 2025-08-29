@php
$siteUrl = env('APP_URL');
@endphp
@extends('layout.default')
@section('content')
<link href="{{$siteUrl}}public/css/new-style.css" rel="stylesheet">
    <link href="{{$siteUrl}}public/css/contact.css" rel="stylesheet">
<style type="text/css">

        .megasubmenu{ padding:1rem; }
        
        /* ============ desktop view ============ */
        @media all and (min-width: 992px) {
            .kk-navbar .nav-maga-main-kk .dropdown-toggle::after{
                background-color: transparent;
                border: 0px;
            }
            .megasubmenu{ 
                left:100%; top:0; min-height: 100%; min-width:760px;
            }
            
            .navbar-nav li.dropdown:hover .dropdown-menu{
                display: block;
            }
            .navbar-nav li.dropdown:hover .dropdown-menu .megasubmenu{
                display: none;
            }
                .navbar-nav li.dropdown:hover .dropdown-menu > li:hover .megasubmenu{
                display: block;
            }
            .megasubmenu-inner-kk .mega-nav-item {
                margin: 0px 0px 4.5px 0px !important;
            }
            .mega-nav-link {
                font-family: 'Ubuntu';
                font-style: normal;
                font-weight: 400;
                font-size: 12px !important;
                line-height: 14px !important;
                color: #393938 !important;
                opacity: 0.6;
                padding: 0px 0px !important;
                text-transform: capitalize !important;
            }
            
            .nav-maga-main-kk .has-megasubmenu a {
                padding: 6px 13px !important;
            }
            .nav-maga-main-kk .has-megasubmenu .megasubmenu .megasubmenu-inner-kk li.mega-nav-item a {
                padding: 6px 0 !important;
                }
            .nav-maga-main-kk .dropdown-item {
                font-family: 'Ubuntu';
                font-style: normal;
                font-weight: 400;
                font-size: 14px !important;
                line-height: 16px !important;
                color: #393938;
                text-transform: capitalize;
            }
            .nav-maga-main-kk .dropdown-item:focus, .dropdown-item:hover {
                color: #1e2125;
                background-color: transparent;
            }
            .nav-maga-main-kk .dropdown-menu {
                z-index: 1000;
                display: none;
                padding: unset !important;
                margin: 0;
                font-size: 1rem;
                color: #212529;
                text-align: left;
                list-style: none;
                background: #FCFBFF;
                border: none !important;
                border-radius: 0px 0px 0px 0px;
                width: 200px;
               

            }
            .nav-maga-main-kk .megasubmenu.dropdown-menu {
                background-color: #fff !important;
                border-radius: 0px 0px 0px 0px !important;
                padding-left: 50px !important;
                box-shadow: 0px 12px 20px rgba(0, 0, 0, 0.16);
            }
            .nav-maga-main-kk .dropdown-menu .has-megasubmenu {
                margin-right: 0px !important;
            }
           
        }	
            @media(max-width: 767.91px){
                .nav-maga-main-kk .dropdown-menu {
                min-width: 94%;
                padding: .3rem 0;
                font-size: 1rem;
                color: #212529;
                border-radius: .25rem;
                margin: 0px 10px !important;
                width: auto;
                overflow: hidden;
                max-height: 300px;
                overflow: scroll;
            }
            .nav-maga-main-kk .megasubmenu.dropdown-menu .row {
                flex-direction: column;
                display: flex;
            }
            .nav-maga-main-kk .megasubmenu.dropdown-menu .row .col-4 {
                width: 100%;
            }
            .nav-maga-main-kk .hemburger-content .burder-menu-content ul li a, .main-nav-mob ul li a {
                padding: 6px 0px;
            }
    }
        /* ============ desktop view .end// ============ */
        
        </style>
 <section class="banner-section contact-us-banner">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="kk-banner-contant align-items-center">
                        <h1 class="buy-title text-align-center pb-0">Contact US</h1>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="help-section py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <h2 class="help-titles">How can we help you?</h2>
                </div>
                <div class="col-md-4 ">
                    <span class="d-flex align-items-center chat-cls">
                        <span><img src="{{$siteUrl}}public/images/message-square.svg" alt=""></span>
                        <span class="chat-title">Chat</span>
                    </span>
                    <p class="chat-des pt-3">Chat is currently unavailable</p>
                </div>
                <div class="col-md-4">
                    <span class="d-flex mb-4 align-items-center chat-cls">
                        <span><img src="{{$siteUrl}}public/images/mail.svg" alt=""></span>
                        <span class="chat-title">Feedback</span>
                    </span>
                    <a href="#" class="btn leave-btn">Leave Feedback</a>
                </div>
            </div>
        </div> 

    </section>
    <section class="question-section py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <h2>Have a question? Let's connect</h2>
                </div>
            </div>
            <div class="row pt-4">
                <div class="col-lg-8">
                    <div class="inner-form">
                        <h3>Get in touch</h3>
                        <form class="row g-3 mt-3">
                            <div class="col-md-6">
                              <label for="inputEmail4" class="form-label">Name</label>
                              <input type="text" class="form-control" id="inputEmail4" placeholder="User Name">
                            </div>
                            <div class="col-md-6">
                              <label for="inputPassword4" class="form-label">Email</label>
                              <input type="email" class="form-control" id="inputPassword4" placeholder="Enter email">
                            </div>
                            <div class="col-md-6">
                              <label for="inputAddress" class="form-label">Phone No</label>
                              <input type="text" class="form-control" id="inputAddress" placeholder="Enter Phone no">
                            </div>
                            <div class="col-md-6">
                              <label for="inputAddress2" class="form-label">Subject</label>
                              <input type="text" class="form-control" id="inputAddress2" placeholder="Enter Subject">
                            </div>
                            <div class="col-lg-12">
                              <div class="form-area">
                                <label for="exampleFormControlTextarea1" class="form-label">Your Massage</label>
                                <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" style="height: 150px;"></textarea>
                              </div>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn mt-2">Send message</button>
                            </div>
                            
                          </form>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="inner-form2">
                        <ul class="p-0 national-list-menu">
                            <li>
                                <span class="national-title">Contact our National Advertising team</span>
                                <span class="d-flex national-list-inline align-items-start pt-2">
                                    <span class="pe-2"><img src="{{$siteUrl}}public/images/mail-white.svg" alt=""></span>
                                    <div>
                                        <span class="mail-titles ">support@karkiosk.com</span>
                                    </div>
                                </span>
                            </li>
                            <li>
                                <span class="national-title">Contact our Public Relations team</span>
                                <span class="d-flex national-list-inline align-items-center pt-2">
                                   
                                    <div>
                                        <span class="mail-titles d-flex "> <span class="pe-2"><img src="{{$siteUrl}}public/images/mail-white.svg" alt=""></span> support@karkiosk.com</span>
                                        <span class="mail-titles d-block pt-2"> <span class="pe-2 "><img src="{{$siteUrl}}public/images/phone-icon.svg" alt=""></span>312-508-6727</span>
                                    </div>
                                </span>
                            </li>
                            
                            
                        </ul>
                    </div>
                </div>

            </div>
        </div> 
    </section>

    <section class="map-section">
        <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d2348.1176055548117!2d75.3629608071024!3d28.14036106221967!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2sin!4v1675259140128!5m2!1sen!2sin" width="100%;" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </section>
  
    <section class="better-section py-5">
        <div class="container">
            <div class="row align-items-center pt-3">
                <div class="col-lg-12 better-box">
                    <h2 class="text-left">Better together with innovative solutions</h2>
                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took.</p>
                </div>
            </div>
            <div class="row pt-4">
                <div class="col-md-2">
                    <span class="logo2"><img src="{{$siteUrl}}public/images/Logo.png" alt=""></span>
                </div>
                <div class="col-md-4">
                    <span class="dealer-title">Dealer Inspire</span>
                    <p class="dealer-sub">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took.</p>
                    <a href="#" class="karkiosk-link">karkiosk.com</a>
                </div>
                <div class="col-md-4">
                    <span class="dealer-title">DealerRater</span>
                    <p class="dealer-sub">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took.</p>
                    <a href="#" class="karkiosk-link">karkiosk.com</a>
                </div>
            </div>
        </div>
    </section>
@endsection