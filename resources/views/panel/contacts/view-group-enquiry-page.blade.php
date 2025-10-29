@extends('layout.admin.dashboard')

@section('content')

            <div class="container-xxl flex-grow-1 container-p-y">
            <div class="card mb-2 p-3">
            <form id="searchForm" name="searchForm">
            <div class="row">
            <div class="col-md-1">
            <a style="color:#FFF" href="{{route('admin.group-enquiries')}}" class="btn btn-danger waves-effect waves-light">back</a>
            </div>
            </div>
            </form>
            </div>
              <!-- Basic Bootstrap Table -->
              <div class="card">
                <h5 class="card-header">Group Enquiry Details </h5>
                
                <div class="table-responsive text-nowrap">
                  <table class="table">
                    <thead>
                         <tr>
                        <th width="20%"><strong>Details</strong></th>
                        <td>&nbsp;<td>
                      </tr>
                       <tr>
                        <th>Customer Name</th>
                        <td>{{$record->name}}<td>
                      </tr>
                       <tr>
                        <th>Customer Mobile No.</th>
                        <td>{{$record->contact}}<td>
                      </tr>
                      <tr>
                        <th>Alternative Mobile No.</th>
                        <td>{{$record->booking_alternative_mobile}}<td>
                      </tr>
                      <tr>
                        <th>Room Type</th>
                        <td>{{$record->booking_room_type}}<td>
                      </tr>
                      <tr>
                        <th>No. of Guest</th>
                        <td>{{$record->booking_total_guest}}<td>
                      </tr>
                       <tr>
                        <th>Total Room</th>
                        <td>{{$record->booking_total_room}}<td>
                      </tr>
                      
                       @php
                       $destinations = json_decode($record->destinations);
                       @endphp
                       @if(count($destinations) > 0)
                        <tr>
                            <th align="top">Place</th>
                            <td>
                                @foreach($destinations as $key => $destination)
                                    <div style="margin-bottom:20px;" >
                                        <span style="font-weight:bold; width:120px; display:inline-block;">Place :</span> {{$destination->bookingPlace}}<br />
                                        <span style="font-weight:bold; width:120px; display:inline-block;">Check-in :</span> {{$destination->checkIn}}<br />
                                        <span style="font-weight:bold; width:120px; display:inline-block;">Check-out :</span> {{$destination->checkOut}}
                                    </div>
                                @endforeach
                            <td>
                        </tr>
                        @endif
                      <tr>
                        <th>Estimate Budget</th>
                        <td>{{$record->estimate_budget}}<td>
                      </tr>
                      <tr>
                        <th>Date</th>
                        <td>{!! date('d M, Y h:i A',strtotime($record->created_at)) !!}<td>
                      </tr>
                    </thead>
                  </table>
                </div>
              </div>
              <!--/ Basic Bootstrap Table -->
            </div>
<style>
    th{
        vertical-align: sub;
        background: #ddd !important;
    }
</style>
@endsection