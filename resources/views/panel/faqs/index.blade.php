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
      <select name="search_category" class="form-select select2">
        <option value="">Category</option>
        <option value="Contact Us">Contact Us</option>
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
      <h5 class="card-header">Faqs <a onclick="$('#popupHeading').html('Add New Faq');clearForm();" style="float:right; color:#FFF" data-bs-toggle="modal"
              data-bs-target="#addNewCCModal" class="btn btn-success waves-effect waves-light">Add</a></h5>
      <div class="table-responsive text-nowrap">
        <table class="table">
          <thead>
            <tr>
              <th>#ID</th>
              <th>Category</th>
              <th>Question</th>
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
              <h4 id="popupHeading" class="mb-2">Add New Faq</h4>
              <!--<p>Create a brand new category from here.</p>-->
            </div>
            <form id="pageForm" class="row g-6" method="post">
            <input type="hidden" id="row_id" name="row_id" value="0" />
            
            <div class="col-12 form-control-validation">
                <label class="form-label w-100" for="title">Category</label>
                <div class="input-group-merge">
                 <select name="category" id="category" class="form-select">
                    <option value="">Category</option>
                    <option value="Contact Us">Contact Us</option>
                </select>
                </div>
              </div>
              <div class="col-12 form-control-validation">
                <label class="form-label w-100" for="title">Question</label>
                <div class="input-group-merge">
                  <input id="question" name="question" class="form-control credit-card-mask" placeholder="Please enter question" type="text" aria-describedby="title2" />
                </div>
              </div>

               <div class="col-12 form-control-validation">
                <label class="form-label w-100" for="title">Answer</label>
                <div class="input-group-merge">
                  <textarea id="answer" name="answer" style="height:200px" class="form-control credit-card-mask" placeholder="Please enter answer" type="text" aria-describedby="title2"></textarea>
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
var addUrl = "{{route('admin.add-faq')}}";
var getUrl = "{{route('admin.get-faq')}}";

$(document).ready(function(){

  $('#title').on('keydown', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        $('#submitBtn').trigger('click');
    }
});

    filterData('simple');
	$('#submitBtn').click(function(e) {
		$('#submitBtn').html('Processing...');

    var formData = new FormData();
    formData.append("category", $('#category').val());
    formData.append("question", $('#question').val());
    formData.append("answer", $('#answer').val());
    formData.append("row_id", $('#row_id').val());

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
	$('#popupHeading').html('Edit Faq');
  $('#oldImage').html('');
  $('#old_icon').val('');
	$.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        type: 'POST',
		data:{rowId:rowId},
		url: getUrl,
		success: function(response){
			var obj = JSON.parse(response);
			if(obj['heading'] == "Success"){
				$('#row_id').val(obj['record']['id']);
				$('#category').val(obj['record']['category']);
				$('#question').val(obj['record']['question']);
				$('#answer').val(obj['record']['answer']);
			}else{
				swal("Error!", obj['msg'], "error");
				return false;
			}
		}
	});
}
function clearForm(){
	$('#row_id').val(0);
	$('#category').val('');
	$('#question').val('');
	$('#answer').val('');
}
function filterData(type = null){
    if(type =='search'){$('#searchbuttons').html('Searching..');}
	$.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        type: 'POST',
		data: $('#searchForm').serialize(),
		url: "{{ url('/panel/faqs_paginate') }}",
		success: function(response){
			$('#replaceHtml').html(response);
            $('#searchbuttons').html('Search');
		}
	});
}
</script> 
@endsection