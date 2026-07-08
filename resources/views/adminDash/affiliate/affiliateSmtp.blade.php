@extends('layouts.Backend.master')
@section('title','Affiliate SMTP Configuration')
    
@section('content')
<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header">
                <h3>Affiliate SMTP Configuration</h3>
            </div>
            <div class="card-body">
                <div class="templete">
                    <label for="verifyMail">Affiliate Verification Mail Templete</label>
                    <textarea name="verifyMail" id="verifyMail" cols="30" rows="10" class="form-control"></textarea>
                    <button class="btn btn-primary mt-2">Save</button>    
                </div>  
                <div class="templete">
                    <label for="verifyMail">Affiliate Registration Mail Templete</label>
                    <textarea name="verifyMail" id="verifyMail" cols="30" rows="10" class="form-control"></textarea>
                    <button class="btn btn-primary mt-2">Save</button>    
                </div>  
                <div class="templete">
                    <label for="verifyMail">Affiliate Approval Mail Templete</label>
                    <textarea name="verifyMail" id="verifyMail" cols="30" rows="10" class="form-control"></textarea>
                    <button class="btn btn-primary mt-2">Save</button>    
                </div>  
                <div class="templete">
                    <label for="verifyMail">Affiliate Payment Mail Templete</label>
                    <textarea name="verifyMail" id="verifyMail" cols="30" rows="10" class="form-control"></textarea>
                    <button class="btn btn-primary mt-2">Save</button>    
                </div>  
            </div>
        </div>
    </div>
</div>
@endsection