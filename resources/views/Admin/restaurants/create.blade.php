@extends('Admin.layouts.main')

@section("title", "Services - Edit")
@section("loading_txt", "Edit")

@section("content")
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Edit Service</h1>
    <a href="{{ route("admin.restaurants.show") }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
            class="fas fa-arrow-left fa-sm text-white-50"></i> Back</a>
</div>

<div class="card p-3 mb-3" id="restaurants_wrapper">
    <div class="d-flex justify-content-between" style="gap: 16px">
        <div class="w-100">
            <div class="form-group w-100">
                <label for="Title" class="form-label">Title</label>
                <input type="text" class="form-control" id="Title"  placeholder="Service Title" v-model="title">
            </div>
            <div class="form-group w-100">
                <label for="subTitle" class="form-label">Sub Title</label>
                <input type="text" class="form-control" id="subTitle"  placeholder="Sub Title" v-model="sub_title">
            </div>
            <div class="form-group w-100">
                <label for="Title" class="form-label">Title in arabic</label>
                <input type="text" class="form-control" id="Title"  placeholder="Event Title in arabic" v-model="title_ar">
            </div>
            <div class="form-group w-100">
                <label for="subTitle" class="form-label">Sub Title in arabic</label>
                <input type="text" class="form-control" id="subTitle"  placeholder="Sub Title in arabic" v-model="sub_title_ar">
            </div>
            <div class="form-group w-100">
                <label for="subTitle" class="form-label">Phone</label>
                <input type="text" class="form-control" id="subTitle"  placeholder="Phone" v-model="phone">
            </div>
            <div class="form-group w-100">
                <label for="subTitle" class="form-label">website</label>
                <input type="text" class="form-control" id="subTitle"  placeholder="website" v-model="website">
            </div>
            <div class="form-group">
                <label for="Description" class="form-label">Description</label>
                <textarea rows="5" class="form-control" id="Description"  placeholder="Description Name" style="resize: none" v-model="description">
                </textarea>
            </div>
            <div class="form-group">
                <label for="Description" class="form-label">Description in arabic</label>
                <textarea rows="5" class="form-control" id="Description"  placeholder="Description in arabic" style="resize: none" v-model="description_ar">
                </textarea>
            </div>
            <div class="form-group w-100">
                <label for="location" class="form-label">Location</label>
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
                <label for="date_from" class="form-label">Working From</label>
                <input type="text" class="form-control" id="date_from" v-model="working_from">
            </div>
            <div class="form-group w-100">
                <label for="date_to" class="form-label">Working To</label>
                <input type="text" class="form-control" id="date_to" v-model="working_to">
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
        <input type="file" class="form-control d-none" id="thumbnail"  placeholder="Service Thumbnail Picture" @change="handleChangeThumbnail">
        </div>

        </div>
    <div class="form-group">
        <br>
        <button class="btn btn-success w-25" @click="updateRestaurant" style="margin: auto; display: block">Create</button>
    </div>
</div>

@endSection

@section("scripts")
<script>
const { createApp, ref } = Vue

createApp({
    data() {
        return {
            title: "",
            sub_title: "",
            title_ar: "",
            description: "",
            description_ar: "",
            sub_title_ar: "",
            phone: "",
            website: "",
            thumbnail_path: null,
            thumbnail: null,
            location_id: null,
            working_to: null,
            working_from: null,
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
        async updateRestaurant() {
            $('.loader').fadeIn().css('display', 'flex')
            try {
                const response = await axios.post(`{{ route("admin.restaurants.create") }}`, {
                    title: this.title,
                    sub_title: this.sub_title,
                    title_ar: this.title_ar,
                    description: this.description,
                    description_ar: this.description_ar,
                    sub_title_ar: this.sub_title_ar,
                    phone: this.phone,
                    website: this.website,
                    working_from: this.working_from,
                    working_to: this.working_to,
                    photo: this.thumbnail,
                    location_id: this.location_id,
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
                        window.location.href = '{{ route("admin.restaurants.show") }}'
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
}).mount('#restaurants_wrapper')
</script>
@endSection
