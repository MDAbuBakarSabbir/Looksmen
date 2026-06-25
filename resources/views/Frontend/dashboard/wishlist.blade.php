@extends('layouts.Frontend.master')
@section('title')
    WISHLIST
@endsection
@section('content')
    <section class="py-5">
        <div class="container">
            <div class="d-flex align-items-start">

                @include('Frontend.dashboard.partials.usersideNav')

                <div class="aiz-user-panel">
                    <div class="aiz-titlebar mt-2 mb-4">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h1 class="h3">Wishlist</h1>
                            </div>
                        </div>
                    </div>
                    <div class="row gutters-10">

                    </div>
                    <div class="row gutters-10">
                        <div class="col-lg">
                            <div class="card">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection


