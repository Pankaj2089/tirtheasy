@extends('layout.admin.dashboard')
@php
$siteUrl = env('APP_URL');
@endphp
@section('content')
<link href="{{ URL::asset('public/admin/css/dropzone.css') }}" rel="stylesheet">
<script src="{{ URL::asset('public/admin/js/dropzone.js') }}"></script>
<div class="container-xxl flex-grow-1 container-p-y">
  <div class="row">
    <div class="col">
      <h6 class="mt-6">Edit Hotel</h6>
      <div class="row">
      <div class="col-12 col-md-9">
      <div class="card mb-6">
      	<form id="pageForm" enctype="multipart/form-data" method="post">
        <input type="hidden" name="old_banner" value="{{$record->image}}" />
        <div class="card-header px-0 pt-0">
          <div class="nav-align-top">
            <ul class="nav nav-tabs" role="tablist">
              <li class="nav-item">
                <button type="button" class="nav-link active" data-bs-toggle="tab" data-bs-target="#form-tabs-personal" aria-controls="form-tabs-personal"
role="tab" aria-selected="true"> <span class="icon-base ti tabler-user icon-lg d-sm-none"></span><span class="d-none d-sm-block">General Info</span> </button>
              </li>
              <li class="nav-item">
                <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#form-tabs-information" aria-controls="form-tabs-account"
role="tab" aria-selected="false"> <span class="icon-base ti tabler-user-cog icon-lg d-sm-none"></span><span class="d-none d-sm-block">Information</span> </button>
              </li>
              <li class="nav-item">
                <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#form-tabs-images" aria-controls="form-tabs-account"
role="tab" aria-selected="false"> <span class="icon-base ti tabler-user-cog icon-lg d-sm-none"></span><span class="d-none d-sm-block">Hotel Images</span> </button>
              </li>
              <li class="nav-item">
                <button type="button" onclick="getFaqs()" class="nav-link" data-bs-toggle="tab" data-bs-target="#form-tabs-faqs" aria-controls="form-tabs-account"
role="tab" aria-selected="false"> <span class="icon-base ti tabler-user-cog icon-lg d-sm-none"></span><span class="d-none d-sm-block">FAQs</span> </button>
              </li>
              <li class="nav-item">
                <button type="button" onclick="getRules()" class="nav-link" data-bs-toggle="tab" data-bs-target="#form-tabs-rules" aria-controls="form-tabs-account"
role="tab" aria-selected="false"> <span class="icon-base ti tabler-user-cog icon-lg d-sm-none"></span><span class="d-none d-sm-block">Rules</span> </button>
              </li>
              <li class="nav-item">
                <button type="button" onclick="getLandmarks()" class="nav-link" data-bs-toggle="tab" data-bs-target="#form-tabs-landmarks" aria-controls="form-tabs-landmarks"
role="tab" aria-selected="false"> <span class="icon-base ti tabler-user-cog icon-lg d-sm-none"></span><span class="d-none d-sm-block">Landmarks</span> </button>
              </li>
              <li class="nav-item">
                <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#form-tabs-account" aria-controls="form-tabs-account"
