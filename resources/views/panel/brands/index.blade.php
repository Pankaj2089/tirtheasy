@extends('layout.admin.dashboard')

@section('content')

            <div class="container-xxl flex-grow-1 container-p-y">
            <div class="card mb-2 p-3">
            <form id="searchForm" name="searchForm">
            <div class="row">
            <div class="col-md-2">
            <input type="text" class="form-control" name="search_title" placeholder="Enter Title" id="defaultFormControlInput" />
            </div>
            <div class="col-md-2">
            <select name="search_type" class="form-select">
                <option value="">Type</option>
                <option value="Vehicle">Vehicle</option>
                <option value="Accessories">Accessories</option>
            </select>
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
                <h5 class="card-header">Brands <a onclick="$('#popupHeading').html('Add New Brand');clearForm();" style="float:right; color:#FFF" data-bs-toggle="modal" data-bs-target="#addNewCCModal" class="btn btn-success waves-effect waves-light">Add</a></h5>
                
                <div class="table-responsive text-nowrap">
                  <table class="table">
                    <thead>
                      <tr>
                        <th>#ID</th>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Banner</th>
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
                        <h4 id="popupHeading" class="mb-2">Add New Category</h4>
                        <!--<p>Create a brand new category from here.</p>-->
                      </div>
                      <form id="pageForm" class="row g-6" method="post" enctype="multipart/form-data">
                      <input type="hidden" id="row_id" name="row_id" value="0" />
                      <input type="hidden" id="old_banner" name="old_banner" />
                      
                        <div class="col-6 form-control-validation">
                          <label class="form-label w-100" for="title">Title</label>
                          <div class="input-group-merge">
                            <input id="title" name="title" class="form-control credit-card-mask" placeholder="Please enter title" type="text" aria-describedby="title2" />
                          </div>
                        </div>
                        
                        <div class="col-6 form-control-validation">
                          <label class="form-label w-100" for="type">Type</label>
                          <div class="input-group-merge">
                            <select id="type" name="type" class="form-select">
                            <option value="">Select</option>
                            <option value="Vehicle">Vehicle</option>
                            <option value="Accessories">Accessories</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-12 form-control-validation">
                          <label class="form-label w-100" for="title">Banner</label>
                          <div class="input-group-merge">
                            <input id="banner" name="banner" class="form-control" type="file"  />
                          </div>
                        </div>
                        <div class="col-12 form-control-validation">
                          <label class="form-label w-100" for="seo_title">SEO Title</label>
                          <div class="input-group-merge">
                            <input id="seo_title" name="seo_title" class="form-control" placeholder="Please enter title" type="text"  />
                          </div>
                        </div>
                        <div class="col-12 form-control-validation">
                          <label class="form-label w-100" for="seo_description">SEO Description</label>
                          <div class="input-group-merge">
                            <textarea id="seo_description" name="seo_description" class="form-control" ></textarea>
                          </div>
                        </div>
                        <div class="col-12 form-control-validation">
                          <label class="form-label w-100" for="seo_keyword">SEO Keywords</label>
                          <div class="input-group-merge">
                            <textarea id="seo_keyword" name="seo_keyword" class="form-control" ></textarea>
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
var addUrl = "{{route('admin.add-brand')}}";
var getUrl = "{{route('admin.get-brand')}}";
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
	$('#popupHeading').html('Edit Brand');
	$.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        type: 'POST',
		data:{rowId:rowId},
		url: getUrl,
		success: function(response){
			var obj = JSON.parse(response);
			if(obj['heading'] == "Success"){
				$('#row_id').val(obj['record']['id']);
				$('#title').val(obj['record']['title']);
				$('#type').val(obj['record']['type']);
				$('#seo_title').val(obj['record']['seo_title']);
				$('#seo_description').val(obj['record']['seo_description']);
				$('#seo_keyword').val(obj['record']['seo_keyword']);
				$('#old_banner').val(obj['record']['banner']);
			}else{
				swal("Error!", obj['msg'], "error");
				return false;
			}
		}
	});
}
function clearForm(){
	$('#row_id').val(0);
	$('#title').val('');
	$('#type').val('');
	$('#seo_title').val('');
	$('#seo_description').val('');
	$('#seo_keyword').val('');
	$('#banner').val('');
}
function filterData(type = null){
    if(type =='search'){$('#searchbuttons').html('Searching..');}
	$.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        type: 'POST',
		data: $('#searchForm').serialize(),
		url: "{{ url('/panel/brands_paginate') }}",
		success: function(response){
			$('#replaceHtml').html(response);
            $('#searchbuttons').html('Search');
		}
	});
}
</script> 
@endsection