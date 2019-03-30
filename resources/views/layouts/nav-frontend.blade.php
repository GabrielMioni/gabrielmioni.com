@section('nav')
    <nav class="navbar navbar-expand-md navbar-frontend">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <h2>
                    {{ config('app.name', 'Laravel') }}
                </h2>
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav mr-auto">

                </ul>

                <!-- Right Side Of Navbar -->
                I am nav
            </div>
        </div>
    </nav>
@stop