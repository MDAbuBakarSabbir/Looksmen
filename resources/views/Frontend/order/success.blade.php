@extends('layouts.Frontend.master')
@section('content')
<div class="container my-5 text-center">
    <div class="card p-5 border-0 shadow-sm">
        <div class="success-icon mb-4">
            <i class="las la-check-circle text-success" style="font-size: 70px;"></i>
        </div>
        <h2>অভিনন্দন! আপনার অর্ডারটি সফলভাবে গৃহীত হয়েছে।</h2>
        <p class="h5 mt-3">অর্ডার আইডি: <strong>#{{ $order->id }}</strong></p>
        <p>শীঘ্রই আমাদের প্রতিনিধি আপনার সাথে যোগাযোগ করবেন।</p>

        <div class="mt-4">
            <a href="{{ url('/') }}" class="btn btn-primary px-4">আরও কেনাকাটা করুন</a>
            <button onclick="window.print()" class="btn btn-outline-secondary px-4 ml-2">ইনভয়েস প্রিন্ট করুন</button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if(session('order_placed'))
<script>
    Swal.fire({
        title: 'অর্ডার সফল!',
        text: 'আপনার অর্ডারটি আমরা পেয়েছি।',
        icon: 'success',
        confirmButtonText: 'ঠিক আছে'
    });
</script>
@endif
@endsection

