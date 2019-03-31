@extends('layouts.app')
@include('layouts.nav-frontend')

@section('content')
    <div class="container-fluid front-end-hero">
        <div class="hero-cta">
            <h2>
                Web Developer<br>
                Available for hire
            </h2>
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
@endsection
