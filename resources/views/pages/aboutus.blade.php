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
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
 
  <style>
    body, html {
      font-family: "Inter", sans-serif;;
      font-weight: 300;
    }
    .footer {
      background-color: #eaeaee;
      padding: 20px 0;
    }
    .footer p{
      font-size:18px;
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
  .content-section .heading{
    font-size:24px !important;
    font-weight:600;
    color: #cd4849;
  }
  .content-section p{
    font-size:22px !important;
    font-weight:400;
    text-align: justify;
  }
  .container-xxl{
    max-width: 90% !important;
  }
  .social-icon img{
    height: 40px;
  }
  .footer-logo{
      width: 250px;
    }
  .logo img{
     width: 300px;
  }
  h1{
    font-family: "Poppins", sans-serif;
    font-weight: 700;
  }
  
@media only screen and (max-width: 600px) {
    .footer{
      text-align:center;
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
    .content-section p {
      font-size: 14px !important;
      font-weight: 400;
      text-align: justify;
  }
  .content-section .heading{
    font-size: 16px !important;
    text-align:center;

  }
  .logo img{
     width: 150px;
     margin-left:-15px !important
  }
  .mobile-text{
    margin-top:18px !important;
    font-weight: 700;
  }
  h1{
    text-align:center;
  }
}
  </style>
</head>
<body>

  <!-- Header with overlay -->
 
  

  <!-- Main content -->
  <main class="container-xxl my-5 mobile-text">
    <header class="header-section mb-4">
    <div class="header-overlay">
      <div class="header-text logo mb-4">
        <a href="{{url('/')}}"><img src="{{$siteUrl}}public/img/a-logo.png"/></a>
      </div>
    </div>
  </header>
    <!-- Blog Section -->
  <section >
      <div class="row content-section">

        <!-- Blog 1 -->
        <div class="col-md-12 mb-4 mt-4">
          <h1>About Us</h1>
          <div class="heading mt-4 mb-4">Your sacred journey, seamlessly booked.</div>
        <p>At TirthEasy, we’re on a heartfelt mission to simplify and elevate the way India travels for faith. Born 
        from a deep personal connection to our spiritual roots, TirthEasy was created to serve the millions of
        Hindu pilgrims who seek comfort, clarity, and convenience on their sacred journeys. <br /> <br />
        We are India’s first tech-enabled religious travel company offering:
        Real-time booking of dharmshala stays and pilgrimage accommodations
        A powerful Property Management System (PMS) for temples and trust-run institutions
        A seamless booking experience with minimum clicks, affordable rates, and exclusive inventory No
        more confusing websites, unverified listings, or unconfirmed stays. TirthEasy ensures 100% verified
        properties, secure payments, and robust fraud prevention systems so your focus stays on devotion,
        not doubt.<br /><br />
        We’re not just building a platform—we’re creating a bridge between tradition and technology. Soon,
        our vision includes AI-powered yatra planners that understand your rituals, routes, and
        requirements, offering tailor-made travel suggestions for every pilgrim.
        TirthEasy is more than a company. It’s a tribute to tradition, made easy by innovation.</p>
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
@endsection