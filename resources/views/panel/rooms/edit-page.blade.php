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
      <h6 class="mt-6">Edit Room</h6>
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
                <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#form-tabs-images" aria-controls="form-tabs-account"
role="tab" aria-selected="false"> <span class="icon-base ti tabler-user-cog icon-lg d-sm-none"></span><span class="d-none d-sm-block">Room Images</span> </button>
              </li>
              <li class="nav-item">
                <button type="button" onclick="getPrices()" class="nav-link" data-bs-toggle="tab" data-bs-target="#form-tabs-prices" aria-controls="form-tabs-account"
role="tab" aria-selected="false"> <span class="icon-base ti tabler-user-cog icon-lg d-sm-none"></span><span class="d-none d-sm-block">Room Prices</span> </button>
              </li>
            </ul>
          </div>
        </div>
        <div class="card-body">
          <div class="tab-content p-0">
            <div class="tab-pane fade active show" id="form-tabs-personal" role="tabpanel">
        
                <div class="row g-12">
                  <div class="col-md-8">
                    <label class="form-label" for="title">Room Name</label>
                    <input type="text" id="title" name="title" class="form-control" value="{{$record->title}}" />
                  </div>
                  
                 <div class="col-md-4">
                    <label class="form-label" for="price">Price</label>
                    <input type="number" id="price" name="price" class="form-control" value="{{$record->price}}" />
                  </div>
                  <div class="col-md-3">
                    <label class="form-label" for="no_of_rooms">No of Rooms</label>
                    <input type="number" id="no_of_rooms" name="no_of_rooms" class="form-control" value="{{$record->no_of_rooms}}" />
                  </div>
                  <div class="col-md-3">
                    <label class="form-label" for="no_of_guest">No of Guest</label>
                    <input type="number" id="no_of_guest" name="no_of_guest" class="form-control" value="{{$record->no_of_guest}}" />
                  </div>
                  <div class="col-md-3">
                    <label class="form-label" for="no_of_child">No of Child</label>
                    <input type="number" id="no_of_child" name="no_of_child" class="form-control" value="{{$record->no_of_child}}" />
                  </div>
                  
                  <div class="col-md-3">
                    <label class="form-label" for="cancellation_policy">Cancellation</label>
                    <select id="cancellation_policy" name="cancellation_policy" class="form-select" >
                        <option value="1" {{$record->cancellation_policy == 1 ? 'selected':''}}>Select</option>
                        <option value="2" {{$record->cancellation_policy == 2 ? 'selected':''}}>Cancellation</option>
                        <option value="3" {{$record->cancellation_policy == 3 ? 'selected':''}}>No Cancellation</option>
                    </select>
                  </div>


                  <div class="col-md-3">
                    <label class="form-label" for="no_of_single_rooms">No. of Single Rooms</label>
                    <input type="number" id="no_of_single_rooms" name="no_of_single_rooms" class="form-control" value="{{$record->no_of_single_rooms}}" />
                  </div>
                  <div class="col-md-3">
                    <label class="form-label" for="no_of_single_beds">No. of Single Beds</label>
                    <input type="number" id="no_of_single_beds" name="no_of_single_beds" class="form-control" value="{{$record->no_of_single_beds}}" />
                    <i style="font-size:11px">No. of beds in a room</i>
                  </div>
                  <div class="col-md-3">
                    <label class="form-label" for="no_of_guest_in_room">No of Adult</label>
                    <input type="number" id="no_of_guest_in_room" name="no_of_guest_in_room" class="form-control" value="{{$record->no_of_guest_in_room}}" />
                  </div>
                  <div class="col-md-3">
                    <label class="form-label" for="no_of_child_in_room">No of Child</label>
                    <input type="number" id="no_of_child_in_room" name="no_of_child_in_room" class="form-control" value="{{$record->no_of_child_in_room}}" />
                  </div>



                   <div class="col-md-3">
                    <label class="form-label" for="no_of_double_rooms">No. of Double Rooms</label>
                    <input type="number" id="no_of_double_rooms" name="no_of_double_rooms" class="form-control" value="{{$record->no_of_double_rooms}}" />
                  </div>
                  <div class="col-md-3">
                    <label class="form-label" for="no_of_double_beds">No. of Double Beds</label>
                    <input type="number" id="no_of_double_beds" name="no_of_double_beds" class="form-control" value="{{$record->no_of_double_beds}}" />
                    <i style="font-size:11px">No. of beds in a room</i>
                  </div>
                  <div class="col-md-3">
                    <label class="form-label" for="no_of_guest_in_double_room">No of Adult</label>
                    <input type="number" id="no_of_guest_in_double_room" name="no_of_guest_in_double_room" class="form-control" value="{{$record->no_of_guest_in_double_room}}" />
                  </div>
                  <div class="col-md-3">
                    <label class="form-label" for="no_of_child_in_double_room">No of Child</label>
                    <input type="number" id="no_of_child_in_double_room" name="no_of_child_in_double_room" class="form-control" value="{{$record->no_of_child_in_double_room}}" />
                  </div>


                   
                  <div class="col-md-3">
                    <label class="form-label" for="no_of_child">Extra Mattress</label>
                    <select id="extra_mattress" name="extra_mattress" class="form-select" onchange="updateMattress();" >
                        <option value="0" {{$record->extra_mattress == 0 ? 'selected':''}}>No</option>
                        <option value="1" {{$record->extra_mattress == 1 ? 'selected':''}}>Yes</option>
                    </select>
                  </div>
                  <div class="col-md-3 {{ $record->extra_mattress != 1 ? 'd-none':''  }}" id="extra_mattress_price_div">
                    <label class="form-label" for="extra_mattress_price">Extra Mattress Price</label>
                    <input type="number" id="extra_mattress_price" name="extra_mattress_price" class="form-control" value="{{$record->extra_mattress_price}}" />
                  </div>
                  <div class="col-md-3">
                    <label class="form-label" for="room_size">Room Size</label>
                    <input type="text" id="room_size" name="room_size" class="form-control"  value="{{$record->room_size}}"  />
                  </div>

                  <div class="col-md-12">
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
                  <div class="col-md-4">
                    <label class="form-label" for="banner">Main Image</label>
                    <input type="file" id="banner" name="banner" class="form-control" />
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
                                            <label for="basicInput">Upload Room Images</label>
                                              <div id="my-awesome-dropzone" class="dropzone"></div>
                                          </div>
                                          @if(isset($room_images) && count($room_images) > 0)
                                          <div class="col-12 mt-4 mb-4"><label for="basicInput">Room Images</label></div>
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
                                              @foreach($room_images as $key => $value)
                                              <tr>
                                                <td>{{ $key+1; }}</td>
                                                <td>
                                                  <a target="_blank" href="{{ URL::asset('public/img/rooms/') }}/{!! $value->image !!}" data-sub-html="Image"> 
                                                  <img width="100px" class="img-responsive" src="{{ URL::asset('public/img/rooms/') }}/{!! $value->image !!}"> 
                                                  </a>
                                                </td>
                                                <td> 
                                                   @php
                                                    if($value->status == 1){$class = 'bg-label-success'; $label = 'Active';}else{$class = 'bg-label-danger'; $label = 'In-Active';}
                                                    @endphp
                                                    <a style="cursor:pointer" onclick="changeStatus('room_images','{!!$value->id!!}');" id="status_{{$value->id}}" class="badge {{$class}} me-1">{{$label}}</a>
                                                    <input type="hidden" id="status_value_{{$value->id}}" value="{!!$value->status!!}" />
                                                  </td>
                                                <td><a href="javascript:void(0);" onclick="deleteImgData('room_images','{{ $value->id }}');" class="btn btn-sm btn-danger"  title="Delete">
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

             <div class="tab-pane fade" id="form-tabs-prices" role="tabpanel">
              <div class="row">
                    <div class="col-md-12">
                    <form class="form w-100" id="pageForm" action="#">
                      <div class="row">
                          <div class="col-12 col-md-12">
                              <div class="card">
                                  <div class="card-body"> 
                                      <div class="row">
                                         <h5 class="card-header p-0 mb-4">Prices <a  style="float:right; color:#FFF" data-bs-toggle="modal"
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

        </div>
        </form>
      </div>
      </div>
      </div>
        <div class="col-12 col-md-3">
            <div class="card mb-6">
                <div class="card-body">
                <div class="row g-6">
                @if($record->image != '')
                  <div class="col-md-12">
                    <img src="{{$siteUrl}}/public/img/rooms/{{$record->image}}" width="100%" />
                  </div>
                  @endif
                </div>
                    <div class="pt-6">
                        <button  type="button" id="submitBtn" class="btn btn-primary me-4">Submit</button>
                        <button type="reset" onclick="window.location.href='{{url('/panel/rooms/'.$record->hotel_id)}}'" class="btn btn-label-secondary" >Cancel</button>
                    </div>
                </div>
            </div>
        </div>
        </div>
      
    </div>
  </div>
