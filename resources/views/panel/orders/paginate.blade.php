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
        $details = json_decode($row->other_details);
    @endphp

    <tr>
    <td>{{$sr}}.</td>
    <td>{{$row->invoice_id}}</td>
     <td>
        <strong>Name:</strong> {{$row->billing_name}}<br />
        <!-- <strong>Email:</strong> {{$row->billing_email}}<br /> -->
        <strong>Mobile:</strong> {{$row->billing_phone}}<br />
        <strong>City:</strong> {{$row->billing_city}}<br />
        <strong>State:</strong> {{$row->billing_state}}<br /><br />
        <strong>Check-In:</strong> {{date('d M, Y',strtotime($row->check_in_date))}}<br />
        <strong>Check-Out:</strong> {{date('d M, Y',strtotime($row->check_out_date))}}


     </td>  
     <td>
        <strong>Hotel:</strong> {{ \Illuminate\Support\Str::limit($details->roomDetails->hotel_details->title, 20) }}...<br />
        <strong>Room:</strong> {{$details->roomDetails->room_details->title}}<br />
        <strong>Rooms:</strong> {{$row->rooms}}<br />
        <strong>Adults:</strong> {{$row->adults}}<br />
        <strong>Childs:</strong> {{$row->childs}}<br />
        <strong>Nights:</strong> {{$row->number_of_nights}}<br />
        <strong>Extra Mattress:</strong> {{$row->extra_mattress}}
     </td> 
     <td>₹{{$row->grand_total}}</td> 
     <td>{{$row->payment_status}}</td>       
    <td>{!! date('d M, Y h:i A',strtotime($row->created_at)) !!}</td>
    <td>
        <div class="dropdown">
            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
            <i class="icon-base ti tabler-dots-vertical"></i>
            </button>
            <div class="dropdown-menu">
                <a class="dropdown-item" style="cursor:pointer"  href="{{url('/panel/view-order/')}}/{{$row->id}}" ><i class="icon-base ti tabler-eye me-1"></i> View</a>
                </div>
        </div>
    </td>
    </tr>

    @endforeach
    @else
    <tr>
        <td align="center" colspan="8">Record not found</td>
    </tr>
    @endif
    <tr>
        <td align="center" colspan="10">
            <div id="pagination">{!! $records->links('pagination.front') !!}</div>
        </td>
    </tr>