role="tab" aria-selected="false"> <span class="icon-base ti tabler-user-cog icon-lg d-sm-none"></span><span class="d-none d-sm-block">SEO Details</span> </button>
              </li>
              
            </ul>
          </div>
        </div>
        <div class="card-body">
          <div class="tab-content p-0">
            <div class="tab-pane fade active show" id="form-tabs-personal" role="tabpanel">
        
                <div class="row g-6">
                  <div class="col-md-8">
                    <label class="form-label" for="title">Hotel Name</label>
                    <input type="text" id="title" name="title" class="form-control" value="{{$record->title}}" />
                  </div>
                  <div class="col-md-4">
                    <label class="form-label" for="hotel_type">Hotel Type</label>
                    <select type="text" id="hotel_type" name="hotel_type" class="form-select" >
                        <option value="hotel" {{$record->hotel_type == "hotel" ?'selected':''}}>Hotel</option>
                        <option value="dharmshala"{{$record->hotel_type == "dharmshala" ?'selected':''}}>Dharmshala</option>
                    </select>
                  </div>
                  <div class="col-md-3">
                    <label class="form-label" for="state">State</label>
                    <select type="text" id="state" name="state" class="form-select select2" onchange="getCities()" >
                        <option value="">Select</option>
                        @foreach($states as $state)
                          <option value="{{$state->title}}" {{$record->state == $state->title ?'selected':''}} data-id="{{$state->id}}">{{$state->title}}</option>
                        @endforeach
                    </select>
                  </div>
                  <div class="col-md-3">
                    <label class="form-label" for="city">City</label>
                    <select type="text" id="city" name="city" class="form-select" >
                        <option value="">Select</option>
                         @foreach($cities as $city)
                          <option value="{{$city->title}}"  {{$record->city == $city->title ?'selected':''}} >{{$city->title}}</option>
                        @endforeach
                    </select>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label" for="address">Address</label>
                    <input type="text" id="address" name="address" class="form-control" value="{{$record->address}}"  />
                  </div>
                  <div class="col-md-3">
                    <label class="form-label" for="price">Price</label>
                    <input type="number" id="price" name="price"  value="{{$record->price}}" class="form-control" />
                  </div>
                  <div class="col-md-3">
                    <label class="form-label" for="cancellation_policy">Cancellation</label>
                    <select id="cancellation_policy" name="cancellation_policy" class="form-select" >
                      <option value="1" {{$record->cancellation_policy == 1 ? 'selected':''}}>Select</option>
                      <option value="2" {{$record->cancellation_policy == 2 ? 'selected':''}}>Cancellation</option>
                      <option value="3" {{$record->cancellation_policy == 3 ? 'selected':''}}>No Cancellation</option>
                    </select>
                    </div>
                  <div class="col-md-6">
                    @php
                    $amenitiesArr = [];
                    if(!empty($record->amenities)){
                      $amenitiesArr = json_decode($record->amenities);
                    }
                    @endphp
                    <label class="form-label" for="amenities">Amenities</label>
                    <select type="text" id="amenities" name="amenities[]" class="form-select select2" multiple >
                        <option value="">Select</option>
                        @foreach($amenities as $amenitycat)
                          @if(isset($amenitycat->amenities) && count($amenitycat->amenities) > 0)
                          <optgroup label="{{$amenitycat->title}}">
                              @foreach($amenitycat->amenities as $amenity)
                              <option value="{{$amenity->id}}" {{in_array($amenity->id , $amenitiesArr) ?'selected':''}}>{{$amenity->title}}</option>
                              @endforeach
                          </optgroup>
                          @endif
                        @endforeach
                    </select>
                  </div>
                  <div class="col-md-6">
                    @php
                    $facilitiesArr = [];
                    if(!empty($record->facilities)){
                      $facilitiesArr = json_decode($record->facilities);
                    }
                    @endphp
                    <label class="form-label" for="facilities">Facilities</label>
                    <select type="text" id="facilities" name="facilities[]" class="form-select select2" multiple >
                        <option value="">Select</option>
                        @foreach($facilities as $facility)
                          <option value="{{$facility->id}}" {{in_array($facility->id , $facilitiesArr) ?'selected':''}}>{{$facility->title}}</option>
                        @endforeach
                    </select>
                  </div>
                  <div class="col-md-3">
                    <label class="form-label" for="check_in_time">Check In</label>
                    <input type="time" id="check_in_time" name="check_in_time" class="form-control"  value="{{$record->check_in_time}}" />
                  </div>
                  <div class="col-md-3">
                    <label class="form-label" for="check_out_time">Check Out</label>
                    <input type="time" id="check_out_time" name="check_out_time" class="form-control"  value="{{$record->check_out_time}}" />
                  </div>
                  <div class="col-md-4">
                    <label class="form-label" for="banner">Main Image</label>
                    <input type="file" id="banner" name="banner" class="form-control" />
                  </div>         
                
                 <div class="col-md-4">
                    <label class="form-label" for="latitude">Latitude</label>
                    <input type="text" id="latitude" name="latitude" class="form-control"  value="{{$record->latitude}}"  />
                  </div>
                  <div class="col-md-4">
                  <label class="form-label" for="longitude">Longitude</label>
                  <input type="text" id="longitude" name="longitude" class="form-control"  value="{{$record->longitude}}"  />
                </div>
                
                <div class="col-md-4">
                  <label class="form-label" for="is_only_jain">Only For Jain</label>
                  <select id="is_only_jain" name="is_only_jain" class="form-select">
                        <option value="2" {{$record->is_only_jain == 2 ? 'selected':''}}>No</option>
                        <option value="1" {{$record->is_only_jain == 1 ? 'selected':''}}>Yes</option>
                    </select>
                </div>
                </div>
            </div>
            <div class="tab-pane fade" id="form-tabs-information" role="tabpanel">
                <div class="row g-6">
                  <div class="col-md-12">
                    <label class="form-label" for="description">Policy</label>
                    <textarea class="form-control editor"  rows="10" id="policy" name="policy">{{$record->policy}}</textarea>
                  </div> 
                  <div class="col-md-12">
                    <label class="form-label" for="description">Description</label>
                    <textarea class="form-control editor" rows="10" id="description" name="description">{{$record->description}}</textarea>
                  </div>  
                    
                  <div class="col-md-12">
                    <label class="form-label" for="description">Special Note</label>
                    <textarea class="form-control editor"  rows="10" id="special_note" name="special_note">{{$record->special_note}}</textarea>
                  </div> 
                  <div class="col-md-12">
                    <label class="form-label" for="description">Extra Bed Policy</label>
                    <textarea class="form-control editor" rows="10" id="extra_bed_policy" name="extra_bed_policy">{{$record->extra_bed_policy}}</textarea>
                  </div>  
                    
                  <div class="col-md-12">
                    <label class="form-label" for="description">Check In Restrictions</label>
                    <textarea class="form-control editor" rows="10" id="check_in_restrictions" name="check_in_restrictions">{{$record->check_in_restrictions}}</textarea>
                  </div>
                  <div class="col-md-12">
                    <label class="form-label" for="description">Food Arrangement</label>
                    <textarea class="form-control editor" rows="10" id="food_arrangement" name="food_arrangement">{{$record->food_arrangement}}</textarea>
                  </div>
                  <div class="col-md-12">
                    <label class="form-label" for="description">Id Proof Rrelated</label>
                    <textarea class="form-control editor" rows="10" id="id_proof_related" name="id_proof_related">{{$record->id_proof_related}}</textarea>
                  </div>
                  <div class="col-md-12">
                    <label class="form-label" for="description">Property Accessibility</label>
                    <textarea class="form-control editor" rows="10" id="property_accessibility" name="property_accessibility">{{$record->property_accessibility}}</textarea>
                  </div>
                  <div class="col-md-12">
                    <label class="form-label" for="description">Pet(s) Related</label>
                    <textarea class="form-control editor" rows="10" id="pet_related" name="pet_related">{{$record->pet_related}}</textarea>
                  </div>
                  <div class="col-md-12">
                    <label class="form-label" for="description">Other Rules</label>
                    <textarea class="form-control editor" rows="10" id="other_rules" name="other_rules">{{$record->other_rules}}</textarea>
                  </div>
                  
                </div>
            </div>
            
            <div class="tab-pane fade" id="form-tabs-images" role="tabpanel">
              <div class="row">
                    <div class="col-md-12">
                    <form class="form w-100" id="pageForm" action="#">
                      <div class="row">
                          <div class="col-12 col-md-12">
                              <div class="card">
                                  <div class="card-body"> 
                                      <div class="row">
                                          <div class="col-12">
                                            <label for="basicInput">Upload Hotel Images</label>
                                              <div id="my-awesome-dropzone" class="dropzone"></div>
                                          </div>
                                          @if(isset($hotel_images) && count($hotel_images) > 0)
                                          <div class="col-12 mt-4 mb-4"><label for="basicInput">Hotel Images</label></div>
                                          <div class="col-12" id="replaceHtml">
                                              <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                              <thead>
                                                <tr>
                                                  <th>#</th>
                                                  <th>Image</th>
                                                  <th>Status</th>
                                                  <th>Action</th>
                                                  <th>Created</th> 
                                                </tr>
                                              </thead>
                                              <tbody>
                                              @foreach($hotel_images as $key => $value)
                                              <tr>
                                                <td>{{ $key+1; }}</td>
                                                <td>
                                                  <a target="_blank" href="{{ URL::asset('public/img/hotel/') }}/{!! $value->image !!}" data-sub-html="Image"> 
                                                  <img width="100px" class="img-responsive" src="{{ URL::asset('public/img/hotel/') }}/{!! $value->image !!}"> 
                                                  </a>
                                                </td>
                                                <td> 
                                                   @php
                                                    if($value->status == 1){$class = 'bg-label-success'; $label = 'Active';}else{$class = 'bg-label-danger'; $label = 'In-Active';}
                                                    @endphp
                                                    <a style="cursor:pointer" onclick="changeStatus('hotel_images','{!!$value->id!!}');" id="status_{{$value->id}}" class="badge {{$class}} me-1">{{$label}}</a>
                                                    <input type="hidden" id="status_value_{{$value->id}}" value="{!!$value->status!!}" />
                                                  </td>
                                                <td><a href="javascript:void(0);" onclick="deleteImgData('hotel_images','{{ $value->id }}');" class="btn btn-sm btn-danger"  title="Delete">
                                                      Delete
                                                  </a></td>
                                                <td>{{ date("F jS, Y h:i A",strtotime($value->created_at)); }}</td> 
                                              </tr>
                                              @endforeach
                                              </tbody>
                                            </table>
                                          </div>
                                          @endif                                          
                                      </div>                        
                                  </div>                    
                              </div>
                          </div>            
                      </div>
                      </form>
                    </div>
                  </div>
            </div>
            
            <div class="tab-pane fade" id="form-tabs-faqs" role="tabpanel">
              <div class="row">
                    <div class="col-md-12">
                    <form class="form w-100" id="pageForm" action="#">
                      <div class="row">
                          <div class="col-12 col-md-12">
                              <div class="card">
                                  <div class="card-body"> 
                                      <div class="row">
                                         <h5 class="card-header p-0 mb-4">FAQs <a onclick="$('#popupHeading').html('Add New Faq');clearForm();" style="float:right; color:#FFF" data-bs-toggle="modal"
              data-bs-target="#addNewCCModal" class="btn btn-success waves-effect waves-light">Add New</a></h5>
                                          <div class="col-12" id="replaceHtml2">
                                             <p class="p-4 text-center" >Loading....</p>
                                          </div>                                       
                                      </div>                        
                                  </div>                    
                              </div>
                          </div>            
                      </div>
                      </form>
                    </div>
                  </div>
            </div>
            <div class="tab-pane fade" id="form-tabs-rules" role="tabpanel">
              <div class="row">
                    <div class="col-md-12">
                    <form class="form w-100" id="pageForm" action="#">
                      <div class="row">
                          <div class="col-12 col-md-12">
                              <div class="card">
                                  <div class="card-body"> 
                                      <div class="row">
                                         <h5 class="card-header p-0 mb-4">Rules <a onclick="$('#popupRulesHeading').html('Add New Rule');clearRuleForm();" style="float:right; color:#FFF" data-bs-toggle="modal"
              data-bs-target="#addNewRCModal" class="btn btn-success waves-effect waves-light">Add New</a></h5>
                                          <div class="col-12" id="replaceHtml3">
                                             <p class="p-4 text-center" >Loading....</p>
                                          </div>                                     
                                      </div>                        
                                  </div>                    
                              </div>
                          </div>            
                      </div>
                      </form>
                    </div>
                  </div>
            </div>

          <div class="tab-pane fade" id="form-tabs-landmarks" role="tabpanel">
              <div class="row">
                    <div class="col-md-12">
                    <form class="form w-100" id="pageForm" action="#">
                      <div class="row">
                          <div class="col-12 col-md-12">
                              <div class="card">
                                  <div class="card-body"> 
                                      <div class="row">
                                         <h5 class="card-header p-0 mb-4">Landmarks <a onclick="$('#popupLandmarksHeading').html('Add New Landmarks');clearLandmarksForm();" style="float:right; color:#FFF" data-bs-toggle="modal"
              data-bs-target="#addNewLandmarksModal" class="btn btn-success waves-effect waves-light">Add New</a></h5>
                                          <div class="col-12" id="replaceHtml5">
                                             <p class="p-4 text-center" >Loading....</p>
                                          </div>                                     
                                      </div>                        
                                  </div>                    
                              </div>
                          </div>            
                      </div>
                      </form>
                    </div>
                  </div>
            </div>

            <div class="tab-pane fade" id="form-tabs-account" role="tabpanel">
                <div class="row g-6">
                  <div class="col-md-12">
                    <label class="form-label" for="seo_title">SEO Title</label>
                    <input type="text" id="seo_title" name="seo_title" class="form-control" value="{{$record->seo_title}}"/>
                  </div>
                  <div class="col-md-12">
                    <label class="form-label" for="seo_description">SEO Description</label>
                    <textarea class="form-control" id="seo_description" name="seo_description">{{$record->seo_description}}</textarea>
                  </div>
                  <div class="col-md-12">
                    <label class="form-label" for="seo_keyword">SEO Keywords</label>
                    <textarea class="form-control" id="seo_keyword" name="seo_keyword">{{$record->seo_keyword}}</textarea>
                  </div>
                </div>
            </div>
          </div>
        </div>
        </form>
      </div>
      </div>
        <div class="col-12 col-md-3">
            <div class="card mb-6">
                <div class="card-body">
                <div class="row g-6">
                @if($record->image != '')
                  <div class="col-md-12">
                    <img src="{{$siteUrl}}/public/img/hotel/{{$record->image}}" width="100%" />
                  </div>
                  @endif
                </div>
                    <div class="pt-6">
                        <button  type="button" id="submitBtn" class="btn btn-primary me-4">Submit</button>
                        <button type="reset" onclick="window.location.href='{{route('admin.hotels')}}'" class="btn btn-label-secondary" >Cancel</button>
                    </div>
                </div>
            </div>
        </div>
        </div>
      
    </div>
  </div>
