@extends('Admin.layouts.main')

@section("title", "Events - Edit")
@section("loading_txt", "Edit")

@section("content")
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Edit Event</h1>
    <a href="{{ route("admin.events.show") }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
            class="fas fa-arrow-left fa-sm text-white-50"></i> Back</a>
</div>

<div class="card p-3 mb-3" id="events_wrapper">
    <div class="d-flex justify-content-between" style="gap: 16px">
        <div class="w-100">
            <div class="form-group w-100">
                <label for="Title" class="form-label">Title</label>
                <input type="text" class="form-control" id="Title"  placeholder="Event Title" v-model="title">
            </div>
            <div class="form-group w-100">
                <label for="subTitle" class="form-label">Sub Title</label>
                <input type="text" class="form-control" id="subTitle"  placeholder="Sub Title" v-model="sub_title">
            </div>
            <div class="form-group w-100">
                <label for="url" class="form-label">Url</label>
                <input type="text" class="form-control" id="url"  placeholder="Event Url" v-model="url">
            </div>
            <div class="form-group w-100">
                <label for="location" class="form-label">Locations</label>
                @php
                    $locations = App\Models\Location::all();
                @endphp
                <select name="locations" id="location" class="form-control"  v-model="location_id">
                    <option value="" selected disabled>select ---</option>
                    @foreach ($locations as $item)
                        <option value="{{$item->id}}">{{ $item->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group w-100">
                <label for="categories" class="form-label">Categories</label>
                @php
                    $categories = App\Models\Category::all();
                @endphp
                <select name="categories" id="categories" class="form-control" multiple v-model="category_ids" @change="console.log(category_ids)">
                    @foreach ($categories as $category)
                        <option value="{{$category->id}}">{{ $category->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group w-100">
                <label for="date_from" class="form-label">Date From</label>
                <input type="datetime-local" class="form-control" id="date_from" v-model="date_from">
            </div>
            <div class="form-group w-100">
                <label for="date_to" class="form-label">Date To</label>
                <input type="datetime-local" class="form-control" id="date_to" v-model="date_to">
            </div>
        </div>
        <div class="form-group pt-4 pb-4" style="width: max-content; height: 300px;min-width: 250px">
            <label for="thumbnail" class="w-100 h-100">
                <svg v-if="!thumbnail && !thumbnail_path" xmlns="http://www.w3.org/2000/svg" className="icon icon-tabler icon-tabler-photo-up" width="24" height="24" viewBox="0 0 24 24" strokeWidth="1.5" style="width: 100%; height: 100%; object-fit: cover; padding: 10px; border: 1px solid; border-radius: 1rem" stroke="#043343" fill="none" strokeLinecap="round" strokeLinejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M15 8h.01" />
                    <path d="M12.5 21h-6.5a3 3 0 0 1 -3 -3v-12a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v6.5" />
                    <path d="M3 16l5 -5c.928 -.893 2.072 -.893 3 0l3.5 3.5" />
                    <path d="M14 14l1 -1c.679 -.653 1.473 -.829 2.214 -.526" />
                    <path d="M19 22v-6" />
                    <path d="M22 19l-3 -3l-3 3" />
                </svg>
                <img v-if="thumbnail_path" :src="thumbnail_path" style="width: 100%; height: 100%; object-fit: cover; padding: 10px; border: 1px solid; border-radius: 1rem" />
            </label>
        <input type="file" class="form-control d-none" id="thumbnail"  placeholder="Event Thumbnail Picture" @change="handleChangeThumbnail">
        </div>

        </div>
            <div class="form-group pb-4" style="width: max-content; height: 350px;min-width: 100%">
                <h2>Cover image</h2>
                <label for="cover" class="w-100 h-100">
                    <svg v-if="!cover && !cover_path" xmlns="http://www.w3.org/2000/svg" className="icon icon-tabler icon-tabler-photo-up" width="24" height="24" viewBox="0 0 24 24" strokeWidth="1.5" style="width: 100%; height: 100%; object-fit: cover; padding: 10px; border: 1px solid; border-radius: 1rem" stroke="#043343" fill="none" strokeLinecap="round" strokeLinejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M15 8h.01" />
                        <path d="M12.5 21h-6.5a3 3 0 0 1 -3 -3v-12a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v6.5" />
                        <path d="M3 16l5 -5c.928 -.893 2.072 -.893 3 0l3.5 3.5" />
                        <path d="M14 14l1 -1c.679 -.653 1.473 -.829 2.214 -.526" />
                        <path d="M19 22v-6" />
                        <path d="M22 19l-3 -3l-3 3" />
                    </svg>
                    <img v-if="cover_path" :src="cover_path" style="width: 100%; height: 100%; object-fit: cover; padding: 10px; border: 1px solid; border-radius: 1rem" />
                </label>
                <input type="file" class="form-control d-none" id="cover"  placeholder="Category cover Picture" @change="handleChangecover">
            </div>
            <div style="display: grid;gap: 16px; margin-top: 24px;grid-template-columns: 2fr 1fr; width: 100%">
                <div class="form-group pb-4" style="width: max-content; height: 350px;width: 100%">
                    <h2>Landscape image</h2>
                    <label for="landscape" class="w-100 h-100">
                        <svg v-if="!landscape && !landscape_path" xmlns="http://www.w3.org/2000/svg" className="icon icon-tabler icon-tabler-photo-up" width="24" height="24" viewBox="0 0 24 24" strokeWidth="1.5" style="width: 100%; height: 100%; object-fit: cover; padding: 10px; border: 1px solid; border-radius: 1rem" stroke="#043343" fill="none" strokeLinecap="round" strokeLinejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M15 8h.01" />
                            <path d="M12.5 21h-6.5a3 3 0 0 1 -3 -3v-12a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v6.5" />
                            <path d="M3 16l5 -5c.928 -.893 2.072 -.893 3 0l3.5 3.5" />
                            <path d="M14 14l1 -1c.679 -.653 1.473 -.829 2.214 -.526" />
                            <path d="M19 22v-6" />
                            <path d="M22 19l-3 -3l-3 3" />
                        </svg>
                        <img v-if="landscape_path" :src="landscape_path" style="width: 100%; height: 100%; object-fit: cover; padding: 10px; border: 1px solid; border-radius: 1rem" />
                    </label>
                    <input type="file" class="form-control d-none" id="landscape"  placeholder="Category landscape Picture" @change="handleChangelandscape">
                </div>
                <div class="form-group pb-4" style="width: max-content; height: 350px;width: 100%">
                    <h2>Portrait image</h2>
                    <label for="portrait" class="w-100 h-100">
                        <svg v-if="!portrait && !portrait_path" xmlns="http://www.w3.org/2000/svg" className="icon icon-tabler icon-tabler-photo-up" width="24" height="24" viewBox="0 0 24 24" strokeWidth="1.5" style="width: 100%; height: 100%; object-fit: cover; padding: 10px; border: 1px solid; border-radius: 1rem" stroke="#043343" fill="none" strokeLinecap="round" strokeLinejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M15 8h.01" />
                            <path d="M12.5 21h-6.5a3 3 0 0 1 -3 -3v-12a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v6.5" />
                            <path d="M3 16l5 -5c.928 -.893 2.072 -.893 3 0l3.5 3.5" />
                            <path d="M14 14l1 -1c.679 -.653 1.473 -.829 2.214 -.526" />
                            <path d="M19 22v-6" />
                            <path d="M22 19l-3 -3l-3 3" />
                        </svg>
                        <img v-if="portrait_path" :src="portrait_path" style="width: 100%; height: 100%; object-fit: cover; padding: 10px; border: 1px solid; border-radius: 1rem" />
                    </label>
                    <input type="file" class="form-control d-none" id="portrait"  placeholder="Category portrait Picture" @change="handleChangeportrait">
                </div>
            </div>
            <br>
            <br>
    <div class="form-group">
        <br>
        <button class="btn btn-success w-25" @click="updateEvent" style="margin: auto; display: block">Update</button>
    </div>
</div>

@endSection

@section("scripts")
<script>
const { createApp, ref } = Vue

createApp({
    data() {
        return {
            title: "{{$event->title}}",
            sub_title: "{{$event->sub_title}}",
            thumbnail_path: "{{$event->thumbnail}}",
            thumbnail: null,
            location_id: "{{$event->location_id}}",
            url: "{{$event->url}}",
            cover_path: "{{$event->cover}}",
            cover: null,
            landscape_path: "{{$event->landscape}}",
            landscape: null,
            portrait_path: "{{$event->portrait}}",
            portrait: null,
            date_from: "{{$event->date_from}}",
            date_to: "{{$event->date_to}}",
            category_ids: @json($event->event_categories->pluck("id")) || []
        }
    },
    methods: {
        handleChangeThumbnail(event) {
            this.thumbnail = event.target.files[0]
            this.thumbnail_path = URL.createObjectURL(event.target.files[0])
        },
        handleChangecover(event) {
            this.cover = event.target.files[0]
            this.cover_path = URL.createObjectURL(event.target.files[0])
        },
        handleChangelandscape(event) {
            this.landscape = event.target.files[0]
            this.landscape_path = URL.createObjectURL(event.target.files[0])
        },
        handleChangeportrait(event) {
            this.portrait = event.target.files[0]
            this.portrait_path = URL.createObjectURL(event.target.files[0])
        },
        async updateEvent() {
            $('.loader').fadeIn().css('display', 'flex')
            try {
                const response = await axios.post(`{{ route("admin.events.update") }}`, {
                    id: "{{$event->id}}",
                    title: this.title,
                    sub_title: this.sub_title,
                    url: this.url,
                    thumbnail: this.thumbnail,
                    location_id: this.location_id,
                    cover: this.cover,
                    landscape: this.landscape,
                    portrait: this.portrait,
                    date_from: this.date_from,
                    date_to: this.date_to,
                    categories: JSON.stringify(this.category_ids),
                }, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                });

                if (response.data.status === true) {
                    document.getElementById('errors').innerHTML = ''
                    let error = document.createElement('div')
                    error.classList = 'success'
                    error.innerHTML = response.data.message
                    document.getElementById('errors').append(error)
                    $('#errors').fadeIn('slow')
                    setTimeout(() => {
                        $('.loader').fadeOut()
                        $('#errors').fadeOut('slow')
                        window.location.href = '{{ route("admin.events.show") }}'
                    }, 1300);
                } else {
                    $('.loader').fadeOut()
                    document.getElementById('errors').innerHTML = ''
                    $.each(response.data.errors, function (key, value) {
                        let error = document.createElement('div')
                        error.classList = 'error'
                        error.innerHTML = value
                        document.getElementById('errors').append(error)
                    });
                    $('#errors').fadeIn('slow')
                    setTimeout(() => {
                        $('#errors').fadeOut('slow')
                    }, 5000);
                }
            } catch (error) {
                document.getElementById('errors').innerHTML = ''
                let err = document.createElement('div')
                err.classList = 'error'
                err.innerHTML = 'server error try again later'
                document.getElementById('errors').append(err)
                $('#errors').fadeIn('slow')
                $('.loader').fadeOut()

                setTimeout(() => {
                    $('#errors').fadeOut('slow')
                }, 3500);

                console.error(error);
            }
        }
    },
    created() {
    }
}).mount('#events_wrapper')
</script>
@endSection
