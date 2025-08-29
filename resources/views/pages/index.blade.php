@php
$siteUrl = env('APP_URL');
@endphp
@extends('layout.default')
@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Tirtheasy</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <style>
    body, html {
      font-family: "Inter", sans-serif;;
      font-weight: 300;
    }
    .header-section {
      position: relative;
      background: url('{{$siteUrl}}public/img/bg-1.jpg') no-repeat center center/cover;
      height: 800px;
      color: white;;
      margin-top:-65px;
      
    }

    .header-overlay {
      position: absolute;
      top: 0;
      bottom: 0;
      left: 0;
      right: 0;
      background-color: rgba(0, 0, 0, 0.5); /* dark overlay */
      display: flex;
      align-items: center;
      justify-content: center;
    }
    h1{
      font-size:50px;
      font-weight:700;
    }

    .footer {
      background-color: #eaeaee;
      padding: 20px 0;
    }
    .header-text{
      font-size:30px;
      font-weight:500;
      margin:20px 0 50px;
    }
    .singup-early{
      font-size:22px;
      font-weight:500;
      border:1px solid #000;
      padding: 5px 10px;
      background-color: rgba(255, 255, 255, 0.5);
      border-radius:10px;
      max-width: 520px;
      margin: 55px auto 0 !important;
      color:#000;
      display:block;
    }
    .singup-early-small{
      max-width: 340px;
    }
    .sign-up-btn{
      font-size:22px;
      font-weight:700;
      border:1px solid #000;
      padding: 5px 10px;
      background-color: #cd4849;
      border-radius:10px;
      max-width: 150px;
      margin: 30px auto 0 !important;
      color:#FFF;
      text-decoration: none;
      display:block;
    }
    .sign-up-btn:hover{
      color:#FFF
    }
    .blog-section {
      padding: 40px 0;
      text-align:center;
    }
    .card-body{
      background-color: #f9f9f9;
    }
    .card-title{
      font-size:22px;
      font-weight:700;
      color: #cd4849;
    }
    .card-text{
      font-size:18px;
    }
    .color-text{
      font-size:42px;
      font-weight:400;
      margin:50px 0 40px;
      text-align:left;
    }
    .color-text span{
      color: #cd4849;
    }
    .religious-travel-section .heading{
      font-size:30px;
      font-weight:400;
      margin:20px 0 10px;
      color: #cd4849;
    }
    .religious-travel-section ul{
      padding: 0 20px;
    }
    .religious-travel-section ul li{
      font-size:22px;
      margin:10px 0 20px;
    }
  .religious-travel-section ul li::marker {
    color: #cd4849;
  }
  .font-bold{
      font-weight:700;
      font-size:55px;
  }
  .footer p{
      font-size:16px;
  }
  .footer-links li{
    text-decoration: none;
    display:inline-block;
    padding:0px 10px;
    margin-top:45px;
  }
  .footer-links li a{
    color:#000;
    text-decoration: none;
  }
  .header-text{
    display:block;
    width:70%;
    margin:50px auto;
  }
  .header-menu{
    display:block;
    width:100%
  }
  .navbar{
    display:block;
    position: relative;
    z-index: 9;
    width: 95%;
    margin:0px auto;
    padding-top: 20px;
  }
  .navbar a{
    font-weight:500;
    font-size:25px;
  }
  .sign-up-btn-1{
    font-size:20px !important;
    font-weight:700;
    padding: 5px 15px;
    background-color: #cd4849;
    border-radius:5;
    max-width: 150px;
    text-decoration: none;
    float:right;
    color:#FFF;
    border-radius:4px;
  }
  
  .sign-up-btn-2{
    font-size:14px !important;
    font-weight:400;
    padding: 5px 15px;
    background-color: #cd4849;
    border-radius:5;
    max-width: 200px;
    text-decoration: none;
    float:right;
    color:#FFF;
    border-radius:4px;
    text-transform:uppercase;
  }
  .sign-up-btn-1:hover{
      color:#000
    }
    .logo-img{
      max-width:450px;
    }
  .card-body img{
    width: 70px;
    margin-bottom:20px
  }
  .container-xxl{
    max-width: 90% !important;
  }
  .card{
    width: auto;
  }
  .icon{
    height:80px
  }
  .rupee-icon img{
    width:50px;
  }
  .card{
    max-width:80% !important
  }
   .social-icon img{
    height: 40px;
  }
  .footer-logo{
      width: 250px;
    }

