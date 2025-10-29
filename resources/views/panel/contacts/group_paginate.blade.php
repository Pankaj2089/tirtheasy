@php
$siteUrl = env('APP_URL');
@endphp
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
    <td>{{$row->name}}</td>
     <td>{{$row->contact}}</td>    
     <td>{{$row->booking_room_type}}</td>    
    <td>{!! date('d M, Y h:i A',strtotime($row->created_at)) !!}</td>
    <td>
        <div class="dropdown">
            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
            <i class="icon-base ti tabler-dots-vertical"></i>
            </button>
            <div class="dropdown-menu">
                <a class="dropdown-item" style="cursor:pointer"  href="{{url('/panel/view-group-enquiries/')}}/{{$row->id}}" ><i class="icon-base ti tabler-eye me-1"></i> View</a>
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
            <div id="pagination">{!! $records->links('pagination.front') !!}</div>
        </td>
    </tr>