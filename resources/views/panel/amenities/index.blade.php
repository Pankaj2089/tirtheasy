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
      <select name="search_category_id" class="form-select select2">
        <option value="">Category</option>
        @foreach($categories as $category)
        <option value="{{$category->id}}">{{$category->title}}</option>
        @endforeach
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
      <h5 class="card-header">Amenities <a onclick="$('#popupHeading').html('Add New Amenity');clearForm();" style="float:right; color:#FFF" data-bs-toggle="modal"
              data-bs-target="#addNewCCModal" class="btn btn-success waves-effect waves-light">Add</a></h5>
      <div class="table-responsive text-nowrap">
        <table class="table">
          <thead>
            <tr>
              <th>#ID</th>
              <th>Title</th>
              <th>Category</th>
              <th>Icon</th>
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
              <h4 id="popupHeading" class="mb-2">Add New Amenity</h4>
              <!--<p>Create a brand new category from here.</p>-->
            </div>
            <form id="pageForm" class="row g-6" method="post">
            <input type="hidden" id="row_id" name="row_id" value="0" />
            
            <div class="col-12 form-control-validation">
                <label class="form-label w-100" for="title">Category</label>
                <div class="input-group-merge">
                 <select name="category_id" id="category_id" class="form-select">
                    <option value="">Category</option>
                    @foreach($categories as $category)
                    <option value="{{$category->id}}">{{$category->title}}</option>
                    @endforeach
                </select>
                </div>
              </div>
              <div class="col-12 form-control-validation">
                <label class="form-label w-100" for="title">Title</label>
                <div class="input-group-merge">
                  <input id="title" name="title" class="form-control credit-card-mask" placeholder="Please enter title" type="text" aria-describedby="title2" />
                </div>
              </div>
              
              <div class="col-12 form-control-validation">
                <label class="form-label w-100" for="title">Icon</label>
                <div class="input-group-merge">
                    <input type="file" id="icon" name="icon" class="form-control" accept="image/png, image/jpeg" />
                    <input type="hidden" name="old_icon" id="old_icon" value="" />
                </div>
                <div id="oldImage"></div>
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
var addUrl = "{{route('admin.add-amenity')}}";
var getUrl = "{{route('admin.get-amenity')}}";

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

		var file = $('#icon')[0].files[0];

    var formData = new FormData();
    formData.append("icon", file);
    formData.append("category_id", $('#category_id').val());
    formData.append("title", $('#title').val());
    formData.append("old_icon", $('#old_icon').val());
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
	$('#popupHeading').html('Edit Amenity');
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
				$('#title').val(obj['record']['title']);
				$('#old_icon').val(obj['record']['icon']);
				$('#category_id').val(obj['record']['category_id']);
        var icon = obj['record']['icon_image'];
        if(icon != '' && icon != null){
          var image ='<div class="col-12 form-control-validation" style="margin-top:15px;">\
          <label class="form-label w-100" for="title">Old Icon</label>\
            <img src="'+icon+'" width="60px" />\
          </div>';
          $('#oldImage').html(image);
      }
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
	$('#seo_title').val('');
	$('#seo_description').val('');
	$('#seo_keyword').val('');
}
function filterData(type = null){
    if(type =='search'){$('#searchbuttons').html('Searching..');}
	$.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        type: 'POST',
		data: $('#searchForm').serialize(),
		url: "{{ url('/panel/amenities_paginate') }}",
		success: function(response){
			$('#replaceHtml').html(response);
            $('#searchbuttons').html('Search');
		}
	});
}
</script> 
@endsection