</div>

<!-- Add New Rules Modal -->
  <div class="modal fade" id="addNewRCModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered1 modal-simple modal-add-new-cc">
      <div class="modal-content">
        <div class="modal-body">
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          <div class="text-center mb-6">
            <h4 id="popupRulesHeading" class="mb-2">Add New Faq</h4>
            <!--<p>Create a brand new category from here.</p>-->
          </div>
          <form id="pageRuleForm" class="row g-6" method="post">
            <input id="hotel_id" name="hotel_id"  value="{{$record->id}}" type="hidden" />
          <input type="hidden" id="row_id" name="row_id" value="0" />
            <div class="col-12 form-control-validation">
              <label class="form-label w-100" for="title">Title</label>
              <div class="input-group-merge">
                <input id="title" name="title" class="form-control credit-card-mask" placeholder="Please enter title" type="text" aria-describedby="title" />
              </div>
            </div>
            <div class="col-12 form-control-validation">
              <label class="form-label w-100" for="answer">Description</label>
              <div class="input-group-merge">
                <textarea id="description" name="description" class="form-control credit-card-mask" placeholder="Please enter description" type="text" aria-describedby="description" ></textarea>
              </div>
            </div>
            <div class="col-12 text-center">
              <button type="button" id="submitRuleBtn" class="btn btn-primary me-3">Submit</button>
              <button type="reset" class="btn btn-label-secondary btn-reset" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  
