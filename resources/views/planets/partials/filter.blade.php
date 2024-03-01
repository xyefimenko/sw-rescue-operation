<form method="GET" action="{{ route('planets') }}" class="mb-4">
    <div class="form-group mb-3">
        <label for="diameter">{{ __('frontend.form.diameter') }}</label>
        <input type="text" class="form-control" name="diameter" id="diameter" aria-describedby="diameterHelp" placeholder="Enter required diameter">
        <small id="diameterHelp" class="form-text text-muted">{{ __('frontend.form.filter_diameter') }}</small>
    </div>
    <div class="form-group mb-3">
        <label for="rotation_period">{{ __('frontend.form.rotation_period') }}</label>
        <input type="text" class="form-control" name="rotation_period" id="rotation" aria-describedby="rotationHelp" placeholder="Enter required rotation period">
        <small id="rotationHelp" class="form-text text-muted">{{ __('frontend.form.filter_rotation_period') }}</small>
    </div>
    <div class="form-group mb-3">
        <label for="gravity">{{ __('frontend.form.gravity') }}</label>
        <input type="text" class="form-control" name="gravity" id="gravity" aria-describedby="gravityHelp" placeholder="Enter required gravity">
        <small id="gravityHelp" class="form-text text-muted">{{ __('frontend.form.filter_gravity') }}</small>
    </div>
    <div class="form-group mb-3">
        <label for="planet_search">{{ __('frontend.form.planet_search') }}</label>
        <input type="text" class="form-control" name="planet_search" id="planet_search" aria-describedby="planetSearchHelp" placeholder="Search">
        <small id="planetSearchHelp" class="form-text text-muted">{{ __('frontend.form.planet_search_description') }}</small>
    </div>
    <div class="form-group mb-3">
        <button type="submit" class="btn btn-primary">{{ __('frontend.form.filter') }}</button>
        <a href="{{ route('planets') }}" class="btn btn-secondary">{{ __('frontend.form.filter_reset') }}</a>
    </div>
</form>