</div>

<!-- Add New Rules Modal -->
    <div class="modal fade" id="addNewCCModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered1 modal-simple modal-add-new-cc">
        <div class="modal-content">
          <div class="modal-body">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="text-center mb-6">
              <h4 id="popupRulesHeading" class="mb-2">Add New Price</h4>
              <!--<p>Create a brand new category from here.</p>-->
            </div>
            <form id="pagePriceForm" class="row g-6" method="post">
              <input id="hotel_id" name="hotel_id"  value="{{$record->hotel_id}}" type="hidden" />
              <input id="room_id" name="room_id"  value="{{$record->id}}" type="hidden" />
            <input type="hidden" id="row_id" name="row_id" value="0" />
              <div class="col-12 form-control-validation">
                <label class="form-label w-100" for="title">Price</label>
                <div class="input-group-merge">
                  <input id="price" name="price" class="form-control credit-card-mask" placeholder="Please enter price" type="number" aria-describedby="price" />
                </div>
              </div>
             <div class="col-12 form-control-validation">
                <label class="form-label w-100" for="title">No of Guest</label>
                <div class="input-group-merge">
                  <input id="no_of_guest" name="no_of_guest" class="form-control credit-card-mask" placeholder="Please enter No of Guest" type="number" aria-describedby="no_of_guest" />
                </div>
              </div>
              <div class="col-12 form-control-validation">
                <label class="form-label w-100" for="title">No of Child</label>
                <div class="input-group-merge">
                  <input id="no_of_child" name="no_of_child" class="form-control credit-card-mask" placeholder="Please enter No of Guest" type="number" aria-describedby="no_of_child" />
                </div>
              </div>
              <!-- <div class="col-12 form-control-validation">
                <label class="form-label w-100" for="title">No of Rooms</label>
                <div class="input-group-merge">
                  <input id="no_of_rooms" name="no_of_rooms" class="form-control credit-card-mask" placeholder="Please enter no of rooms" type="number" aria-describedby="no_of_rooms" />
                </div>
              </div> -->
              <div class="col-12 form-control-validation Amenities">
                    <label class="form-label" for="facilities">Amenities</label>
                    <div class="col-12 form-control-validation">
                    <select type="text" id="amenities" name="amenities[]" class="form-select" multiple >
                        @foreach($amenities as $amenities)
                          <option value="{{$amenities->title}}">{{$amenities->title}}</option>
                        @endforeach
                    </select>
                  </div>
                  </div>
              <div class="col-12 text-center">
                <button type="button" id="submitPriceBtn" class="btn btn-primary me-3">Submit</button>
                <button type="reset" class="btn btn-label-secondary btn-reset" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>


