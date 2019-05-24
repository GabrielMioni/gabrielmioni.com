@extends('layouts.app')
@include('layouts.nav-frontend')

@section('page-style')
    <script src="{{ asset('js/frontend/app.js') }}" defer></script>
    <style>
        @if (isset($avatar) && trim($avatar) !== '')
            #app .about .about-content .about-image {
                background-image: url({{ bgImage($avatar) }});
            }
        @endif
        @if (isset($hero) && trim($hero) !== '')
            #app .hero {
            background-image: url({{ bgImage($hero, 'hero') }});
        }
        @endif
        @if (isset($hero) && trim($hero) === '')
            #app .hero {
            background-image: url({{ '/background/nature-forest-trees-fog.jpeg' }});
        }
        @endif
    </style>
@stop

@section('content')
    <div class="container-fluid hero">
        <div class="hero-cta">
            @if (isset($tagLine) && trim($tagLine) !== '')
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
                <h2 id="projects">Projects</h2>
            </div>
        </div>
        <front-end-projects></front-end-projects>
    </div>
    <div id="footer" class="footer">
        <div class="container">
            <div class="col-sm-12">
                <div class="row">
                    @if (isset($avatar) && trim($avatar) !== '' || isset($aboutMe) && trim($aboutMe) !== '')
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
                    <div {{setBladeClass((isset($avatar) && trim($avatar) !== '' || isset($aboutme) && trim($aboutme) !== ''), 'col-lg-8', 'col-lg-12')}}>
                        <contact-me></contact-me>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
