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
        <input type="hidden" name="room_id" value="{{$record->room_id}}" />
        <input type="hidden" name="hotel_id" value="{{$record->hotel_id}}" />
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
        
                <div class="row g-12">
                  <div class="col-md-4">
                    <label class="form-label" for="prefix">Prefix</label>
                    <input type="text" id="prefix" name="prefix" class="form-control" value="{{$record->prefix}}" />
                  </div>
                  
                 <div class="col-md-4">
                    <label class="form-label" for="title">Room Number</label>
                    <input type="number" id="title" name="title" class="form-control" value="{{$record->title}}" />
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


<script type="text/javascript">
var addUrl = "{{url('/panel/edit-room-number/')}}/{{$record->id}}";

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
              window.location.href = "{{url('/panel/edit-room-number/')}}/{{$record->id}}";
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

</script> 
@endsection