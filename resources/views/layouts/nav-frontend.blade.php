@section('nav')
    <nav class="navbar navbar-expand-md navbar-frontend">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <h2>
                    {{ config('app.name', 'Laravel') }}
                </h2>
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}" tabindex="-1">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto">
                    <li><a href="#projects">Projects</a></li>
                    <li><a href="#footer">About</a></li>
                    <li><a href="#footer">Contact</a></li>
                </ul>
                <a class="home-link d-none d-md-flex" href="#"><i class="fas fa-arrow-alt-circle-up"></i></a>
            </div>
        </div>
    </nav>
@stop
