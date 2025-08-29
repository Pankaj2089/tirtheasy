<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
        <thead>
        <tr>
            <th>#</th>
            <th>Category</th>
            <th>Title</th>
            <th>value</th>
            <th>Status</th>
            <th>Action</th> 
        </tr>
        </thead>
        <tbody>
        @foreach($records as $key => $value)
        <tr>
        <td>{{ $key+1; }}</td>
        <td>{{ $value->category_id == 1 ? 'Walkable Places' : ($value->category_id == 2 ? 'Popular Landmarks' : 'Closest Landmarks') }}</td>
        <td>{{$value->title}}</td>
        <td>{{$value->description}}</td>
        <td> 
            @php
            if($value->status == 1){$class = 'bg-label-success'; $label = 'Active';}else{$class = 'bg-label-danger'; $label = 'In-Active';}
            @endphp
            <a style="cursor:pointer" onclick="changeStatus('hotel_landmarks','{!!$value->id!!}');" id="status_{{$value->id}}" class="badge {{$class}} me-1">{{$label}}</a>
            <input type="hidden" id="status_value_{{$value->id}}" value="{!!$value->status!!}" />
            </td>
            <td><a href="javascript:void(0);" onclick="deleteData('hotel_landmarks','{{ $value->id }}');" class="btn btn-sm btn-danger"  title="Delete">
                Delete
            </a></td>
       
        </tr>
        @endforeach
         @if(count($records) <= 0)
          <tr>
            <td colspan="7" class="alert alert-danger text-center">Record(s) not found.</td>
          </tr>
         @endif
        </tbody>
    </table>