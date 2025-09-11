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
     <td>{{$row->email}}</td>  
     <td>{{$row->contact}}</td> 
     <td>{{$row->message}}</td>    
    <td>{!! date('d M, Y h:i A',strtotime($row->created_at)) !!}</td>
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