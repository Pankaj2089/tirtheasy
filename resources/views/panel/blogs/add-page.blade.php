@extends('layout.admin.dashboard')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  <div class="row">
    <div class="col">
      <h6 class="mt-6">Add Blog</h6>
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
              <li class="nav-item">
                <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#form-tabs-account" aria-controls="form-tabs-account"
role="tab" aria-selected="false"> <span class="icon-base ti tabler-user-cog icon-lg d-sm-none"></span><span class="d-none d-sm-block">SEO Details</span> </button>
              </li>
              
            </ul>
          </div>
        </div>
        <div class="card-body">
          <div class="tab-content p-0">
            <div class="tab-pane fade active show" id="form-tabs-personal" role="tabpanel">
                <div class="row g-6">
                  <div class="col-md-8">
                    <label class="form-label" for="title">Blog Name</label>
                    <input type="text" id="title" name="title" class="form-control" />
                  </div>
                  <div class="col-md-4">
                    <label class="form-label" for="category">Blog Category</label>
                    <select type="text" id="category" name="category" class="form-select" >
                        <option value="Most Popular" selected>Most Popular</option>
                        <option value="Best Accommodation">Best Accommodation</option>
                        <option value="Events">Events</option>
                        <option value="Darshan Stories">Darshan Stories</option>
                    </select>
                  </div>

                  <div class="col-md-12">
                    <label class="form-label" for="short_description">Short Description</label>
                    <input type="text" id="short_description" name="short_description" class="form-control" />
                  </div>
                  <div class="col-md-12">
                    <label class="form-label" for="description">Description</label>
                    <textarea class="form-control editor" rows="10" id="description" name="description"></textarea>
                  </div> 
                 
                  <div class="col-md-4">
                    <label class="form-label" for="blog_date">Blog Date</label>
                    <input type="date" id="blog_date" name="blog_date" class="form-control" />
                  </div> 
                  <div class="col-md-4">
                    <label class="form-label" for="banner">Main Image</label>
                    <input type="file" id="banner" name="banner" class="form-control" />
                  </div>         
                
                                 </div>
            </div>
            <div class="tab-pane fade" id="form-tabs-account" role="tabpanel">
                <div class="row g-6">
                  <div class="col-md-12">
                    <label class="form-label" for="seo_title">SEO Title</label>
                    <input type="text" id="seo_title" name="seo_title" class="form-control"/>
                  </div>
                  <div class="col-md-12">
                    <label class="form-label" for="seo_description">SEO Description</label>
                    <textarea class="form-control" id="seo_description" name="seo_description"></textarea>
                  </div>
                  <div class="col-md-12">
                    <label class="form-label" for="seo_keyword">SEO Keywords</label>
                    <textarea class="form-control" id="seo_keyword" name="seo_keyword"></textarea>
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
        <button type="reset" onclick="window.location.href='{{route('admin.blogs')}}'" class="btn btn-label-secondary" >Cancel</button>
        </div>
        </div>
        </div>
        </div>
        </div>
      
    </div>
  </div>
</div>
<script type="text/javascript">
  
var addUrl = "{{route('admin.add-blog')}}";
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
						window.location.href = "{{route('admin.blogs')}}";
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