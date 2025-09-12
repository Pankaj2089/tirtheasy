@extends('layout.admin.dashboard')

@section('content')

@php
        $details = json_decode($record->other_details);
    @endphp
<div class="container-xxl flex-grow-1 container-p-y">
  <div class="card mb-2 p-3">
    <form id="searchForm" name="searchForm">
          <div class="row">
          <div class="">
          <a style="color:#FFF" href="{{route('admin.orders')}}" class="btn btn-danger waves-effect waves-light">back</a>
          <a style="color:#FFF; float:right; cursor:pointer" onclick="printInvoice();"  class="btn btn-success waves-effect waves-light">Print Invoice</a>
          </div>
      </div>
    </form>
  </div>
    <!-- Basic Bootstrap Table -->
    <div class="card">                
      <div class="table-responsive text-nowrap">
        <div class="container my-5">
              <div id="invoiceSection" class="invoice">
                <!-- Header -->
                <div class="invoice-header d-flex justify-content-between align-items-center">
                  <div>
                    <h3 class="mb-0">Hotel Booking Invoice</h3>
                    <small>Invoice #{{$record->invoice_id}}</small><br>
                    <small>Date: {!! date('d M, Y h:i A',strtotime($record->created_at)) !!}</small>
                  </div>
                  <div>
                    <h5 class="mb-0">{{ $details->roomDetails->hotel_details->title}}</h5>
                    <small>{{ $details->roomDetails->hotel_details->address}}, <br />{{ $details->roomDetails->hotel_details->city}}, {{ $details->roomDetails->hotel_details->state}}, India</small>
                  </div>
                </div>

                <!-- Guest Details -->
                <div class="row mb-4">
                  <div class="col-sm-6">
                    <h6>Guest Details</h6>
                    <p class="mb-0"><strong>Name:</strong> {{$record->billing_name}}</p>
                    <p class="mb-0"><strong>Email:</strong> {{$record->billing_email}}</p>
                    <p class="mb-0"><strong>Phone:</strong> +91 {{$record->billing_phone}}</p>
                    @if(isset($details->userDetails->arrival_date) && !empty($details->userDetails->arrival_date))
                    <p class="mb-0"><strong>Arrival Date:</strong> {{date('d M, Y',strtotime($details->userDetails->arrival_date))}}</p>
                    @endif
                    @if(isset($details->userDetails->arrival_time) && !empty($details->userDetails->arrival_time))
                    <p class="mb-0"><strong>Arrival Time:</strong> {{date('h:i A',strtotime($details->userDetails->arrival_time))}}</p>
                    @endif
                  </div>
                  <div class="col-sm-6 text-sm-end">
                    <h6>Booking Details</h6>
                    <p class="mb-0"><strong>Check-in:</strong> {{date('d M, Y',strtotime($record->check_in_date))}}</p>
                    <p class="mb-0"><strong>Check-out:</strong> {{date('d M, Y',strtotime($record->check_out_date))}}</p>
                    <p class="mb-0"><strong>Room Type:</strong> {{$details->roomDetails->room_details->title}}</p>
                    <p class="mb-0"><strong>Rooms:</strong> {{$record->rooms}}</p>
                    <p class="mb-0"><strong>Adults:</strong> {{$record->adults}}</p>
                    <p class="mb-0"><strong>Childs:</strong> {{$record->childs}}</p>
                  </div>
                </div>

                <!-- Invoice Table -->
                <div class="table-responsive">
                  <table class="table table-bordered">
                    <thead class="table-light">
                      <tr>
                        <th>Description</th>
                        <th class="text-center">Nights</th>
                        <th class="text-end">Rate (₹)</th>
                        <th class="text-end">Amount (₹)</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>Room Charges</td>
                        <td class="text-center">{{$record->number_of_nights}}</td>
                        <td class="text-end">₹{{number_format($record->room_price,2)}}</td>
                        <td class="text-end">₹{{number_format($record->sub_total,2)}}</td>
                      </tr>
                    </tbody>
                    <tfoot>
                      <tr>
                        <th colspan="3" class="text-end">Subtotal</th>
                        <th class="text-end">₹{{number_format($record->sub_total,2)}}</th>
                      </tr>

                      <tr>
                          <th colspan="3" class="text-end">Convenience Fee</th>
                          <th class="text-end">₹{{number_format($record->convenience,2)}}</th>
                        </tr>

                      @if($record->extra_mattress > 0)
                        <tr>
                          <th colspan="3" class="text-end">Extra Mattress ({{$record->extra_mattress}})</th>
                          <th class="text-end">₹{{number_format($record->extra_mattress_price,2)}}</th>
                        </tr>
                      @endif
                      @if($record->discount > 0)
                        <tr>
                          <th colspan="3" class="text-end">Discount ({{$record->coupon_code}})</th>
                          <th class="text-end">- ₹{{number_format($record->discount,2)}}</th>
                        </tr>
                      @endif

                      <tr class="table-light">
                        <th colspan="3" class="text-end">Total</th>
                        <th class="text-end">₹{{number_format($record->grand_total,2)}}</th>
                      </tr>
                    </tfoot>
                  </table>
                </div>

                <!-- Footer -->
                <!-- <div class="invoice-footer">
                  <p>Thank you for booking with Hotel Paradise. We look forward to hosting you!</p>
                  <small>This is a computer-generated invoice and does not require a signature.</small>
                </div> -->
              </div>
            </div>
      </div>
    </div>
    <!--/ Basic Bootstrap Table -->
  </div>
  <script>
    function printInvoice() {
    var content = document.getElementById("invoiceSection").innerHTML;
    var myWindow = window.open("", "", "width=800,height=600");
    myWindow.document.write(`
        <html>
          <head>
            <title>Invoice</title>
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
            <style>
              body { padding:20px; }
            </style>
          </head>
          <body>${content}</body>
        </html>
    `);
    myWindow.document.close();
    myWindow.print();
}
    </script>
<style>
    .invoice {
      background: #fff;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,.1);
    }
    .invoice-header {
      border-bottom: 2px solid #dee2e6;
      margin-bottom: 20px;
      padding-bottom: 10px;
    }
    .invoice-footer {
      border-top: 2px solid #dee2e6;
      margin-top: 20px;
      padding-top: 10px;
      font-size: 14px;
      text-align: center;
      color: #6c757d;
    }
</style>
@endsection