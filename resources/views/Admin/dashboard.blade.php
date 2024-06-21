@extends("Admin.layouts.main")
@section("title", "Settings")
@section("content")
    <h1 class="h3 mb-0 text-gray-800">Settings</h1>
    @if (session('success'))
        <div class="alert alert-success mt-4">
            {{ session('success') }}
        </div>
    @endif
    <br>
    <form action="/admin/store-settings" method="POST" class="card p-4">
        @csrf
        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Teaser Url</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Main Category Section</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#pills-contact" type="button" role="tab" aria-controls="pills-contact" aria-selected="false">Home Restaurants</button>
            </li>
        </ul>
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                <div class="form-group">
                    <input type="text" name="teaser_url" id="teaser_url" class="form-control" placeholder="Teaser Url" value="{{(isset($settingsArray["teaser_url"]) && $settingsArray["teaser_url"]["value"]) ? $settingsArray["teaser_url"]["value"] : ''}}">
                </div>
            </div>
            <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                @php
                    $categories = App\Models\Category::all();
                @endphp

                <div class="form-group">
                    <label for="main_cat">Choose Category</label>
                    <select id="main_cat" name="main_cat" class="form-control" value="{{ (isset($settingsArray["main_cat"]) && $settingsArray["main_cat"]["value"]) ? $settingsArray["main_cat"]["value"] : '' }}">
                        <option value="">Choose Category</option>
                        @foreach($categories as $cat)
                            <option value="{{$cat->id}}"
                                {{ (isset($settingsArray["main_cat"]) && $settingsArray["main_cat"]["value"] == $cat->id) ? "selected" : '' }}>
                                {{$cat->title}}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
                @php
                    $restaurants = App\Models\Restaurant::all();
                @endphp

                <div class="form-group">
                    <label for="main_restaurants">Choose Restaurant</label>
                    <select id="main_restaurants" name="main_restaurants[]" class="form-control" value="{{ (isset($settingsArray["main_restaurants"]) && $settingsArray["main_restaurants"]["value"]) ? @json($settingsArray["main_restaurants"]["value"]) : '' }}" multiple>
                        @foreach($restaurants as $cat)
                            <option value="{{$cat->id}}"
                                {{ (isset($settingsArray["main_restaurants"]) && $settingsArray["main_restaurants"]["value"] == $cat->id) ? "selected" : '' }}>
                                {{$cat->title}}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-success">Save Settings</button>
    </form>
@endSection
