@extends('layouts.default')

@section('title', 'Main Page')

@section('content')
    <div class="jumbotron">
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <h1 class="text-lg-center display-2 mb-4">{{ __('frontend.site_title') }}</h1>
                    <p class="mb-4">{{ __('frontend.site_description') }}</p>
                    <div class="d-flex justify-content-center mb-4">
                        <a class="btn btn-primary btn-lg" href="#" role="button">{{ __('frontend.force') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
