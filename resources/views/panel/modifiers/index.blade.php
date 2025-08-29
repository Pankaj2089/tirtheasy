@extends('layout.admin.dashboard')

@section('content')

            <div class="container-xxl flex-grow-1 container-p-y">
            <div class="card mb-2 p-3">
            <form id="searchForm" name="searchForm">
            <div class="row">
            <div class="col-md-2">
            <input type="text" class="form-control" name="search_title" placeholder="Enter User Name" id="search_title" />
            </div>
            <div class="col-md-2">
            <input type="text" class="form-control" name="search_email" placeholder="Enter Email" id="search_email" />
            </div>
            <div class="col-md-2">
            <input type="text" class="form-control" name="search_mobile" placeholder="Enter Mobile" id="search_mobile" />
            </div>
            <div class="col-md-2">
            <select name="search_status" class="form-select">
            <option value="">Status</option>
            <option value="1">Active</option>
            <option value="2">In-Active</option>
            </select>
            </div>
            <div class="col-md-1">
            <a style="color:#FFF" id="searchbuttons" onclick="filterData('search');" class="btn btn-primary waves-effect waves-light">Search</a>
            </div>
            <div class="col-md-1">
            <a style="color:#FFF" onclick="resetFilterForm();" class="btn btn-danger waves-effect waves-light">Reset</a>
            </div>
            </div>
            </form>
            </div>
              <!-- Basic Bootstrap Table -->
              <div class="card">
                <h5 class="card-header">Modifiers <a onclick="$('#popupHeading').html('Add Modifier');clearForm();" style="float:right; color:#FFF" data-bs-toggle="modal" data-bs-target="#addNewCCModal" class="btn btn-success waves-effect waves-light">Add</a></h5>
                
                <div class="table-responsive text-nowrap">
                  <table class="table">
                    <thead>
                      <tr>
                        <th>#ID</th>
                        <th>User Name</th>
                        <th>Email Address</th>
                        <th>Mobile</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody class="table-border-bottom-0" id="replaceHtml">
                      <tr>
                    <td colspan="10" class="text-center"><img src="{{ asset('public/admin/images/svg/oval.svg') }}" class="me-4" style="width: 3rem" alt="audio"></td>
                  </tr>
                      
                    </tbody>
                  </table>
                </div>
              </div>
              <!--/ Basic Bootstrap Table -->
            </div>
            
             <!-- Add New Credit Card Modal -->
              <div class="modal fade" id="addNewCCModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered1 modal-simple modal-add-new-cc">
                  <div class="modal-content">
                    <div class="modal-body">
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      <div class="text-center mb-6">
                        <h4 id="popupHeading" class="mb-2">Add Year</h4>
                        <!--<p>Create a brand new category from here.</p>-->
                      </div>
                      <form id="pageForm" class="row g-6" method="post">
                      <input type="hidden" id="row_id" name="row_id" value="0" />
                        <div class="col-12 form-control-validation">
                          <label class="form-label w-100" for="title">Name</label>
                          <div class="input-group-merge">
                            <input id="name" name="name" class="form-control credit-card-mask" placeholder="Please enter name" type="text" />
                          </div>
                        </div>
                        <div class="col-12 form-control-validation">
                          <label class="form-label w-100" for="title">Email Address</label>
                          <div class="input-group-merge">
                            <input id="email" name="email" class="form-control credit-card-mask" placeholder="Please enter email" type="text" />
                          </div>
                        </div>
                        <div class="col-12 form-control-validation">
                          <label class="form-label w-100" for="title">Mobile No</label>
                          <div class="input-group-merge">
                            <input id="mobile" name="mobile" class="form-control credit-card-mask" maxlength="10" placeholder="Please enter mobile" type="text" />
                          </div>
                        </div>
                        <div class="col-12 form-control-validation">
                          <label class="form-label w-100" for="title">Password</label>
                          <div class="input-group-merge">
                            <input id="password" name="password" class="form-control credit-card-mask" placeholder="Please enter password" type="text" />
                          </div>
                        </div>
                        
                        <div class="col-12 text-center">
                          <button type="button" id="submitBtn" class="btn btn-primary me-3">Submit</button>
                          <button type="reset" class="btn btn-label-secondary btn-reset" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
              <!--/ Add New Credit Card Modal -->

<script type="text/javascript">
var addUrl = "{{route('admin.add-modifier')}}";
var getUrl = "{{route('admin.get-modifier')}}";
$(document).ready(function(){
	filterData('simple');
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
					 $('#addNewCCModal').modal('hide');
					 filterData();
					swal("", obj['msg'], "success").then((value) => {
						window.location.assign(returnURL);
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
function getDetails(rowId){
	$('#popupHeading').html('Edit Modifier');
	$.ajax({
		headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
		type: 'POST',
		data:{rowId:rowId},
		url: getUrl,
		success: function(response){
			var obj = JSON.parse(response);
			if(obj['heading'] == "Success"){
				$('#row_id').val(obj['record']['id']);
				$('#name').val(obj['record']['name']);
				$('#email').val(obj['record']['email']);
				$('#mobile').val(obj['record']['mobile']);
			}else{
				swal("Error!", obj['msg'], "error");
				return false;
			}
		}
	});
}
function clearForm(){
	$('#row_id').val(0);
	$('#name').val('');
	$('#email').val('');
	$('#mobile').val('');
}
function filterData(type = null){
	if(type =='search'){$('#searchbuttons').html('Searching..');}
	$.ajax({
		headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
		type: 'POST',
		data: $('#searchForm').serialize(),
		url: "{{ url('/panel/modifiers_paginate') }}",
		success: function(response){
			$('#replaceHtml').html(response);
			$('#searchbuttons').html('Search');
		}
	});
}
</script> 
@endsection