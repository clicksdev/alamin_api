@extends('Admin.layouts.main')

@section("title", "Locations - Create")
@section("loading_txt", "Create")

@section("content")
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Create Location</h1>
    <a href="{{ route("admin.locations.show") }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
            class="fas fa-arrow-left fa-sm text-white-50"></i> Back</a>
</div>

<div class="card p-3 mb-3" id="locations_wrapper">
    <div class="d-flex justify-content-between" style="gap: 16px">
        <div class="w-100">
            <div class="form-group w-100">
                <label for="Title" class="form-label">Title</label>
                <input type="text" class="form-control" id="Title"  placeholder="Location Title" v-model="title">
            </div>
            <div class="form-group w-100">
                <label for="Title" class="form-label">Sub Title</label>
                <input type="text" class="form-control" id="Title"  placeholder="Sub Title" v-model="sub_title">
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
                <label for="Title" class="form-label">Url</label>
                <input type="text" class="form-control" id="Title"  placeholder="Location Map Url" v-model="url">
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
        <input type="file" class="form-control d-none" id="thumbnail"  placeholder="Location Thumbnail Picture" @change="handleChangeThumbnail">
        </div>
        </div>
    <div class="form-group">
        <button class="btn btn-success w-25" @click="create" style="margin: auto; display: block">Create</button>
    </div>
</div>

@endSection

@section("scripts")
<script>
const { createApp, ref } = Vue

createApp({
    data() {
        return {
            title: null,
            sub_title: null,
            title_ar: "",
            sub_title_ar: "",
            thumbnail_path: null,
            thumbnail: null,
            url: null,
        }
    },
    methods: {
        handleChangeThumbnail(event) {
            this.thumbnail = event.target.files[0]
            this.thumbnail_path = URL.createObjectURL(event.target.files[0])
        },
        async create() {
            $('.loader').fadeIn().css('display', 'flex')
            try {
                const response = await axios.post(`{{ route("admin.locations.create") }}`, {
                    title: this.title,
                    sub_title: this.sub_title,
                    title_ar: this.title_ar,
                    sub_title_ar: this.sub_title_ar,
                    url: this.url,
                    thumbnail: this.thumbnail,
                },
                {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                },
                );
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
                        window.location.href = '{{ route("admin.locations.show") }}'
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
}).mount('#locations_wrapper')
</script>
@endSection
