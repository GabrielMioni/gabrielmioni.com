@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <profile></profile>
            </div>
        </div>
    </div>
@endsection
<script>
    import Profile from "../js/components/Profile";
    export default {
        components: {Profile}
    }
</script>
