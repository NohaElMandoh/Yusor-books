<div class="form-group">
    <label for="name">Title</label>
    {!! Form::text('name',$model->title,[
        'class' => 'form-control'
    ]) !!}
</div>
<div class="form-group">
    <button class="btn btn-primary" type="submit">Submit</button>
</div>