@php
$siteUrl = env('APP_URL');
@endphp
@extends('layout.default')
@section('content')
<link href="{{$siteUrl}}public/css/new-style.css" rel="stylesheet">
<link href="{{$siteUrl}}public/css/contact.css" rel="stylesheet">

 <section class="banner-section contact-us-banner">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="kk-banner-contant align-items-center">
                        <h1 class="buy-title text-align-center pb-0">Partner Registration</h1>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <section class="question-section py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <h2>Please fill your personal details</h2>
                </div>
            </div>
            <div class="row pt-4">
            <div class="col-lg-2"></div>
                <div class="col-lg-8">
                    <div class="inner-form">
                        <form class="row g-3 mt-3" id="detailsForm" method="post">
                        <div class="col-md-6">
                              <label for="gender" class="form-label">Registration Type</label>
                              <select class="form-select" id="type" name="type">
                              <option value="">Select</option>
                              <option value="Manufacturer">Manufacturer</option>
                              <option value="Modifier">Modifier</option>
                              <option value="Distributer">Distributer</option>
                              </select>
                            </div>
                            <div class="col-md-6">
                              <label for="company" class="form-label">Business / Brand Name</label>
                              <input type="text" class="form-control" id="company" name="company">
                            </div>
                            <div class="col-md-6">
                              <label for="name" class="form-label">Name of Contact Person</label>
                              <input type="text" class="form-control" id="name" name="name">
                            </div>
                            <div class="col-md-6">
                              <label for="email" class="form-label">Email</label>
                              <input type="email" class="form-control" id="email" name="email">
                            </div>
                            <div class="col-md-6">
                              <label for="phone" class="form-label">Phone No</label>
                              <input type="text" class="form-control" id="phone" name="phone" maxlength="10">
                            </div>
                            <div class="col-md-6">
                              <label for="password" class="form-label">Password</label>
                              <input type="password" class="form-control" id="password" name="password">
                            </div>
                            <div class="col-md-6">
                              <label for="cpassword" class="form-label">Confirm Password</label>
                              <input type="password" class="form-control" id="cpassword" name="cpassword">
                            </div>
                            <div class="col-md-6">
                              <label for="company_type" class="form-label">Business Type</label>
                              <select class="form-select" id="company_type" name="company_type">
                              <option value="">Select</option>
                              <option value="Individual">Individual</option>
                              <option value="Pvt. Ltd">Pvt. Ltd</option>
                              <option value="LLP">LLP</option>
                              <option value="Other">Other</option>
                              </select>
                            </div>
                            <div class="col-md-6">
                              <label for="gstin" class="form-label">GSTIN (Optional but preferred)</label>
                              <input type="email" class="form-control" id="gstin" name="gstin">
                            </div>
                            
                            
                            <div class="col-12">
                                <button type="button" id="submitBtn" class="btn mt-2">Submit</button>
                            </div>
                            
                          </form>
                    </div>
                </div>
                <div class="col-lg-2"></div>

            </div>
        </div> 
    </section>
	<script>
    $(document).ready(function(e) {
        $('#submitBtn').click(function(e) {
			
			$('#submitBtn').html('Processing...');
            $.ajax({
				headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
				type: 'POST',
				data: $('#detailsForm').serialize(),
				url: "{{ route('partnerRegister') }}",
				success: function(response){
					const obj = JSON.parse(response);
					if(!obj.success){
						Toastify({
						text: obj.msg,
						duration: 3000,
						close: true,
						style: {background: "#f00"}
						}).showToast();
					}else{
						Toastify({
						text: obj.msg,
						duration: 3000,
						close: true,
						style: {background: "#093"}
						}).showToast();
					}
					$('#submitBtn').html('Submit');
				}
			});
        });
    });
    </script>
    
  
    
@endsection