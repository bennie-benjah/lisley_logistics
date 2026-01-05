@extends('layouts.app')

@section('content')
<div style="text-align:center; padding:50px;">
    <h1>404 | Page Not Found</h1>
    <p>Sorry, the page you are looking for does not exist.</p>
    <a href="{{ url('/') }}" class="btn btn-primary">Go Home</a>
</div>
@endsection
