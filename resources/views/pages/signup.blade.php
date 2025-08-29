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
  <title>Register Page</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="{{ asset('public/css/toastify.css') }}" rel="stylesheet" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
  <style>
    body, html {
      height: 100%;
      margin: 0;
      background: url('{{$siteUrl}}public/img/bg-2.jpg') no-repeat center center fixed;
      background-size: cover;
      color: white;
      font-family: "Poppins", sans-serif;
      font-weight: 300;
      font-style: normal;
    }
    .overlay {
      background-color: rgba(0, 0, 0, 0.5); /* dark transparent overlay */
      height: 100%;
    }
    .form-container {
      background-color: #FFF;
      padding: 2rem;
      border-radius: 1rem;
      box-shadow: 0 0 15px rgba(0,0,0,0.3);
      font-family: "Poppins", sans-serif;
      color:#000;
    }
    .bg-white {
      background:#FFF;
      color:#000;
    }
    .bg-white label {
      background:#FFF;
      color:#000;
    }
    .text-upper{
        text-transform:uppercase;
        font-family: "Poppins", sans-serif;
    }
    .logo{
        color:#cc484a;
        font-size:80px;
        font-family: "Poppins", sans-serif;
        font-weight: 700;
    }
    
    .logo a{
        color:#cc484a;
        text-decoration:none;
    }
    .btn-danger{
        background-color: #cc484a;
    }
    .btn-default{
      border:1px solid #1e1c1c;
    }
    @media only screen and (max-width: 767px) {
      .logo{
        font-size:60px;
      }
      #pageForm{
        text-align: left;
      }
    }
  </style>
</head>
<body>
  
<!-- call helper method -->
@php
  $setting = settings();
  $left_section_heading = '';
  $left_section_content = '';
  $right_section_heading = '';
  $right_section_content = '';
  if(isset($setting->register_page_content) && !empty($setting->register_page_content)){
      $register_page_content = json_decode($setting->register_page_content);
      $left_section_heading = $register_page_content->left_section_heading;
      $left_section_content = $register_page_content->left_section_content;
      $right_section_heading = $register_page_content->right_section_heading;
      $right_section_content = $register_page_content->right_section_content;
  }
  @endphp

  <div class="overlay d-flex align-items-center">
    <div class="container">
      <div class="row text-center text-md-start">
        <!-- Left Text Block -->
        <div class="col-md-3 d-none">
            <div class="form-container w-100 h-100 text-center">
                <h3>{{$left_section_heading}}</h3>
                <p>{!! $left_section_content !!}</p>
            </div>
        </div>
        <!-- Center Form -->
        <div class="col-md-6 d-flex offset-lg-3">
          <div class="form-container bg-white w-100 ">
            <h3 class="text-center mb-2 text-upper">Coming Soon...</h3>
            <h2 class="text-center mb-4 logo"><a href="{{url('/')}}">Tirtheasy</a></h2>
            <h4 class="text-center mb-4">Sign Up Now For Early User Offer</h4>
            <form id="pageForm">
              <div class="mb-3">
                <label for="name" class="form-label ">Name</label>
                <input type="text" class="form-control" id="name" name="name">
              </div>
              <div class="mb-3">
                <label for="email" class="form-label ">Email Address</label>
                <input type="email" class="form-control" id="email" name="email">
              </div>
              <div class="mb-3">
                <label for="email" class="form-label ">Mobile Number</label>
                <input type="tel" maxlength="10" class="form-control numberonly" id="phone" name="phone">
              </div>
              <div class="mb-3">
                <label for="password" class="form-label ">Password</label>
                <input type="password" class="form-control" id="password" name="password">
              </div>
              <button type="button" class="btn btn-danger w-100 mb-3" id="submitBtn">Submit</button>
              <p class="text-center">or</p>
             <a href="{{ route('google.login') }}">
                <button type="button" class="btn btn-default w-100" >
                    <img src="{{$siteUrl}}public/img/google-icon.png" class="pr-4" width="20" /> Sign in with Google
                </button>
            </a>
            </form>
          </div>
        </div>
        <!-- Right Text Block -->
        <div class="col-md-3 d-none text-center">
            <div class="form-container w-100 h-100">
                <h3>{{$right_section_heading}}</h3>
                <p>{!! $right_section_content !!}</p>  
          </div>
        </div>
      </div>
    </div>
  </div>
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

$('.numberonly').keypress(function(e){
    var charCode = (e.which) ? e.which : event.keyCode
    if(String.fromCharCode(charCode).match(/[^0-9+]/g))
    return false;
});
	$('#submitBtn').click(function(e) {
		$('#submitBtn').html('Processing...');
		var form = $('#pageForm')[0];
		var formData = new FormData(form);
	   $.ajax({
			headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
			type: 'POST',
			data:formData,
			url: addUrl,
			processData: false,
			contentType: false,
			success: function(response){
				$('#submitBtn').html('Submit');
				var obj = JSON.parse(response);
                
				 if(obj['success']){
                    Toastify({
                        text: obj['msg'],
                        duration: 3000,
                        gravity: "top", // `top` or `bottom`
                        position: "center", // `left`, `center` or `right`
                        backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)",
                        stopOnFocus: true
                    }).showToast();
                    setTimeout(() => {
                        window.location.assign(returnURL);
                    }, 3000);
				}else{
                    Toastify({
                        text: obj['msg'],
                        duration: 3000,
                        gravity: "top", // `top` or `bottom`
                        position: "center", // `left`, `center` or `right`
                        backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)",
                        stopOnFocus: true
                    }).showToast();
					// swal("Error!", obj['msg'], "error");
					return false;
				}
			},error: function(ts) {
				$('#submitBtn').html('Submit');
                Toastify({
                        text: 'Some thing want to wrong, please try after sometime.',
                        duration: 3000,
                        gravity: "top", // `top` or `bottom`
                        position: "center", // `left`, `center` or `right`
                        backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)",
                        stopOnFocus: true
                    }).showToast();
				//swal("Error!", 'Some thing want to wrong, please try after sometime.', "error");
				return false;
			}
		}); 
	});
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