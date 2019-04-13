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
    <div class="footer">
        <div class="container">
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="about">
                            <div class="about-content">
                                <div class="me-image">

                                </div>
                                <div class="about-text">
                                    Aptent class venenatis arcu curabitur luctus phasellus eleifend aliquam, dictumst fermentum finibus vivamus conubia nunc ante aliquet, mauris quam in cubilia et taciti nulla.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <contact-me></contact-me>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
