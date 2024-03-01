
@extends('layouts.default')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <h1 class="text-lg-center display-2 mb-4">{{ __('frontend.planets_table_title') }}</h1>

            @if(isset($planets) && $planets->count() > 0)
                <div class="col-4">
                    @include('planets.partials.filter')
                </div>

                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        @foreach (__('frontend.planets_table_columns') as $column)
                            <th scope="col">{{ $column }}</th>
                        @endforeach
                    </tr>
                    </thead>
                    @foreach ($planets as $index => $planet)
                        <tr>
                            <td>{{ $planet->id }}</td>
                            <td>{{ $planet->name }}</td>
                            <td>{{ $planet->diameter }}</td>
                            <td>{{ $planet->rotation_period }}</td>
                            <td>{{ $planet->gravity }}</td>
                            <td>{{ $planet->climate }}</td>
                            <td>{{ $planet->terrain }}</td>
                            <td>{{ $planet->population }}</td>
                        </tr>
                    @endforeach
                </table>

                <div class="d-flex justify-content-center mt-3">
                    {{ $planets->links() }}
                </div>
            @else
                <p class="text-center mb-4">{{ __('frontend.planets_table_no_planets') }}</p>
            @endif
        </div>
    </div>
@endsection
