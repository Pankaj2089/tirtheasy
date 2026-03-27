@extends('layout.admin.dashboard')

@section('content')

        <div class="container-xxl flex-grow-1 container-p-y">
          <div class="card mb-2 p-3">
            <form id="searchForm" name="searchForm">
            <div class="row">
            <div class="col-md-2">
              <input type="text" class="form-control" name="search_title" placeholder="Enter Room Number" id="defaultFormControlInput" />
            </div>
            <div class="col-md-2">
              <select name="search_status" class="form-select">
                <option value="">Status</option>
                <option value="1">Active</option>
                <option value="2">In-Active</option>
              </select>
            </div>
            <div class="col-md-1">
              <a style="color:#FFF" id="searchbuttons" onclick="filterData('search');" class="btn btn-primary waves-effect waves-light">Search</a>
            </div>
            <div class="col-md-1">
              <a style="color:#FFF" onclick="resetFilterForm();" class="btn btn-danger waves-effect waves-light">Reset</a>
            </div>
            </div>
            </form>
            </div>
              <!-- Basic Bootstrap Table -->
              <div class="card">
                <h5 class="card-header">
                    Rooms Numbers - <i style="color:var(--bs-primary); font-weight:600">({{$roomData->title}})</i>
                    <div style="float:right;">
                        <a href="{{ url('/panel/add-room-number/'.$room_id) }}" class="btn btn-success waves-effect waves-light">Add</a>
                        <a href="javascript:void(0);" onclick="deleteSelectedRoomNumbers();" class="btn btn-danger waves-effect waves-light ms-2">Delete Selected</a>
                    </div>
                </h5>
                
                <div class="table-responsive text-nowrap">
                  <table class="table">
                    <thead>
                      <tr>
                        <th><input type="checkbox" id="selectAllRooms" onclick="toggleSelectAllRoomNumbers(this)"></th>
                        <th>#ID</th>
                        <th>Prefix</th>
                        <th>Room Number</th>
                        <th>Full Room Number</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody class="table-border-bottom-0" id="replaceHtml">
                      <tr>
                    <td colspan="10" class="text-center"><img src="{{ asset('public/admin/images/svg/oval.svg') }}" class="me-4" style="width: 3rem" alt="audio"></td>
                  </tr>
                      
                    </tbody>
                  </table>
                </div>
              </div>
              <!--/ Basic Bootstrap Table -->
            </div>

		<script type="text/javascript">
        $(document).ready(function(){
            filterData('simple');
        });
        
        function toggleSelectAllRoomNumbers(source){
            $('.room-number-checkbox').prop('checked', source.checked);
        }

        function deleteSelectedRoomNumbers(){
            var selected = [];
            $('.room-number-checkbox:checked').each(function(){
                selected.push($(this).val());
            });
            if(selected.length == 0){
                swal('Oops!', 'Please select at least one room number.', 'warning');
                return;
            }
            swal({
                title: 'Are you sure?',
                text: 'Once deleted, you will not be able to recover these records!',
                icon: 'warning',
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if(willDelete){
                    $.ajax({
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        type: 'POST',
                        url: "{{ url('/panel/delete-record-multiple') }}",
                        data: {table: 'room_numbers', rowIDs: selected},
                        success: function(msg){
                            if(msg == 'Success'){
                                swal({
                                    title: 'Success',
                                    text: 'Selected records have been deleted successfully.',
                                    icon: 'success',
                                });
                                filterData('simple');
                            }else{
                                swal('Oops!', msg, 'error');
                            }
                        }
                    });
                } else {
                    swal('Your records are safe!');
                }
            });
        }

        function filterData(type = null){
            if(type =='search'){$('#searchbuttons').html('Searching..');}
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type: 'POST',
                data: $('#searchForm').serialize(),
                url: "{{ url('/panel/rooms_numbers_paginate/'.$room_id) }}",
                success: function(response){
                    $('#replaceHtml').html(response);
                    $('#searchbuttons').html('Search');
                }
            });
        }

        </script> 
        @endsection