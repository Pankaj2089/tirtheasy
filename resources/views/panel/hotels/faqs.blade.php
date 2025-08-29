<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
        <thead>
        <tr>
            <th>#</th>
            <th>Question</th>
            <th>Answer</th>
            <th>Status</th>
            <th>Action</th> 
        </tr>
        </thead>
        <tbody>
        @foreach($records as $key => $value)
        <tr>
        <td>{{ $key+1; }}</td>
        <td>
            {{$value->question}}
        </td>
        <td>
            {{substr($value->answer,0,200)}}{{strlen($value->answer)> 200 ? '...':''}}
        </td>
        <td> 
            @php
            if($value->status == 1){$class = 'bg-label-success'; $label = 'Active';}else{$class = 'bg-label-danger'; $label = 'In-Active';}
            @endphp
            <a style="cursor:pointer" onclick="changeStatus('hotel_faqs','{!!$value->id!!}');" id="status_{{$value->id}}" class="badge {{$class}} me-1">{{$label}}</a>
            <input type="hidden" id="status_value_{{$value->id}}" value="{!!$value->status!!}" />
            </td>
            <td><a href="javascript:void(0);" onclick="deleteData('hotel_faqs','{{ $value->id }}');" class="btn btn-sm btn-danger"  title="Delete">
                Delete
            </a></td>
       
        </tr>
        @endforeach
         @if(count($records) <= 0)
          <tr>
            <td colspan="5" class="alert alert-danger text-center">Record(s) not found.</td>
          </tr>
         @endif
        </tbody>
    </table>