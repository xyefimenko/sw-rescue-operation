@extends('layouts.default')

@section('title', 'Main Page')

@section('content')
    <div class="jumbotron">
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <h1 class="text-lg-center display-2 mb-4">{{ __('frontend.site_title') }}</h1>
                    <p class="mb-4">{{ __('frontend.site_description') }}</p>
                    <p class="text-center mb-4">{{ __('frontend.sync_command_description') }}</p>
                    <div class="d-flex justify-content-center mb-4">
                        <form method="POST" action="{{ route('trigger.command') }}">
                            @csrf
                            <button type="submit" class="btn btn-primary">{{ __('frontend.force') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