<!-- Add New Faqs Modal -->
    <div class="modal fade" id="addNewCCModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered1 modal-simple modal-add-new-cc">
        <div class="modal-content">
          <div class="modal-body">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="text-center mb-6">
              <h4 id="popupHeading" class="mb-2">Add New FAQ</h4>
              <!--<p>Create a brand new category from here.</p>-->
            </div>
            <form id="pageFaqForm" class="row g-6" method="post">
              <input id="hotel_id" name="hotel_id"  value="{{$record->id}}" type="hidden" />
            <input type="hidden" id="row_id" name="row_id" value="0" />
              <div class="col-12 form-control-validation">
                <label class="form-label w-100" for="title">Question</label>
                <div class="input-group-merge">
                  <input id="question" name="question" class="form-control credit-card-mask" placeholder="Please enter question" type="text" aria-describedby="question" />
                </div>
              </div>
              <div class="col-12 form-control-validation">
                <label class="form-label w-100" for="answer">Answer</label>
                <div class="input-group-merge">
                  <textarea id="answer" name="answer" class="form-control credit-card-mask" placeholder="Please enter answer" type="text" aria-describedby="answer" ></textarea>
                </div>
              </div>
              <div class="col-12 text-center">
                <button type="button" id="submitFAQBtn" class="btn btn-primary me-3">Submit</button>
                <button type="reset" class="btn btn-label-secondary btn-reset" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Add New Landmark Modal -->
    <div class="modal fade" id="addNewLandmarksModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered1 modal-simple modal-add-new-cc">
        <div class="modal-content">
          <div class="modal-body">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="text-center mb-6">
              <h4 id="popupLandmarksHeading" class="mb-2">Add New Landmark</h4>
              <!--<p>Create a brand new category from here.</p>-->
            </div>
            <form id="pageLandmarkForm" class="row g-6" method="post">
              <input id="l_hotel_id" name="hotel_id"  value="{{$record->id}}" type="hidden" />
            <input type="hidden" id="l_row_id" name="row_id" value="0" />
             <div class="col-12 form-control-validation">
                <label class="form-label w-100" for="title">Landmark Type</label>
                <div class="input-group-merge">
                 <select name="category_id" id="l_category_id" class="form-select">
                    <option value="1" selected>Walkable Places</option>
                    <option value="2">Popular Landmarks</option>
                    <option value="3">Closest Landmarks	</option>
                </select>
                </div>
              </div>
              <div class="col-12 form-control-validation">
                <label class="form-label w-100" for="title">Title</label>
                <div class="input-group-merge">
                  <input id="l_title" name="title" class="form-control credit-card-mask" placeholder="Please enter title" type="text" aria-describedby="title" />
                </div>
              </div>
              <div class="col-12 form-control-validation">
                <label class="form-label w-100" for="answer">Value</label>
                <div class="input-group-merge">
                  <input type="text" id="l_description" name="description" class="form-control credit-card-mask" placeholder="Please enter value" type="text" aria-describedby="description" />
                </div>
              </div>
               <div class="col-12 form-control-validation">
                <label class="form-label w-100" for="title">Icon</label>
                <div class="input-group-merge">
                    <input type="file" id="l_icon" name="icon" class="form-control" accept="image/png, image/jpeg" />
                    <input type="hidden" name="old_icon" id="l_old_icon" value="" />
                </div>
                <div id="oldImage"></div>
              </div>
              <div class="col-12 text-center">
                <button type="button" id="submitLandmarkBtn" class="btn btn-primary me-3">Submit</button>
                <button type="reset" class="btn btn-label-secondary btn-reset" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

