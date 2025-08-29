@if($records->count()>0)
    @foreach($records as $key => $row)
     @php
        $count = $records->count();
        $last = 	$records->lastItem();
        $page = $records->currentPage();
        $sr = $key+1;
        if($page > 1){
            $sr = ($last-$count)+$key+1;
        }
    @endphp
    <tr>
        <td>{{$sr}}.</td>
        <td>
        	@if(!empty($row->image))
            <div class="d-flex align-items-center">
                <div class="cropped" id="cropped"><img src="{{URL::asset('public/img/popular-destinations/')}}/{!! $row->image !!}" width="100"></div>
            </div>
            @endif
        </td>
        <td>{{$row->heading}}</td>
        <td>{{ucfirst($row->property_type)}}</td>
        <td>{{$row->destination_type == 1?'Popular Destinations India':'Popular Destinations Outside India'}}</td>
        <td>
            @php
            if($row->status == 1){$class = 'bg-label-success'; $label = 'Active';}else{$class = 'bg-label-danger'; $label = 'In-Active';}
            @endphp
            <a style="cursor:pointer" onclick="changeStatus('popular-destinations','{!!$row->id!!}');" id="status_{{$row->id}}" class="badge {{$class}} me-1">{{$label}}</a>
            <input type="hidden" id="status_value_{{$row->id}}" value="{!!$row->status!!}" />
        </td>
        <td>
            {!! date('d M, Y h:i A',strtotime($row->created_at)) !!}
        </td>
        <td>              
          <div class="dropdown">
                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                    <i class="icon-base ti tabler-dots-vertical"></i>
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item"  href="{{url('/panel/edit-popular-destination/')}}/{{$row->id}}" style="cursor:pointer" ><i class="icon-base ti tabler-pencil me-1"></i> Edit</a>
                    <a class="dropdown-item" onclick="deleteData('popular-destinations','{{ $row->id }}');" href="javascript:void(0);"><i class="icon-base ti tabler-trash me-1"></i> Delete</a>
                </div>
            </div>
        </td>
    </tr>
    @endforeach
    @else
    <tr>
        <td align="center" colspan="6">Record not found</td>
    </tr>
    @endif
    <tr>
        <td align="center" colspan="10">
            <div id="pagination">{{{ $records->links() }}}</div>
        </td>
    </tr>