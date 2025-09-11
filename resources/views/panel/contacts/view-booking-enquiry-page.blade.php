@extends('layout.admin.dashboard')

@section('content')

            <div class="container-xxl flex-grow-1 container-p-y">
            <div class="card mb-2 p-3">
            <form id="searchForm" name="searchForm">
            <div class="row">
            <div class="col-md-1">
            <a style="color:#FFF" href="{{route('admin.booking-enquiries')}}" class="btn btn-danger waves-effect waves-light">back</a>
            </div>
            </div>
            </form>
            </div>
              <!-- Basic Bootstrap Table -->
              <div class="card">
                <h5 class="card-header">Booking Enquiry Details </h5>
                
                <div class="table-responsive text-nowrap">
                  <table class="table">
                    <thead>
                         <tr>
                        <th width="20%"><strong>Details</strong></th>
                        <td>&nbsp;<td>
                      </tr>
                      <tr>
                        <th>Booking Type</th>
                        <td>{{$record->booking_type}}<td>
                      </tr>
                      @if($record->booking_type == 'Existing Booking')
                      <tr>
                        <th>Booking Number</th>
                        <td>{{$record->booking_number}}<td>
                      </tr>
                      @endif
                       <tr>
                        <th>Customer Name</th>
                        <td>{{$record->name}}<td>
                      </tr>
                       <tr>
                        <th>Customer email</th>
                        <td>{{$record->email}}<td>
                      </tr>
                       <tr>
                        <th>Customer Mobile No.</th>
                        <td>{{$record->contact}}<td>
                      </tr>
                       <tr>
                        <th>Number Of Guest</th>
                        <td>{{$record->booking_total_guest}}<td>
                      </tr>
                       @if($record->booking_type == 'New Inquiry' && !empty($record->destinations) )
                       @php
                       $destinations = json_decode($record->destinations);
                       @endphp
                       @if(count($destinations) > 0)
                        <tr>
                            <th align="top">Destinations</th>
                            <td>
                                @foreach($destinations as $key => $destination)
                                    <div style="margin-bottom:20px;" >
                                        <span style="font-weight:bold; width:120px; display:inline-block;">Destination :</span> {{$destination->destination}}<br />
                                        <span style="font-weight:bold; width:120px; display:inline-block;">Check-in :</span> {{$destination->checkIn}}
                                    </div>
                                @endforeach
                            <td>
                        </tr>
                        @endif
                       @endif
                      <tr>
                        <th>Tour Description</th>
                        <td>{{$record->message}}<td>
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