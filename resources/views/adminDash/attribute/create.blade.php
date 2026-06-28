@php
    use App\Models\Attribute;

@endphp
@extends('layouts.Backend.master')
@section('title')
    ATTRIBUTE CREATE
@endsection
@section('content')
<div class="back mb-3">
    <a href="{{url('/admin/attributes')}}">
        <img height="30px" src="{{asset('adminDash/images/svg/backbutton.png')}}" alt="">
    </a>
</div>
    <div class="row">
        <div class="col-8">
            <div class="card">
                <div class="card-header">
                    <h3>{{ $attribute->name }} Values</h3>
                </div>
                <table class="table">
                    <thead>
                        <th>SL</th>
                        <th>Value</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                        @forelse ($attributeValues as $attributeValue)
                            <tr>
                                <td>1</td>
                                <td>{{ $attributeValue->value }}</td>
                                <td><a class="btn btn-danger" href="">Delete</a></td>
                            </tr>
                        @empty
                            <td colspan="2" class="text-center text-danger">No Value found.</td>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-4">
            <div class="card">
                <div class="card-header">
                    <h3>Add {{ $attribute->name }} Values</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('value.store',$attribute->id) }}" method="post">
                        @csrf

                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Values</label>
                            <input type="text" class="form-control" name="value">
                        </div>

                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection

