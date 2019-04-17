@extends('layouts.app')
@include('layouts.nav-frontend')

@section('content')
    <div class="container-fluid front-end-hero">
        <div class="hero-cta">
            @if (checkBladeData($tag_line))
                <h2>
                    {{$tag_line}}
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
                    <div class="col-lg-4">
                        @if (checkBladeData($avatar) || checkBladeData($about_me))
                            <div class="about">
                                <div class="about-content">
                                    <h3>About me</h3>
                                    @if (checkBladeData($avatar))
                                        <div class="about-image"></div>
                                    @endif
                                    @if (checkBladeData($about_me))
                                        <div class="about-text">
                                            {!! $about_me !!}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="col-lg-8">
                        <contact-me></contact-me>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
