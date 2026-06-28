@extends('layouts.Backend.master')
@section('title')
    SMTP SETTINGS
@endsection
@section('content')
    <div class="row">
        @if ($featuresConfig['email_verification'] == '1')
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h3>Mail SMTP</h3>
                </div>
                <div class="card-body">
                    <form action="" method="POST" class="settingsUpdateForm">
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Mail Host</label>
                            <input type="text" class="form-control" name="mailhost">
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Mail Port</label>
                            <input type="text" class="form-control" name="mailport">
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Mail Username</label>
                            <input type="text" class="form-control" name="mailusername">
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputPassword1" class="form-label">Mail Password</label>
                            <input type="text" class="form-control" name="mailpassword">
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Mail From Address</label>
                            <input type="text" class="form-control" name="mailaddress">
                        </div>
                        <div class="radio mb-3">
                            <label for="exampleInputEmail1" class="form-label">Mail Encription</label>
                            <div class="d-flex">
                                <div class="form-check mr-4">
                                    <input class="form-check-input" type="radio" name="mailencription"
                                        id="radioDefault1">
                                    <label class="form-check-label" for="radioDefault1">
                                        TLS
                                    </label>
                                </div>
                                <div class="form-check mr-4">
                                    <input class="form-check-input" type="radio" name="mailencription"
                                        id="radioDefault2" checked>
                                    <label class="form-check-label" for="radioDefault2">
                                        SSL
                                    </label>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
        @endif
        @if($featuresConfig['sms_verification'] == '1')
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h3>SMS</h3>
                </div>
                <div class="card-body">
                    <form action="" method="POST" class="settingsUpdateForm">


                    </form>
                </div>
            </div>
        </div>
        @endif
    </div>
@endsection
