@extends('layouts.Backend.master')
@section('title')
    CATEGORY EDIT
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <h4>Edit Color</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('color.update',$color->id) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="exampleInputEmail1">Color Name</label>
                    <input type="text" name="color_name" class="form-control" placeholder="Enter Color Name" value="{{$color->color_name}}">
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1">Color Code</label>
                    <input type="text" name="color_code" class="form-control" placeholder="Enter Color Code with #" value="{{$color->color_code}}">
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
@endsection
