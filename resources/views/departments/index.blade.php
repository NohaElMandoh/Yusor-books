@extends('layouts.app')
@section('page_title')
    Departments
@endsection

@section('content')
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">List of departments</h3>

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
                <a href="{{url(route('departments.create'))}}" class="btn btn-primary"><i class="fa fa-plus"></i> New department</a>
                {{--@include('flash::message')--}}
                <div class="clearfix"></div>

                @if(count($records))
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th class="text-center">Edit</th>
                                <th class="text-center">Delete</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($records as $record)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$record->name}}</td>
                                    <td class="text-center">
                                        <a href="{{url(route('departments.edit',$record->id))}}" class="btn btn-success btn-xs"><i class="fa fa-edit"></i></a>
                                    </td>


                                    <td>

                                        <form action="{{ url('departments/delete'.$record->id) }}" method="post" enctype="multipart/form-data">
                                            {{ csrf_field() }}
                                            <button type="submit" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></button>
                                        </form>
                                    </td>


                                    {{--<a onclick="return confirm('Are you sure to delete this data?')" href="{{ url('departments/delete/'.$record->id) }}" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-remove"></span></a>--}}

                                    {{--<td class="text-center">--}}
                                      {{--{!! Form::open([--}}
                                          {{--'action' => ['departmentsController@destroy',$record->id],--}}
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
