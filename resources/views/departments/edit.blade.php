@extends('layouts.app')

@section('page_title')
    Update Department
@endsection

@section('content')
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Edit Department</h3>

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
                <form action="{{ url('departments/update'.$model->id) }}" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <button type="submit" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></button>
                </form>


                @include('flash::message')
                @include('partials.validation_errors')
                @include('departments.form')
                {!! Form::close() !!}
            </div>
            <!-- /.box-footer-->
        </div>
        <!-- /.box -->

    </section>
    <!-- /.content -->
@endsection
