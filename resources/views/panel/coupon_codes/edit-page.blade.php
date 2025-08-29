@extends('layout.admin.dashboard')

@section('content')

<div class="container-xxl flex-grow-1 container-p-y">
  <div class="row">
    <div class="col">
      <h6 class="mt-6">Edit Coupon Code</h6>
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
                     <label for="basicInput">Coupon Code</label>
                     <input type="text" class="form-control" maxlength="8" value="{{$rowData->title}}" style="text-transform:uppercase" name="title" id="title">
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group">
                     <label for="basicInput">Discount Type</label>
                        <select class="form-select" name="discount_type" id="discount_type">
                           <option value="">Select Discount Type</option>
                           <option @if($rowData->discount_type == 'Amount') selected @endif value="Amount">Amount</option>
                           <option @if($rowData->discount_type == 'Percent') selected @endif value="Percent">Percent</option>
                        </select>
                     </div>
                  </div>

                  
                  <div class="col-md-4">
                     <div class="form-group">
                        <label for="basicInput">Amount</label>
                        <input type="text" class="form-control numberonly" value="{{$rowData->amount}}" maxlength="6" name="amount" id="amount">
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group">
                        <label for="basicInput">Start Date</label>
                        <input type="date" class="form-control" name="start_date" value="{{$rowData->start_date}}" id="start_date">
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group">
                        <label for="basicInput">End Date</label>
                        <input type="date" class="form-control" name="end_date" value="{{$rowData->end_date}}" id="end_date">
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
        <div class="pt-6">
        <button  type="button" id="submitBtn" class="btn btn-primary me-4">Submit</button>
        <button type="reset" onclick="window.location.href='{{route('admin.coupon-codes')}}'" class="btn btn-label-secondary" >Cancel</button>
        </div>
        </div>
        </div>
        </div>
        </div>
      
    </div>
  </div>
</div>
<!-- end plugin js --> 

<script>
    let saveDataURL = "{{url('/panel/edit-coupon-code/'.$row_id)}}";
    $(document).ready(function(){
      $('.numberonly').keypress(function(e){
    var charCode = (e.which) ? e.which : event.keyCode   
    if(String.fromCharCode(charCode).match(/[^0-9+.]/g))   
    return false;   
   });

	$('#submitBtn').click(function(e) {
		$('#submitBtn').html('Processing...');
		var form = $('#pageForm')[0];
		var formData = new FormData(form);
       $.ajax({
			headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
			type: 'POST',
			data:formData,
			url: saveDataURL,
			processData: false,
            contentType: false,
			success: function(response){
				$('#submitBtn').html('Submit');
				var obj = JSON.parse(response);
				 if(obj['heading'] == "Success"){
					
					swal("", obj['msg'], "success").then((value) => {
						window.location.href = "{{route('admin.coupon-codes')}}";
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