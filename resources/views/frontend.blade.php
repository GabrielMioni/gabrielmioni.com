@extends('layouts.app')
@include('layouts.nav-frontend')

@section('page-style')
    <style>
        #app .about .about-content .about-image {
            background-image: url({{ bgImage($avatar) }});
        }
    </style>
@stop

@section('content')
    <div class="container-fluid front-end-hero">
        <div class="hero-cta">
            @if (checkBladeData($tagLine))
                <h2>
                    {!! nl2br($tagLine) !!}
                </h2>
            @endif
            <div class="row">
                <div class="col-sm-6">
                    <a href="">My Projects</a>
                </div>
                <div class="col-sm-6">
                    <a href="">Contact Me</a>
                </div>
            </div>
        </div>
    </div>
    <div class="container projects py-4">
        <div class="row">
            <div class="col-sm-12">
                <h2>Projects</h2>
            </div>
        </div>
        <front-end-projects></front-end-projects>
    </div>
    <div class="footer">
        <div class="container">
            <div class="col-sm-12">
                <div class="row">
                    @if (checkBladeData($avatar) || checkBladeData($aboutMe))
                        <div class="col-lg-4">
                            <div class="about">
                                <div class="about-content">
                                    <h3>About me</h3>
                                    @if (checkBladeData($avatar))
                                        <div class="about-image"></div>
                                    @endif
                                    @if (checkBladeData($aboutMe))
                                        <div class="about-text">
                                            {!! $aboutMe !!}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                    @if (!(checkBladeData($avatar) || checkBladeData($aboutMe)))
                        <div class="col-lg-12">
                    @endif
                    @if (checkBladeData($avatar) || checkBladeData($aboutMe))
                        <div class="col-lg-8">
                            <contact-me></contact-me>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
