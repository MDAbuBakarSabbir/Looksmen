@extends('layouts.Backend.master')
@section('title')
    ROLES
@endsection
@section('content')
<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header">
                <h3>Roles</h3>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <th scope="col">Role</th>
                        <th scope="col">Status</th>
                        <th scope="col">Action</th>
                    </thead>
                    <tbody>
                        @forelse ($roles as $role)
                            <tr>
                                <td>{{ $role->role }}</td>
                                <td>
                                    <label class="switch">
                                        <input class="status-switch" type="checkbox" data-id="{{ $role->id }}"
                                            {{ $role->status == '1' ? 'checked' : '' }}>
                                        <span class="slider round" title="Click to Change Status">
                                        </span>
                                    </label>
                                </td>
                            </tr>
                        @empty
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card">
            <div class="card-header">
                Add Roles
            </div>
            <div class="card-body">
                <form action="" method="POST">
                    <div class="form">
                        <input type="text" name="" id="">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
