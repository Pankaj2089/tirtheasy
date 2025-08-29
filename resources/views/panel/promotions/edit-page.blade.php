@extends('layout.admin.dashboard')
@php
$siteUrl = env('APP_URL');
@endphp
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  <div class="row">
    <div class="col">
      <h6 class="mt-6">Edit Promotion</h6>
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
            </ul>
          </div>
        </div>
        <div class="card-body">
          <div class="tab-content p-0">
            <div class="tab-pane fade active show" id="form-tabs-personal" role="tabpanel">
                <div class="row g-6">
              	<div class="col-md-6">
                  <div class="form-group">
                    <label for="basicInput">Title</label>
                    <input type="text" class="form-control" placeholder="Enter Title" value="{!! $rowData->heading !!}" name="heading" id="heading">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="basicInput">Promotion For</label>
                    <select type="text" class="form-select" placeholder="Enter Title" name="promotion_for" id="promotion_for">
                      <option value="hotel" {{ $rowData->promotion_for == "hotel" ?'selected':'' }}>Hotel</option>
                      <option value="dharmshala" {{ $rowData->promotion_for == "dharmshala" ?'selected':'' }}>Dharmshala</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="basicInput">Promotion Type</label>
                    <select type="text" class="form-select" placeholder="Enter Title" name="promotion_type" id="promotion_type">
                      <option value="1" {{ $rowData->promotion_type == 1 ?'selected':'' }}>Promotions on Flights, Tours & Tickets</option>
                      <option value="2" {{ $rowData->promotion_type == 2 ?'selected':'' }}>Accommodation Promotions</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="basicInput">promotion</label>
                    <input type="file" class="form-control" name="banner" id="banner" accept="image/*">
                  </div>
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
          @if($rowData->banner != '')
            <div class="col-md-12">
              <img src="{{$siteUrl}}/public/img/promotions/{{$rowData->banner}}" width="100%" />
            </div>
            @endif
          </div>
        <div class="pt-6">
        <button  type="button" id="submitBtn" class="btn btn-primary me-4">Submit</button>
        <button type="reset" onclick="window.location.href='{{route('admin.promotions')}}'" class="btn btn-label-secondary" >Cancel</button>
        </div>
        </div>
        </div>
        </div>
        </div>
      
    </div>
  </div>
</div>

<script>
    let addUrl = "{{url('/panel/edit-promotion/'.$row_id)}}";
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
              window.location.href = "{{url('/panel/edit-promotion/')}}/{{$rowData->id}}";
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