<script type="text/javascript">
var addUrl = "{{url('/panel/edit-hotel/')}}/{{$record->id}}";
var getUrl = "{{route('admin.get-cities')}}";

var addFAQUrl = "{{route('admin.add-hotel-faqs')}}";
var getFaqUrl = "{{route('admin.get-hotel-faqs')}}";


var addRuleUrl = "{{route('admin.add-hotel-rules')}}";
var getRuleUrl = "{{route('admin.get-hotel-rules')}}";
var addLandmarkUrl = "{{route('admin.add-hotel-landmark')}}";
var getLandmarksUrl = "{{route('admin.get-landmarks-rules')}}";

$(document).ready(function(){
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
          if(obj['heading'] == "Success"){
            swal("", obj['msg'], "success").then((value) => {
              window.location.href = "{{url('/panel/edit-hotel/')}}/{{$record->id}}";
            });
            
          }else{
            swal("Error!", obj['msg'], "error");
            return false;
          }
        },error: function(ts) {
          $('#submitBtn').html('Submit');
          swal("Error!", 'Some thing want to wrong, please try after sometime.', "error");
          return false;
        }
      }); 
    });

    $('#submitFAQBtn').click(function(e) {
      $('#submitFAQBtn').html('Processing...');
      var form = $('#pageFaqForm')[0];
      var formData = new FormData(form);
        $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        type: 'POST',
        data:formData,
        url: addFAQUrl,
        processData: false,
              contentType: false,
        success: function(response){
          $('#submitFAQBtn').html('Submit');
          var obj = JSON.parse(response);
          if(obj['heading'] == "Success"){
            $('#addNewCCModal').modal('hide');
            $('#pageFaqForm')[0].reset();
            getFaqs();
            swal("", obj['msg'], "success").then((value) => {
              window.location.assign(returnURL);
            });
          }else{
            swal("Error!", obj['msg'], "error");
            return false;
          }
        },error: function(ts) {
          $('#submitFAQBtn').html('Submit');
          swal("Error!", 'Some thing want to wrong, please try after sometime.', "error");
          return false;
        }
      }); 
    });

    $('#submitRuleBtn').click(function(e) {
      $('#submitRuleBtn').html('Processing...');
      var form = $('#pageRuleForm')[0];
      var formData = new FormData(form);
        $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        type: 'POST',
        data:formData,
        url: addRuleUrl,
        processData: false,
              contentType: false,
        success: function(response){
          $('#submitRuleBtn').html('Submit');
          $('#pageRuleForm')[0].reset();
          var obj = JSON.parse(response);
          if(obj['heading'] == "Success"){
            $('#addNewRCModal').modal('hide');
            getRules();
            swal("", obj['msg'], "success").then((value) => {
              window.location.assign(returnURL);
            });
          }else{
            swal("Error!", obj['msg'], "error");
            return false;
          }
        },error: function(ts) {
          $('#submitRuleBtn').html('Submit');
          swal("Error!", 'Some thing want to wrong, please try after sometime.', "error");
          return false;
        }
      }); 
    });


    $('#submitLandmarkBtn').click(function(e) {
      $('#submitLandmarkBtn').html('Processing...');

      var file = $('#l_icon')[0].files[0];

      var formData = new FormData();
      formData.append("icon", file);
      formData.append("category_id", $('#l_category_id').val());
      formData.append("hotel_id", $('#l_hotel_id').val());
      formData.append("title", $('#l_title').val());
      formData.append("description", $('#l_description').val());
      formData.append("old_icon", $('#l_old_icon').val());
      formData.append("row_id", $('#l_row_id').val());

      $.ajax({
      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      type: 'POST',
      data:formData,
      url: addLandmarkUrl,
      processData: false,
      contentType: false,
        success: function(response){
          $('#submitLandmarkBtn').html('Submit');
          $('#pageLandmarkForm')[0].reset();
          var obj = JSON.parse(response);
          if(obj['heading'] == "Success"){
            $('#addNewLandmarksModal').modal('hide');
            getLandmarks();
            swal("", obj['msg'], "success").then((value) => {
              window.location.assign(returnURL);
            });
          }else{
            swal("Error!", obj['msg'], "error");
            return false;
          }
        },error: function(ts) {
          $('#submitLandmarkBtn').html('Submit');
          swal("Error!", 'Some thing want to wrong, please try after sometime.', "error");
          return false;
        }
      }); 
    });
  });

