@extends('layouts.Frontend.master')
@section('title')
    {{ strtoupper($page->page_name) }}
@endsection
@section('content')
<div class="content">
    <section class="contact_us_area section_padding">
        <div class="container">
            <div class="row">
                <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="section_title text-center">
                        <h2><strong>{{ $page->page_name }}</strong></h2>
                        <button onclick="showDescription('english')" class="btn btn-outline-dark btn-sm">English</button>
                        <button onclick="showDescription('bangla')" class="btn btn-outline-dark btn-sm">বাংলা</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="container section_padding_top"> <div id="english_desc" class="row gy-4 gx-3">
                <div class="col-12">
                    <p class="font-weight-bold">{!! nl2br(e($page->english_description)) !!}</p>
                </div>
            </div>

            <div id="bangla_desc" class="row gy-4 gx-3" style="display: none;">
                <div class="col-12">
                    <p class="font-weight-bold">{!! nl2br(e($page->bangla_description)) !!}</p>
                </div>
            </div>

        </div>
    </section>
</div>


    {{-- <div class="content">
        <section class="contact_us_area section_padding">
            <div class="container">
                <div class="row">
                    <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="section_title text-center">
                            <h2><strong>{{ $page->page_name }}</strong></h2>
                            <a href="">English</a>
                            <a href="">বাংলা</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container section_padding_top">
                <div class="row gy-4 gx-3">
                    <p class="font-weight-bold">{!! nl2br(e($page->english_description)) !!}</p>
                </div>
                <div class="row gy-4 gx-3">
                    <p class="font-weight-bold">{!! nl2br(e($page->bangla_description)) !!}</p>
                </div>
            </div>
        </section>
    </div> --}}
@endsection
@section('script')
    <script>
    function showDescription(lang) {
        const engDiv = document.getElementById('english_desc');
        const bngDiv = document.getElementById('bangla_desc');

        if (lang === 'english') {
            engDiv.style.display = 'block';
            bngDiv.style.display = 'none';
        } else {
            engDiv.style.display = 'none';
            bngDiv.style.display = 'block';
        }
    }
</script>
@endsection

