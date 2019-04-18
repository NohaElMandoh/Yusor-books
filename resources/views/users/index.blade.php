@extends('layouts.app')
@section('page_title')
    Users
@endsection

@section('content')
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">List of Users</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                            title="Collapse">
                        <i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip"
                            title="Remove">
                        <i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">

                {{--@include('flash::message')--}}
                <div class="clearfix"></div>

                @if(count($records))

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>User Name</th>
                                <th>Email</th>
                                <th>Gender</th>
                                <th>Department</th>
                                <th class="text-center">Delete</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($records as $record)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$record->UserName}}</td>
                                    <td>{{$record->Email}}</td>
                                    <td>{{$record->Gender}}</td>
                                    <td>{{$record->department->name}}</td>

                                    <form action="{{ url('users/delete'.$record->id) }}" method="post" enctype="multipart/form-data">
                                        {{ csrf_field() }}
                                        <button type="submit" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></button>
                                    </form>
                                    {{--<td class="text-center">--}}
                                      {{--{!! Form::open([--}}
                                          {{--'action' => ['UserController@destroy',$record->id],--}}
                                          {{--'method' => 'delete'--}}
                                      {{--]) !!}--}}
                                      {{--<button type="submit" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></button>--}}
                                      {{--{!! Form::close() !!}--}}
                                  {{--</td>--}}
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-danger" role="alert">
                        No data
                    </div>
                @endif
            </div>
            <!-- /.box-footer-->
        </div>
        <!-- /.box -->

    </section>
    <!-- /.content -->
@endsection