<script type="text/javascript">
var addUrl = "{{url('/panel/edit-room/')}}/{{$record->id}}";

var addPriceUrl = "{{route('admin.add-room-price')}}";
var getPriceUrl = "{{route('admin.get-room-price')}}";

function updateMattress(){
  var extra_mattress = $('#extra_mattress').val();
  $('#extra_mattress_price_div').addClass('d-none');
    $('#extra_mattress_price').val(0);
  if(extra_mattress == 1){
    $('#extra_mattress_price_div').removeClass('d-none');
  }
}

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
              window.location.href = "{{url('/panel/edit-room/')}}/{{$record->id}}";
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

     $('#submitPriceBtn').click(function(e) {
      $('#submitPriceBtn').html('Processing...');
      var form = $('#pagePriceForm')[0];
      var formData = new FormData(form);
        $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        type: 'POST',
        data:formData,
        url: addPriceUrl,
        processData: false,
              contentType: false,
        success: function(response){
          $('#submitPriceBtn').html('Submit');
          var obj = JSON.parse(response);
          if(obj['heading'] == "Success"){
            $('#addNewCCModal').modal('hide');
            $('#pagePriceForm')[0].reset();
            getPrices();
            swal("", obj['msg'], "success").then((value) => {
              window.location.assign(returnURL);
            });
          }else{
            swal("Error!", obj['msg'], "error");
            return false;
          }
        },error: function(ts) {
          $('#submitPriceBtn').html('Submit');
          swal("Error!", 'Some thing want to wrong, please try after sometime.', "error");
          return false;
        }
      }); 
    });

  });

  function getPrices(){
	$.ajax({
      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      type: 'POST',
      data:{rowId:'{{$record->id}}'},
      url: getPriceUrl,
      success: function(response){
        $('#replaceHtml2').html(response);
      }
	});
}

function filterData(){
  getPrices();
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
		url: "{{url('panel/upload-room-images')}}",
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
    params: {'room_id':'{{$record->id}}'},
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