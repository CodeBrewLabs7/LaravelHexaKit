@extends('adminpanel::layouts.master')

@section('content')
 <!-- page content -->
 <div class="right_col" role="main">
    <div class="">
      <div class="row">
        <div class="col-md-12 col-sm-12 ">
            <div class="x_panel">
              <div class="x_title">
                <h2>Users<small>List</small></h2>
                <ul class="nav navbar-right panel_toolbox">
                  <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                  </li>
                  <li><a class="close-link"><i class="fa fa-close"></i></a>
                  </li>
                </ul>
                <div class="clearfix"></div>
                <button type="button" class="btn btn-success AddMemberbtn">{{ __('Add New User') }}</button>
              </div>
              <div class="x_content">
                  <div class="row">
                      <div class="col-sm-12">
                        <div class="card-box table-responsive">
                            <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>E-mail</th>
                                        <th>Phone Number</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $key)
                                       <tr>
                                          <td>{{$loop->iteration}}</td>
                                          <td>{{$key->name}}</td>
                                          <td>{{$key->email}}</td>
                                          <td>{{$key->phone_number}}</td>
                                          <td>
                                            <a href="javascript::void();" class="btn bg-primary edit_member" data-id="{{ $key->id }}">Edit</a>  <a href="javascript::void();" class="btn bg-danger delete_member" data-id="{{ $key->id }}">Delete</a>
                                          </td>
                                       </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
          </div>
      </div>
    </div>
 </div>
@include('users::modal')
@endsection
@push('js')
	@include('users::pagescript')
@endpush
