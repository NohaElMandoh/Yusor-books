@extends('layouts.app')
@section('page_title')
    Books
@endsection

@section('content')
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">List of Books</h3>

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
                                <th>Name</th>
                                <th>Description</th>
                                <th>Publish Year</th>
                                <th>ISBN Number</th>
                                <th>Author Name</th>
                                <th>Department Name</th>
                                {{--<th>Student Name</th>--}}

                                <th class="text-center">Edit</th>
                                <th class="text-center">Delete</th>
                                {{--
                                'author','department','students'--}}
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($records as $record)

                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$record->title}}</td>
                                    <td>{{$record->desc}}</td>
                                    <td>{{$record->publish_year}}</td>
                                    <td>{{$record->ISBN_num}}</td>
                                    <td>{{$record->author->name}}</td>
                                    <td>{{$record->department->name}}</td>
                                    {{--<td>{{$record->$studentname}}</td>--}}



                                    <td class="text-center">
                                        <a href="{{url(route('books.edit',$record->id))}}" class="btn btn-success btn-xs"><i class="fa fa-edit"></i></a>
                                    </td>
                                    <td>

                                        <form action="{{ url('books/delete'.$record->id) }}" method="post" enctype="multipart/form-data">
                                            {{ csrf_field() }}
                                            <button type="submit" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></button>
                                        </form>
                                    </td>
                                    {{--<td class="text-center">--}}
                                      {{--{!! Form::open([--}}
                                          {{--'action' => ['BookController@destroy',$record->id],--}}
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
