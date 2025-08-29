@extends('layout.admin.dashboard')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  <div class="row">
    <div class="col">
      <h6 class="mt-6">Add Room</h6>
      <div class="row">
      <div class="col-12 col-md-9">
      <div class="card mb-6">
      	<form id="pageForm" enctype="multipart/form-data" method="post">
          <input type="hidden" id="hotel_id" name="hotel_id" value="{{$hotel_id}}" class="form-control" />
        <div class="card-header px-0 pt-0">
          <div class="nav-align-top">
            <ul class="nav nav-tabs" role="tablist">
              <li class="nav-item">
                <button type="button" class="nav-link active" data-bs-toggle="tab" data-bs-target="#form-tabs-personal" aria-controls="form-tabs-personal"
role="tab" aria-selected="true"> <span class="icon-base ti tabler-user icon-lg d-sm-none"></span><span class="d-none d-sm-block">General Info</span> </button>
              </li>
            </ul>
          </div>
        </div>
        <div class="card-body">
          <div class="tab-content p-0">
            <div class="tab-pane fade active show" id="form-tabs-personal" role="tabpanel">
                <div class="row g-6">
                  <div class="col-md-8">
                    <label class="form-label" for="title">Room Name</label>
                    <input type="text" id="title" name="title" class="form-control" />
                  </div>
                  <div class="col-md-4">
                    <label class="form-label" for="price">Price</label>
                    <input type="number" id="price" name="price" class="form-control" />
                  </div>
                  <div class="col-md-3">
                    <label class="form-label" for="no_of_rooms">No of Rooms</label>
                    <input type="number" id="no_of_rooms" name="no_of_rooms" class="form-control" />
                  </div>
                  <div class="col-md-3">
                    <label class="form-label" for="no_of_guest">No of Guest</label>
                    <input type="number" id="no_of_guest" name="no_of_guest" class="form-control" />
                  </div>
                   <div class="col-md-3">
                    <label class="form-label" for="no_of_child">No of Child</label>
                    <input type="number" id="no_of_child" name="no_of_child" class="form-control" />
                  </div>
                  <div class="col-md-3">
                    <label class="form-label" for="cancellation_policy">Cancellation</label>
                    <select id="cancellation_policy" name="cancellation_policy" class="form-select" >
                      <option value="1">Select</option>
                      <option value="2">Cancellation</option>
                      <option value="3">No Cancellation</option>
                    </select>
                    </div>

                  <div class="col-md-3">
                    <label class="form-label" for="no_of_single_rooms">No. of Single Rooms</label>
                    <input type="number" id="no_of_single_rooms" name="no_of_single_rooms" class="form-control" />
                  </div>
                  <div class="col-md-3">
                    <label class="form-label" for="no_of_single_beds">No. of Single Beds</label>
                    <input type="number" id="no_of_single_beds" name="no_of_single_beds" class="form-control" />
                    <i style="font-size:11px">No. of beds in a room</i>
                  </div>
                  <div class="col-md-3">
                    <label class="form-label" for="no_of_guest_in_room">No of Adult</label>
                    <input type="number" id="no_of_guest_in_room" name="no_of_guest_in_room" class="form-control" value="" />
                  </div>
                  <div class="col-md-3">
                    <label class="form-label" for="no_of_child_in_room">No of Child</label>
                    <input type="number" id="no_of_child_in_room" name="no_of_child_in_room" class="form-control" value="" />
                  </div>



                   <div class="col-md-3">
                    <label class="form-label" for="no_of_double_rooms">No. of Double Rooms</label>
                    <input type="number" id="no_of_double_rooms" name="no_of_double_rooms" class="form-control" />
                  </div>
                  <div class="col-md-3">
                    <label class="form-label" for="no_of_double_beds">No. of Double Beds</label>
                    <input type="number" id="no_of_double_beds" name="no_of_double_beds" class="form-control" />
                    <i style="font-size:11px">No. of beds in a room</i>
                  </div>
                  <div class="col-md-3">
                    <label class="form-label" for="no_of_guest_in_double_room">No of Adult</label>
                    <input type="number" id="no_of_guest_in_double_room" name="no_of_guest_in_double_room" class="form-control" value="" />
                  </div>
                  <div class="col-md-3">
                    <label class="form-label" for="no_of_child_in_double_room">No of Child</label>
                    <input type="number" id="no_of_child_in_double_room" name="no_of_child_in_double_room" class="form-control" value="" />
                  </div>
                  

                    <div class="col-md-3">
                    <label class="form-label" for="no_of_child">Extra Mattress</label>
                    <select id="extra_mattress" name="extra_mattress" class="form-select" onchange="updateMattress();" >
                        <option value="0">No</option>
                        <option value="1">Yes</option>
                    </select>
                  </div>
                  <div class="col-md-3 d-none" id="extra_mattress_price_div">
                    <label class="form-label" for="extra_mattress_price">Extra Mattress Price</label>
                    <input type="number" id="extra_mattress_price" name="extra_mattress_price" class="form-control" value="" />
                  </div>

                  <div class="col-md-3">
                    <label class="form-label" for="room_size">Room Size</label>
                    <input type="text" id="room_size" name="room_size" class="form-control" />
                  </div>

                  <div class="col-md-12">
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
                  <div class="col-md-4">
                    <label class="form-label" for="banner">Main Image</label>
                    <input type="file" id="banner" name="banner" class="form-control" />
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
        <button type="reset" onclick="window.location.href='{{url('/panel/rooms/'.$hotel_id)}}'" class="btn btn-label-secondary" >Cancel</button>
        </div>
        </div>
        </div>
        </div>
        </div>
        </div>
      
    </div>
  </div>
</div>
<script type="text/javascript">

var addUrl = "{{url('/panel/add-room/'.$hotel_id)}}";
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
						window.location.href = "{{url('/panel/edit-room')}}/"+obj['recordID'];
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

function updateMattress(){
  var extra_mattress = $('#extra_mattress').val();
  $('#extra_mattress_price_div').addClass('d-none');
    $('#extra_mattress_price').val(0);
  if(extra_mattress == 1){
    $('#extra_mattress_price_div').removeClass('d-none');
  }
}

</script> 
@endsection