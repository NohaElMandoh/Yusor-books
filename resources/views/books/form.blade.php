<div class="form-group">
    <label for="name">Title</label>

    {!! Form::text('name',$model->title,[
        'class' => 'form-control'
    ]) !!}
    <label for="desc">Description</label>
    {!! Form::text('desc',$model->desc,[
        'class' => 'form-control'
    ]) !!}
    <label for="publish_year">Publish Year</label>
    {!! Form::text('publish_year',$model->publish_year,[
        'class' => 'form-control'
    ]) !!}
    <label for="ISBN_num">ISBN Number</label>
    {!! Form::text('ISBN_num',$model->ISBN_num,[
        'class' => 'form-control'
    ]) !!}


    {{--<td>{{$record->author->name}}</td>--}}
    {{--<td>{{$record->department->name}}</td>--}}
</div>
<div class="form-group">
    <button class="btn btn-primary" type="submit">Submit</button>
</div>