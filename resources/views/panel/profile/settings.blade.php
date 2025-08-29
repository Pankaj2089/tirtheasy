@extends('layout.admin.dashboard')

@section('content')

<div class="content-wrapper">
          <!-- Content -->
          <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row gy-6">
        <!-- Input Mask -->
        <div class="col-12">
        <div class="card">
            <h5 class="card-header">Settings</h5>
            <div class="card-body">
                <form class="form w-100" id="updateForm" action="#">
                    <div class="row">
                        <!-- Admin Email -->
                        <div class="col-xl-4 col-md-6 col-sm-12 mb-6">
                            <input type="hidden" name="old_banner" value="{!! $setting->logo !!}" />
                            <div class="form-group">
                                <label for="basicInput">Admin Email</label>
                                <input type="text" class="form-control" placeholder="Enter Email" value="{!! $setting->admin_email !!}" name="admin_email" id="admin_email">
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-6 col-sm-12 mb-6">
                            <div class="form-group">
                                <label for="basicInput">Company Name</label>
                                <input type="text" class="form-control" value="{!! $setting->company_name !!}" id="company_name" name="company_name">
                            </div>
                        </div>                        
                        <div class="col-xl-4 col-md-6 col-sm-12 mb-6">
                            <div class="form-group">
                                <label for="basicInput">Company Contact Number</label>
                                <input type="text" class="form-control" value="{!! $setting->mobile !!}" id="mobile" name="mobile">
                            </div>
                        </div>                        
                        <div class="col-xl-4 col-md-6 col-sm-12 mb-6">
                            <div class="form-group">
                                <label for="basicInput">Footer Content</label>
                                <input type="text" class="form-control" value="{!! $setting->footer_content !!}" id="footer_content" name="footer_content">
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-6 col-sm-12 mb-6">
                            <div class="form-group">
                                <label for="basicInput">Company Logo</label>
                                <input class="form-control" type="file" name="logo" id="logo" autocomplete="off" accept="image/png, image/jpg" />
                            </div>
                        </div>
                       
                        <div class="col-xl-4 col-md-6 col-sm-12 mb-6">
                            @if(!empty($setting->logo))
                                <div class="form-group">
                                    <label class="basicInput">Company Logo</label>
                                    <div class="col-lg-6 fv-row fv-plugins-icon-container">
                                        <div class="cropped" id="cropped">
                                            <div class="cropped" id="cropped"><img src="{{URL::asset('public/admin/images/profile/')}}/{!! $setting->logo !!}" width="150"></div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="col-xl-4 col-md-6 col-sm-12 mb-6">
                            <div class="form-group">
                                <label for="basicInput">Business Address</label>
                                <textarea rows="6" class="form-control" id="business_address" name="business_address">{!! $setting->business_address !!}</textarea>
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-6 col-sm-12 mb-6">
                            <div class="form-group">
                                <label for="basicInput">Footer Info</label>
                                <textarea rows="6" class="form-control" id="footer_info" name="footer_info">{!! $setting->footer_info !!}</textarea>
                            </div>
                        </div>
                     </div>

                     @php
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
                         
                        <div class="row">
                                <h5 class="mb-5 mt-6">Register Page Content</h5>
                        <!-- Admin Email -->
                        <div class="col-xl-4 col-md-6 col-sm-12 mb-6">
                            <div class="form-group">
                                <label for="basicInput">Left Section Heading</label>
                                <input type="text" class="form-control" value="{!! $left_section_heading !!}" id="left_section_heading" name="left_section_heading">
                            </div>
                        </div>   
                        <div class="col-xl-8 col-md-6 col-sm-12 mb-6">
                            <div class="form-group">
                                <label for="basicInput">Left Section Content</label>
                                <textarea rows="6" class="form-control" id="left_section_content" name="left_section_content">{!! $left_section_content !!}</textarea>
                            </div>
                        </div>

                        <div class="col-xl-4 col-md-6 col-sm-12 mb-6">
                            <div class="form-group">
                                <label for="basicInput">Right Section Heading</label>
                                <input type="text" class="form-control" value="{!! $right_section_heading !!}" id="right_section_heading" name="right_section_heading">
                            </div>
                        </div>   
                        <div class="col-xl-8 col-md-6 col-sm-12 mb-6">
                            <div class="form-group">
                                <label for="basicInput">Right Section Content</label>
                                <textarea rows="6" class="form-control" id="right_section_content" name="right_section_content">{!! $right_section_content !!}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="text-left">
                            <!--begin::Submit button-->
                            <button type="button" id="profile_submit" class="btn btn-sm btn-primary fw-bolder me-3 my-2">
                                <span class="indicator-label" id="formSubmit">Submit</span>
                                <span class="indicator-progress d-none">Please wait...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                            </button>
                            <!--end::Submit button-->
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
        <!-- /Input Mask -->
  </div>
</div>
<!-- / Content -->

<script>
    let saveDataURL = "{{route('admin.save-setting')}}";
</script>
<script src="{{ asset('public/admin/js/pages/update-settings.js') }}"></script>


@endsection