function clearForm(){
  $('#pageFAQForm').modal('hide');
	$('#row_id').val(0);
	
}

function clearRuleForm(){
  $('#pageRuleForm').modal('hide');
	$('#row_id').val(0);
}
function clearLandmarksForm(){
  $('#pageLandmarksForm').modal('hide');
	$('#l_row_id').val(0);
  $('#l_title').val('');
	$('#l_category_id').val(1);
	$('#l_description').val('');
	$('#l_icon').val('');
}
function filterData(){
  getFaqs();
  getRules();
  getLandmarks();
}

function getFaqs(){
	$('#popupHeading').html('Edit FAQs');
	$.ajax({
      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      type: 'POST',
      data:{rowId:'{{$record->id}}'},
      url: getFaqUrl,
      success: function(response){
        $('#replaceHtml2').html(response);
      }
	});
}

function getLandmarks(){
	$('#popupLandmarksHeading').html('Edit Landmarks');
	$.ajax({
      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      type: 'POST',
      data:{rowId:'{{$record->id}}'},
      url: getLandmarksUrl,
      success: function(response){
        $('#replaceHtml5').html(response);
      }
	});
}

function getRules(){
	$('#popupRulesHeading').html('Edit Rules');
	$.ajax({
      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      type: 'POST',
      data:{rowId:'{{$record->id}}'},
      url: getRuleUrl,
      success: function(response){
        $('#replaceHtml3').html(response);
      }
	});
}

