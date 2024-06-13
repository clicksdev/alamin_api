@extends('Admin.layouts.main')

@section("title", "Events - Top")
@section("loading_txt", "Top")

@section("content")
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Top Event</h1>
    <a href="{{ route("admin.events.show") }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
            class="fas fa-arrow-left fa-sm text-white-50"></i> Back</a>
</div>

<div class="card p-3 mb-3" id="events_wrapper">
    <div class="d-flex justify-content-between" style="gap: 16px">
        <div class="w-100">
            <div class="d-flex" style="gap: 1rem">
                <div class="d-flex w-50 align-items-end">
                    <div class="form-group w-100 mr-2">
                        <label for="location" class="form-label">Ads</label>
                        @php
                            $ads = App\Models\Ad::all();
                        @endphp
                        <select name="locations" id="location" class="form-control" v-model="ad">
                            <option value="" selected disabled>select ---</option>
                            @foreach ($ads as $item)
                                <option value="{{$item->id}}">{{ $item->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button class="btn btn-primary" style="margin-bottom: 1rem" @click="handleAddAd">Add</button>
                </div>
                <div class="d-flex w-50 align-items-end">
                    <div class="form-group w-100 mr-2">
                        <label for="location" class="form-label">Events</label>
                        @php
                            $evets = App\Models\Event::all();
                        @endphp
                        <select name="locations" id="location" class="form-control" v-model="event">
                            <option value="" selected disabled>select ---</option>
                            @foreach ($evets as $item)
                                <option value="{{$item->id}}">{{ $item->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button class="btn btn-primary" style="margin-bottom: 1rem" @click="handleAddEvent">Add</button>
                </div>
            </div>

            <div style="display: flex; gap: 16px; flex-wrap: wrap">
                <div v-for="(item, index) in topEvents" :key="item.id" style="width: 25%;min-width: 250px;position: relative">
                    <div style="position: absolute;width: 100%;height: 250px;display: flex;flex-direction: column;justify-content: space-between;">
                        <button class="btn btn-danger" @click="deleteItem(item)">Remove</button>
                        <div class="d-flex justify-content-between w-100" style="display: flex; justify-content: space-between">
                            <button class="btn btn-primary" @click="moveUp(index)">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-caret-left" width="20" height="20" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M14 6l-6 6l6 6v-12" />
                                  </svg>
                            </button>

                            <button class="btn btn-primary" @click="moveDown(index)">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-caret-right" width="20" height="20" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M10 18l6 -6l-6 -6v12" />
                                  </svg>
                            </button>
                        </div>
                    </div>
                    <img :src="item.type == 1 ? item.item.thumbnail : item.item.photo_path" style="width: 100%;height: 250px;object-fit: cover;border-radius: 8px;">
                    <span style="text-align: center;width: 100%;display: block;">@{{item.item.title}}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <br>
        <button class="btn btn-success w-25" @click="updateTopEvent" style="margin: auto; display: block">Update</button>
    </div>
</div>

@endSection

@section("scripts")
<script>
const { createApp, ref } = Vue

createApp({
    data() {
        return {
            topEvents: @json($events),
            ad: "",
            event: "",
            ads: @json($ads),
            evets: @json($evets),
        }
    },
    methods: {
        deleteItem(item) {
        const index = this.topEvents.indexOf(item);
        if (index > -1) {
            // Remove item from data
            this.topEvents.splice(index, 1);
            // Call your backend API to delete the item (if needed)
        }
        },
        moveUp(index) {
        if (index > 0) {
            const temp = this.topEvents[index];
            this.topEvents[index] = this.topEvents[index - 1];
            this.topEvents[index - 1] = temp;
        }
        },
        moveDown(index) {
        if (index < this.topEvents.length - 1) {
            const temp = this.topEvents[index];
            this.topEvents[index] = this.topEvents[index + 1];
            this.topEvents[index + 1] = temp;
        }
        },
        handleAddAd() {
            let item = this.ads.find(ad => ad.id == this.ad);
            if (item) {
                let x = {}
                x['type'] = 2
                x['item_id'] = item.id
                x['item'] = item
                this.topEvents.push(x)
            }
        },
        handleAddEvent() {
            let item = this.evets.find(event => event.id == this.event);
            if (item) {
                let x = {}
                x['type'] = 1
                x['item_id'] = item.id
                x['item'] = item
                this.topEvents.push(x)
            }
        },
        async updateTopEvent() {
            $('.loader').fadeIn().css('display', 'flex')
            try {
                const response = await axios.post(`{{ route("admin.events.settop") }}`, {
                    events: this.topEvents
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
                        window.location.reload()
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
        console.log(this.topEvents);
    }
}).mount('#events_wrapper')
</script>
@endSection