@media only screen and (max-width: 600px) {
  .logo-img{
      max-width:90%;
    }
   .card{
      max-width:100% !important
    }
  
    h1{
      font-size:35px;
      margin-top:10px !important
    }
  .font-bold{
      font-size:40px;
  }
  .header-text{
      font-size:20px;
      width:98%;
      margin:20px auto;
    }
    
    .singup-early{
      font-size:20px;
      max-width: 90%;
    }
    .header-section {
      margin-top:-70px;
      height: 100vh;
    }
    .footer{
      text-align:center;
    }
    .color-text{
      text-align:center;
    }
    .navbar{
      padding-top: 12px;
    }
    .navbar a{
      font-size:16px;
    }
  .sign-up-btn-1{
    font-size:16px !important;
    padding: 2px 10px;
  }
  .sign-up-btn{
      font-size:20px;
    }
    .color-text {
    font-size: 40px;
}
    .religious-travel-section .heading{
      font-size:25px;
    }
    .religious-travel-section ul li{
      font-size:18px;
    }
    .religious-travel-section .mb-4, .religious-travel-section .pb-4, .religious-travel-section .mt-4{
      margin:0px !important;
      padding:0px !important;
    }
   .religious-travel-section h2{
      margin-bottom:0px !important;
      padding-bottom:0px !important;
      margin-top:20px !important;
    }
    .religious-travel-section br{
      display:none !important;
    }
    .footer p{
      font-size:14px;
    }
    .footer-links li a{
      font-size:14px;
    }

    .footer .mb-4, .footer .pb-4, .footer .mt-4{
      margin-top:10px !important;
      padding-top:10px !important;
    }
    .footer-logo{
      width: 200px;
    }
    .h5, h5 {
      font-size: 18px
    }
    .social-icon img {
        height: 30px;
    }
}
  </style>
</head>
<body>

  <!-- Header with overlay -->
   <nav class="navbar navbar-expand-lg navbar-dark bg-none">
      <a class="navbar-brand" href="{{url('/about-us')}}">About us</a>
      @if(Session::get('username') == '' )
      <a href="{{url('/sign-up')}}" class="sign-up-btn-1">Sign up</a>
      @else
      <a class="sign-up-btn-2">{{Session::get('username')}}</a>
      @endif
  </nav>
  <header class="header-section">
    <div class="header-overlay text-center">
      <div class="header-text">
        <h1>Coming Soon...</h1>
        <img class="logo-img" src="{{$siteUrl}}public/img/logo.png"/>
        <p class="header-text">Introducing India's first Religious travel platform 
Dedicated to Booking verified Dharmshala, Guest house, and Travel Tickets with 
Real time availability, and effortless booking, all in one place. 
      @if(Session::get('username') == '' )
      <div class="singup-early ">Sign up and Book Your First Dharmshala Free*</div>
      <a href="{{url('/sign-up')}}" class="sign-up-btn mt-4">Sign up</a>
      @else
        <div class="singup-early singup-early-small">Welcome {{Session::get('username')}}</div>
      @endif
      </div>
    </div>
  </header>

  <!-- Main content -->
  <main class="my-5">
    <!-- Blog Section -->
  <section class="blog-section">
    <div class="container-xxl">
      <div class="row">
        <!-- Blog 1 -->
        <div class="col-md-6 col-lg-3 mb-4">
          <div class="card h-100">
            <div class="card-body">
              <div class="icon"><img src="{{$siteUrl}}public/img/clock.png"/></div>
              <h5 class="card-title">Real-Time <br/> Availability</h5>
              <p class="card-text">No more<br/>
Guesswork Book<br/>
Verified rooms on<br/>
real time basis</p>
            </div>
          </div>
        </div>
        <!-- Blog 2 -->
        <div class="col-md-6 col-lg-3 mb-4">
          <div class="card h-100">
            <div class="card-body">
              <div class="icon"><img src="{{$siteUrl}}public/img/verified.png"/></div>
              <h5 class="card-title">Trusted & Verified<br/>  Listings</h5>
              <p class="card-text">Every dharmshala is<br/>
approved by us and<br/>
temple trust</p>
            </div>
          </div>
        </div>
        <!-- Blog 3 -->
        <div class="col-md-6 col-lg-3 mb-4">
          <div class="card h-100">
            <div class="card-body">
             <div class="icon rupee-icon"> <img src="{{$siteUrl}}public/img/rupee-indian.png"/></div>
              <h5 class="card-title">Affordable &<br/>  Transparent</h5>
              <p class="card-text">No Hidden fees, fair<br/>
