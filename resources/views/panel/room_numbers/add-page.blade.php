@extends('layout.admin.dashboard')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  <div class="row">
    <div class="col">
      <h6 class="mt-6">Add Room Number</h6>
      <div class="row">
      <div class="col-12 col-md-9">
      <div class="card mb-6">
      	<form id="pageForm" enctype="multipart/form-data" method="post">
          <input type="hidden" id="hotel_id" name="hotel_id" value="{{$hotel_id}}" class="form-control" />
          <input type="hidden" id="room_id" name="room_id" value="{{$room_id}}" class="form-control" />
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
                    <div class="col-md-4">
                      <select type="text" id="type" name="type" class="form-control form-select" onchange="changeType()" >
                          <option value="bulk_add">Bulk Add</div>
                          <option value="manually_add">Manually Add</div>
                      </select>
                    </div>
                  </div>

                  <div class="row g-6 mt-4 actions" id="bulk_add">
                    <div class="col-md-4">
                      <label class="form-label" for="prefix">prefix</label>
                      <input type="text" id="prefix" name="prefix" class="form-control" />
                    </div>
                    <div class="col-md-4">
                      <label class="form-label" for="title">From</label>
                      <input type="number" id="from" name="from" min="1" class="form-control" />
                    </div>
                    <div class="col-md-4">
                      <label class="form-label" for="prictoe">To</label>
                      <input type="number" id="to" name="to" min="1" class="form-control" />
                    </div>
                </div>
                <div class="d-none actions mt-4" id="manually_add">
                  <div class="row g-6 mb-2" id="row_0">
                      <div class="col-md-4">
                        <label class="form-label" for="prefix_0">prefix</label>
                        <input type="text" id="prefix_0" name="prefix_bulk[]" class="form-control" />
                      </div>
                      <div class="col-md-4">
                        <label class="form-label" for="title">Room Number</label>
                        <input type="number" id="from_0" name="room_number[]" min="1" class="form-control" />
                      </div>
                      <div class="col-md-4 d-flex align-items-end">
                        <button type="button" onclick="addMore()" class="btn btn-label-secondary" >Add More</button>
                      </div>
                  </div>

                  <div id="more_rows"></div>

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

  function changeType(){
    var type = $("#type").val();
    $('.actions').addClass('d-none');
    $('#'+type).removeClass('d-none');
  }

  let rowCount = 0;

  function addMore() {
    rowCount++;

    let newRow = `
      <div class="row g-6 mb-2" id="row_${rowCount}">
        <div class="col-md-4">
          <label class="form-label" for="prefix">Prefix</label>
          <input type="text" id="prefix_${rowCount}" name="prefix[]" class="form-control" />
        </div>
        <div class="col-md-4">
          <label class="form-label" for="title">Room Number</label>
          <input type="number" id="from_${rowCount}" name="room_number[]" min="1" class="form-control" />
        </div>
        <div class="col-md-4 d-flex align-items-end">
          <button type="button" class="btn btn-danger" onclick="removeRow(${rowCount})">Remove</button>
        </div>
      </div>
    `;

    $('#more_rows').append(newRow);
  }

  function removeRow(id) {
    $('#row_' + id).remove();
  }

var addUrl = "{{url('/panel/add-room-number/'.$hotel_id)}}";
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
						window.location.href = "{{url('/panel/room-numbers')}}/{{$room_id}}";
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