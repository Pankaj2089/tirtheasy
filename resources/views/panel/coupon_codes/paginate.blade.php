@if($records->count()>0)
@foreach($records as $key => $row)   
@php
    $count = $records->count();
    $last = $records->lastItem();
    $page = $records->currentPage();
    $sr = $key+1;
    if($page > 1){
        $sr = ($last-$count)+$key+1;
    }
@endphp
 <tr>
    <td>
        <div class="d-flex align-items-center">
            {!! $sr !!}
        </div>
    </td>
   <td>
      <div class="d-flex align-items-center">
         {!! $row->title !!}
      </div>
   </td>
   <td>
      @if($row->discount_type == 'Amount')
      <div class="d-flex align-items-center">₹ {!! $row->amount !!}</div>
      @else
      <div class="d-flex align-items-center">{!! $row->amount !!}%</div>
      @endif
   </td>
   <td>
      <div class="d-flex align-items-center">
         {!! date('d-m-Y',strtotime($row->start_date)) !!}
      </div>
   </td>
   <td>
      <div class="d-flex align-items-center">
         {!! date('d-m-Y',strtotime($row->end_date)) !!}
      </div>
   </td>
   <td>
      @php
      if($row->status == 1){$class = 'bg-label-success'; $label = 'Active';}else{$class = 'bg-label-danger'; $label = 'In-Active';}
      @endphp
      <a style="cursor:pointer" onclick="changeStatus('coupon_codes','{!!$row->id!!}');" id="status_{{$row->id}}" class="badge {{$class}} me-1">{{$label}}</a>
      <input type="hidden" id="status_value_{{$row->id}}" value="{!!$row->status!!}" />
   </td>

   <td>{!! date('d M, Y h:i A',strtotime($row->created_at)) !!}</td>
   <td>
      <div class="dropdown">
         <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
            <i class="icon-base ti tabler-dots-vertical"></i>
         </button>
         <div class="dropdown-menu">
            <a class="dropdown-item" href="{{ url('/panel/edit-coupon-code',base64_encode($row->id)) }}" ><i class="icon-base ti tabler-pencil me-1"></i> Edit</a>
            <a class="dropdown-item" onclick="deleteData('coupon_codes','{{ $row->id }}');" href="javascript:void(0);"><i class="icon-base ti tabler-trash me-1"></i> Delete</a>
         </div>
   </div>

   </td>
</tr>
@endforeach
@else
<tr>
   <td align="center" colspan="15">Record not found</td>
</tr>
@endif
<tr>
   <td align="center" colspan="10">
      <div id="pagination">{!! $records->links('pagination.front') !!}</div>
   </td>
</tr>