function getCities(){
  var state = $('#state option:selected').data('id');
  
  if(state > 0){
  $.ajax({
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    type: 'POST',
		data:{stateId:state},
		url: getUrl,
		success: function(response){
        $('#city').html(response).select2();
		}
	});
  }
}

function deleteImgData(table,rowID){
	if(table != "" && rowID != ""){
        swal({
        title: "Are you sure?",
        text: "Once deleted, you will not be able to recover this record!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
        })
        .then((willDelete) => {
        if (willDelete) {
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type: 'POST',
                url: "{{url('panel/delete-record')}}",
                data: {table:table,rowID:rowID},
                success: function(msg){
                    if(msg == "Success"){
                        swal({
                          title: 'Success',
                          text: 'Record has been deleted successfully.',
                          type: 'success',
                          confirmButtonText: 'Ok',
                          confirmButtonColor: "#009EF7"
                        });                        
                        window.location.href = '';
                    }else{
                        swal({
                            title: "Oops!",
                            text: msg,
                            type: "warning",
                            timer: 3000
                        });
                    }
                }
            });
        } else {
            swal("Your record is safe!");
        }
        });
	}else{
		return false;
	}
}
  
	$('#my-awesome-dropzone').attr('class', 'dropzone');
	var myDropzone = new Dropzone('#my-awesome-dropzone', {
		url: "{{url('panel/upload-product-images')}}",
		clickable: true,
		method: 'POST',
		maxFiles: 50,
		parallelUploads: 50,
		maxFilesize: 20,
		addRemoveLinks: false,
		dictRemoveFile: 'Remove',
		dictCancelUpload: 'Cancel',
		dictCancelUploadConfirmation: 'Confirm cancel?',
		dictDefaultMessage: 'Drop files here to upload',
		dictFallbackMessage: 'Your browser does not support drag n drop file uploads',
		dictFallbackText: 'Please use the fallback form below to upload your files like in the olden days',
		paramName: 'file',
		forceFallback: false,
		createImageThumbnails: true,
		maxThumbnailFilesize: 5,
    params: {'hotel_id':'{{$record->id}}'},
		//acceptedFiles: ".jpeg,.jpg,.webp,.png,.svg",
		acceptedFiles: "image/*",
		autoProcessQueue: true,
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		},
		init: function() {
			this.on('thumbnail', function(file) {
				if (file.width < 50 || file.height < 50) {
					//file.rejectDimensions();
					file.acceptDimensions();
				} else {
					file.acceptDimensions();
				}
			});
		},
		accept: function(file, done) {
			file.acceptDimensions = done;
			file.rejectDimensions = function() {
				done('The image must be at least 50 x 50px')
			};
		}
	});
	
	myDropzone.on("complete", function(file) {
		var status = file.status;
		if (status == 'success') {
	
		}
		console.log(file);
	});
	
	var count = 1;
	myDropzone.on("success", function(file, responseText) {
		var fnamenew = file.name;
		var fname = fnamenew.trim().replace(/["~!@#$%^&*\(\)_+=`{}\[\]\|\\:;'<>,.\/?"\- \t\r\n]+/g, '');
		$("#productsimgall").append('<input type="hidden" name="image[]" class="img_eng" id="img_eng' + fname + '" value="' + responseText + '">');
	   
		count++;
	});
	
	myDropzone.on("removedfile", function(file) {
		var fname = file.name;
		fname2 = fname.trim().replace(/["~!@#$%^&*\(\)_+=`{}\[\]\|\\:;'<>,.\/?"\- \t\r\n]+/g, '_');    
		var image = $('#img_eng'+fname2).val();
		
	});
	
	myDropzone.on("addedfile", function(file) {
			});
</script> 
@endsection