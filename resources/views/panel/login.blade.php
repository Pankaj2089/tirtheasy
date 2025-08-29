@extends('layout.admin.login')
@section('content')
<div class="authentication-wrapper authentication-cover">
      <!-- Logo -->
      <a href="{{route('admin.login')}}" class="app-brand auth-cover-brand">
        <span class="app-brand-logo demo">
          <span class="text-primary">
            <svg width="32" height="22" viewBox="0 0 32 22" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path
                fill-rule="evenodd"
                clip-rule="evenodd"
                d="M0.00172773 0V6.85398C0.00172773 6.85398 -0.133178 9.01207 1.98092 10.8388L13.6912 21.9964L19.7809 21.9181L18.8042 9.88248L16.4951 7.17289L9.23799 0H0.00172773Z"
                fill="currentColor" />
              <path
                opacity="0.06"
                fill-rule="evenodd"
                clip-rule="evenodd"
                d="M7.69824 16.4364L12.5199 3.23696L16.5541 7.25596L7.69824 16.4364Z"
                fill="#161616" />
              <path
                opacity="0.06"
                fill-rule="evenodd"
                clip-rule="evenodd"
                d="M8.07751 15.9175L13.9419 4.63989L16.5849 7.28475L8.07751 15.9175Z"
                fill="#161616" />
              <path
                fill-rule="evenodd"
                clip-rule="evenodd"
                d="M7.77295 16.3566L23.6563 0H32V6.88383C32 6.88383 31.8262 9.17836 30.6591 10.4057L19.7824 22H13.6938L7.77295 16.3566Z"
                fill="currentColor" />
            </svg>
          </span>
        </span>
        <span class="app-brand-text demo text-heading fw-bold">Tirtheasy</span>
      </a>
      <!-- /Logo -->
      <div class="authentication-inner row m-0">
        <!-- /Left Text -->
        <div class="d-none d-xl-flex col-xl-8 p-0">
          <div class="auth-cover-bg d-flex justify-content-center align-items-center">
          <img src="https://demos.pixinvent.com/vuexy-html-admin-template/assets/img/illustrations/auth-login-illustration-light.png" alt="auth-login-cover" class="my-5 auth-illustration" data-app-light-img="illustrations/auth-login-illustration-light.png" data-app-dark-img="illustrations/auth-login-illustration-dark.png" style="visibility: visible;">
          </div>
        </div>
        <!-- /Left Text -->

        <!-- Login -->
        <div class="d-flex col-12 col-xl-4 align-items-center authentication-bg p-sm-12 p-6">
          <div class="w-px-400 mx-auto mt-12 pt-5">
            <h4 class="mb-1">Welcome to Tirtheasy</h4>
            <p class="mb-6">Please sign-in to your account and start the adventure</p>

            <form id="adminLoginForm" class="mb-6" >
              <div class="mb-6 form-control-validation">
                <label for="email" class="form-label">Email or Username</label>
                <input
                  type="text"
                  class="form-control"
                  id="email"
                  name="email"
                  placeholder="Enter your email or username"
                  value="{{$cookieUsername}}"
                  autofocus />
              </div>
              <div class="mb-6 form-password-toggle form-control-validation">
                <label class="form-label" for="password">Password</label>
                <div class="input-group input-group-merge">
                  <input
                    type="password"
                    id="password"
                    class="form-control"
                    name="password"
                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                    aria-describedby="password"
                    value="{{$cookiePassword}}"
                    />
                  <span class="input-group-text cursor-pointer "><i class="icon-base ti tabler-eye-off d-none"></i></span>
                </div>
              </div>
              <div class="my-8">
                <div class="d-flex justify-content-between">
                  <div class="form-check mb-0 ms-2">
                    <input class="form-check-input" type="checkbox" id="remember-me" name="reminderMe" checked value="1" />
                    <label class="form-check-label" for="remember-me"> Remember Me </label>
                  </div>
                </div>
              </div>
              <a id="loginSubmit" style="color:#FFF" class="btn btn-primary d-grid w-100">Sign in</a>
            </form>

            <!--<p class="text-center">
              <span>New on our platform?</span>
              <a href="#">
                <span>Create an account</span>
              </a>
            </p>-->


            
          </div>
        </div>
        <!-- /Login -->
      </div>
    </div>
    <script type="text/javascript">
	let adminLoginURL = "{{url('/panel/admin-login')}}";
	let dashboardURL = "{{url('/panel/dashboard')}}";
	$(document).ready(function(){
		$("#email, #password").on('keyup', function (e) {
			if (e.keyCode == 13) {
				$('#loginSubmit').trigger('click');
			}
		});
		$('#loginSubmit').click(function(e){
			var flag = 0;
			if($.trim($("#email").val()) == ''){
				flag = 1;
				showMessage('Please Enter Account Username.');
				return false;
			}
			if($.trim($("#password").val()) == ''){
				flag = 1;
				showMessage('Please Enter Account Password.');
				return false;
			}
			if(flag == 0){
				$('#loginSubmit').html('Processing...');
				$.ajax({
					type: 'POST',
					headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
					url: adminLoginURL,
					data: $('#adminLoginForm').serialize(),
					beforeSend:function(){$('#loginSubmit').removeClass('login-btn').addClass('login-btn-processing').val('Processing...'); },
					success: function(msg){
						var obj = JSON.parse(msg);
						$('#loginSubmit').html('Log In');
						if(obj['heading'] == "Success"){
							window.location.assign(dashboardURL);
						}else{
							showMessage(obj['msg']);
							return false;
						}
					},error: function(ts) {
						showMessage('Some thing want to wrong, please try after sometime.');
						return false;
					}
				});
			}
		});
	});
	function showMessage(msg){
		swal("Error!", msg, "error");
	}
	</script>
@endsection
