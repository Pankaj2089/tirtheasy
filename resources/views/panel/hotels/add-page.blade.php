@extends('layout.admin.dashboard')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  <div class="row">
    <div class="col">
      <h6 class="mt-6">Add Hotel</h6>
      <div class="row">
      <div class="col-12 col-md-9">
      <div class="card mb-6">
      	<form id="pageForm" enctype="multipart/form-data" method="post">
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
                    <input type="text" id="title" name="title" class="form-control" />
                  </div>
                  <div class="col-md-4">
                    <label class="form-label" for="hotel_type">Hotel Type</label>
                    <select type="text" id="hotel_type" name="hotel_type" class="form-select" >
                        <option value="hotel" selected>Hotel</option>
                        <option value="dharmshala">Dharmshala</option>
                    </select>
                  </div>
                  <div class="col-md-3">
                    <label class="form-label" for="state">State</label>
                    <select type="text" id="state" name="state" class="form-select select2" onchange="getCities()" >
                        <option value="">Select</option>
                        @foreach($states as $state)
                          <option value="{{$state->title}}" data-id="{{$state->id}}">{{$state->title}}</option>
                        @endforeach
                    </select>
                  </div>
                  <div class="col-md-3">
                    <label class="form-label" for="city">City</label>
                    <select type="text" id="city" name="city" class="form-select" >
                        <option value="">Select</option>
                    </select>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label" for="address">Address</label>
                    <input type="text" id="address" name="address" class="form-control" />
                  </div>
                  <div class="col-md-3">
                    <label class="form-label" for="price">Price</label>
                    <input type="number" id="price" name="price" class="form-control" />
                  </div>
                  <div class="col-md-3">
                    <label class="form-label" for="cancellation_policy">Cancellation</label>
                    <select id="cancellation_policy" name="cancellation_policy" class="form-select" >
                      <option value="1">Select</option>
                      <option value="2">Cancellation</option>
                      <option value="3">No Cancellation</option>
                    </select>
                    </div>
                    
                  <div class="col-md-6">
                    <label class="form-label" for="amenities">Amenities</label>
                    <select type="text" id="amenities" name="amenities[]" class="form-select select2" multiple >
                        <option value="">Select</option>
                        @foreach($amenities as $amenitycat)
                          @if(isset($amenitycat->amenities) && count($amenitycat->amenities) > 0)
                          <optgroup label="{{$amenitycat->title}}">
                              @foreach($amenitycat->amenities as $amenity)
                              <option value="{{$amenity->id}}">{{$amenity->title}}</option>
                              @endforeach
                          </optgroup>
                          @endif
                        @endforeach
                    </select>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label" for="facilities">Facilities</label>
                    <select type="text" id="facilities" name="facilities[]" class="form-select select2" multiple >
                        <option value="">Select</option>
                        @foreach($facilities as $facility)
                          <option value="{{$facility->id}}">{{$facility->title}}</option>
                        @endforeach
                    </select>
                  </div>
                  <div class="col-md-3">
                    <label class="form-label" for="check_in_time">Check In</label>
                    <input type="time" id="check_in_time" name="check_in_time" class="form-control" />
                  </div>
                  <div class="col-md-3">
                    <label class="form-label" for="check_out_time">Check Out</label>
                    <input type="time" id="check_out_time" name="check_out_time" class="form-control" />
                  </div>
                  <div class="col-md-4">
                    <label class="form-label" for="banner">Main Image</label>
                    <input type="file" id="banner" name="banner" class="form-control" />
                  </div>         
                
                 <div class="col-md-4">
                    <label class="form-label" for="latitude">Latitude</label>
                    <input type="text" id="latitude" name="latitude" class="form-control" />
                  </div>
                  <div class="col-md-4">
                  <label class="form-label" for="longitude">Longitude</label>
                  <input type="text" id="longitude" name="longitude" class="form-control" />
                </div>
                <div class="col-md-4">
                  <label class="form-label" for="is_only_jain">Only For Jain</label>
                  <select id="is_only_jain" name="is_only_jain" class="form-select" >
                      <option value="2">No</option>
                      <option value="1">Yes</option>
                    </select>
                </div>
                </div>
            </div>
            <div class="tab-pane fade" id="form-tabs-information" role="tabpanel">
                <div class="row g-6">
                  <div class="col-md-12">
                    <label class="form-label" for="description">Policy</label>
                    <textarea class="form-control editor"  rows="10" id="policy" name="policy"></textarea>
                  </div> 
                  <div class="col-md-12">
                    <label class="form-label" for="description">Description</label>
                    <textarea class="form-control editor" rows="10" id="description" name="description"></textarea>
                  </div> 

                <div class="col-md-12">
                    <label class="form-label" for="description">Special Note</label>
                    <textarea class="form-control editor"  rows="10" id="special_note" name="special_note"></textarea>
                  </div> 
                  <div class="col-md-12">
                    <label class="form-label" for="description">Extra Bed Policy</label>
                    <textarea class="form-control editor" rows="10" id="extra_bed_policy" name="extra_bed_policy"></textarea>
                  </div>  
                    
                  <div class="col-md-12">
                    <label class="form-label" for="description">Check In Restrictions</label>
                    <textarea class="form-control editor" rows="10" id="check_in_restrictions" name="check_in_restrictions"></textarea>
                  </div>
                  <div class="col-md-12">
                    <label class="form-label" for="description">Food Arrangement</label>
                    <textarea class="form-control editor" rows="10" id="food_arrangement" name="food_arrangement"></textarea>
                  </div>
                  <div class="col-md-12">
                    <label class="form-label" for="description">Id Proof Rrelated</label>
                    <textarea class="form-control editor" rows="10" id="id_proof_related" name="id_proof_related"></textarea>
                  </div>
                  <div class="col-md-12">
                    <label class="form-label" for="description">Property Accessibility</label>
                    <textarea class="form-control editor" rows="10" id="property_accessibility" name="property_accessibility"></textarea>
                  </div>
                  <div class="col-md-12">
                    <label class="form-label" for="description">Pet(s) Related</label>
                    <textarea class="form-control editor" rows="10" id="pet_related" name="pet_related"></textarea>
                  </div>
                  <div class="col-md-12">
                    <label class="form-label" for="description">Other Rules</label>
                    <textarea class="form-control editor" rows="10" id="other_rules" name="other_rules"></textarea>
                  </div>
                                   
                </div>

            </div>
            <div class="tab-pane fade" id="form-tabs-account" role="tabpanel">
                <div class="row g-6">
                  <div class="col-md-12">
                    <label class="form-label" for="seo_title">SEO Title</label>
                    <input type="text" id="seo_title" name="seo_title" class="form-control"/>
                  </div>
                  <div class="col-md-12">
                    <label class="form-label" for="seo_description">SEO Description</label>
                    <textarea class="form-control" id="seo_description" name="seo_description"></textarea>
                  </div>
                  <div class="col-md-12">
                    <label class="form-label" for="seo_keyword">SEO Keywords</label>
                    <textarea class="form-control" id="seo_keyword" name="seo_keyword"></textarea>
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
<script type="text/javascript">
  
var getUrl = "{{route('admin.get-cities')}}";
var addUrl = "{{route('admin.add-hotel')}}";
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
						window.location.href = "{{route('admin.hotels')}}";
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
});
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
</script> 
@endsection