rates. what you see<br/>
is what you pay
 </p>
            </div>
          </div>
        </div>
        <!-- Blog 4 -->
        <div class="col-md-6 col-lg-3 mb-4">
          <div class="card h-100">
            <div class="card-body">
              <div class="icon"><img src="{{$siteUrl}}public/img/lightning.png"/></div>
              <h5 class="card-title">Book in few <br/> Clicks</h5>
              <p class="card-text">Booking a room is<br/>
now as easy as<br/>
messaging a friend
</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Section -->
  <section class="religious-travel-section">
    <div class="container-xxl">
      <div class="row mb-2">
        <h2 class="color-text"><span>Religious travel</span> made easy</h2>
      </div>
      <div class="row">
        <!-- select 1 -->
        <div class="col-md-4 mb-4">
          <div class="heading">For Pilgrims</div>
          <ul>
            <li>Discover Verified <br />stays near Temples</li>
            <li>Book rooms with <br />real-time availability</li>
            <li>Get confirmation<br /> in seconds</li>
            <li>All on Web and App<br /> (coming soon)</li>
          </ul>
        </div>

        <!-- select 2 -->
        <div class="col-md-6 mb-4">
          <div class="heading">For Dharmshalas & Mandirs</div>
          <ul>
            <li>Easy-to-use Property <br />Management System</li>
            <li>Get listed on Tirtheasy <br />for free</li>
            <li>Manage Booking, <br />Visibility & Pricing</li>
            <li>Sync Availability in <br />real time</li>
          </ul>
        </div>
      </div>
    </div>
  </section>
<!-- Section -->
  <section class="religious-travel-section">
    <div class="container-xxl">
      <div class="row mb-4 pb-4">
          <div class="col-md-8 mb-4 mt-4">
        <h2 class="color-text font-bold"><span>Join</span> the waitlist by
Signing up and get 
early user offers and 
<span>book</span> your <span>Sacred</span> 
<span>journey</span> with us <br />&nbsp; </h2>
      </div>
      </div>
    </div>
  </section>
  </main>
  <!-- Footer -->
  <footer class="footer mt-4  ">
    <div class="container-xxl mt-4 ">
      <section>
        <div class="row">
          <!-- Blog 1 -->
          <div class="col-md-6 mb-4 mt-4">
            <img src="{{$siteUrl}}public/img/f-logo (2).png" class="footer-logo"  alt="TirthEasy" />
            <p class="mt-4">
              Copyright &copy; 2025 Dharmshala Ventures Pvt. Ltd. All Rights Reserved.<br />
  The Tirtheasy word mark is a registered trademark of Dharmshala Ventures Pvt. Ltd.

            </p>
          </div>
          <!-- Blog 2 -->
          <div class="col-md-3 mb-4 mt-4  pt-4 text-center">
              <h5 >CONTACT US</h5>
              <p class="card-text">support@tirtheasy.com</p>
          </div>
          <!-- Blog 3 -->
          <div class="col-md-3 mb-4 mt-4  pt-4 text-center">
              <h5>SOCIAL LINKS</h5>
              <div class="social-icon">
                <a href="https://www.linkedin.com/company/dharmshala/" target="_blank"><img src="{{$siteUrl}}public/img/linkedin.png"/></a>
                <a href="https://www.instagram.com/tirtheasy?igsh=bTYzd3RudjJwb2hr" target="_blank"><img src="{{$siteUrl}}public/img/instagram.png"/></a>
                <a href="https://x.com/Tirtheasy?s=08" target="_blank"><img src="{{$siteUrl}}public/img/twitter.png"/></a>
                <a href="https://youtube.com/@tirtheasy?si=klXyQkRLuCOEO4OD" target="_blank"><img src="{{$siteUrl}}public/img/youtube.png"/></a>
                <a href="https://www.facebook.com/share/1CJkWk9nrB/" target="_blank"><img src="{{$siteUrl}}public/img/facebook.png"/></a>
            </div>
            <ul class="footer-links p-0">
              <li><a href="#">PRIVACY</a></li>
              <li><a href="#">SECURITY</a></li>
              <li><a href="#">TERMS</a></li>
          </ul>

          </div>
        </div>
    </section>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<script src="{{ asset('public/js/toastify.js') }}" ></script>
<script type="text/javascript">
var addUrl = "{{route('userRegister')}}";
var returnURL = "/";
$(document).ready(function () {
@if(session('success'))
  showMessage();
@endif
});
function showMessage(){
  Toastify({
        text: "{{ session('success') }}",
        duration: 3000,
        gravity: "top", // `top` or `bottom`
        position: "center", // `left`, `center` or `right`
        backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)",
        stopOnFocus: true
    }).showToast();
}
</script> 
@endsection