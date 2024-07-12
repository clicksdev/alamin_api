<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\HandleResponseTrait;
use App\Models\Setting;
use App\Models\Category;
use App\Models\Sponsor;
use App\Models\Event;
use App\Models\Restaurant;
use Carbon\Carbon;

class HomeController extends Controller
{
    use HandleResponseTrait;

    public function getHomeData() {
        $today = Carbon::today("GMT+3")->endOfDay();  // Today at 00:00:00
        $tomorrow = $today->copy()->addDay()->endOfDay();  // Tomorrow at 00:00:00
        $dayAfterTomorrow = $tomorrow->copy()->addDay()->endOfDay();  // Day after tomorrow at 00:00:00

        $settings = Setting::all();

        $settingsArray = $settings->mapWithKeys(function ($setting) {

            return [
                $setting->key => [
                    'value'     => $setting->value,
                ]
            ];
        })->toArray();

        $teaser_url = (isset($settingsArray["teaser_url"]) && $settingsArray["teaser_url"]["value"]) ? $settingsArray["teaser_url"]["value"] : '';
        $google_play_url = (isset($settingsArray["google_play_url"]) && $settingsArray["google_play_url"]["value"]) ? $settingsArray["google_play_url"]["value"] : '';
        $play_store_url = (isset($settingsArray["play_store_url"]) && $settingsArray["play_store_url"]["value"]) ? $settingsArray["play_store_url"]["value"] : '';
        $facebook_url = (isset($settingsArray["facebook_url"]) && $settingsArray["facebook_url"]["value"]) ? $settingsArray["facebook_url"]["value"] : '';
        $instagram_url = (isset($settingsArray["instagram_url"]) && $settingsArray["instagram_url"]["value"]) ? $settingsArray["instagram_url"]["value"] : '';
        $linkedin_url = (isset($settingsArray["linkedin_url"]) && $settingsArray["linkedin_url"]["value"]) ? $settingsArray["linkedin_url"]["value"] : '';

        $main_cat = (isset($settingsArray["main_cat"]) && $settingsArray["main_cat"]["value"]) ? Category::with(["events" => function ($q) {
            $q->latest()->with("location");
        }])->find($settingsArray["main_cat"]["value"]) : null;
        $amazing_sponsors = Sponsor::where("isTop", 1)->orWhere("isTop", 2)->get();

        // Get today's events
        $todayEvents = Event::select("id", "title", "sub_title", "title_ar", "sub_title_ar", "cover", "thumbnail", "landscape", "portrait", "url", "date_from", "date_to", "location_id")
                            ->with(["event_categories", 'relatedEvents' => function($query) {
                                $query->select("id", "title", "sub_title", "title_ar", "sub_title_ar", "cover", "thumbnail", "landscape", "portrait", "url", "date_from", "date_to", "location_id")
                                    ->where('date_to', '>=', now("GMT+3")); // Ensure active events only
                            }, "location"])
                            ->whereDate('date_from', '<=', $today)
                            ->whereDate('date_to', '>=', $today)
                            ->get();

                            // Get tomorrow's events
                            $tomorrowEvents = Event::select("id", "title", "sub_title", "title_ar", "sub_title_ar", "cover", "thumbnail", "landscape", "portrait", "url", "date_from", "date_to", "location_id")
                            ->with(["event_categories", 'relatedEvents' => function($query) {
                                    $query->select("id", "title", "sub_title", "title_ar", "sub_title_ar", "cover", "thumbnail", "landscape", "portrait", "url", "date_from", "date_to", "location_id")
                                    ->where('date_to', '>=', now("GMT+3")); // Ensure active events only
                                }, "location"])
                                ->whereDate('date_from', '<=', $tomorrow)
                                ->whereDate('date_to', '>=', $tomorrow)
                                ->get();

                                // Get upcoming events after tomorrow
        $upcomingEvents = Event::select("id", "title", "sub_title", "title_ar", "sub_title_ar", "cover", "thumbnail", "landscape", "portrait", "url", "date_from", "date_to", "location_id")
                                ->with(["event_categories", 'relatedEvents' => function($query) {
                                    $query->select("id", "title", "sub_title", "title_ar", "sub_title_ar", "cover", "thumbnail", "landscape", "portrait", "url", "date_from", "date_to", "location_id")
                                    ->where('date_to', '>=', now("GMT+3")); // Ensure active events only
                                }, "location"])
                                ->whereDate('date_from', '<=', $dayAfterTomorrow)
                                ->whereDate('date_to', '>=', $dayAfterTomorrow)
                               ->get();

        $main_restaurants = (isset($settingsArray["main_restaurants"]) && $settingsArray["main_restaurants"]["value"]) ? Restaurant::whereIn("id", json_decode($settingsArray["main_restaurants"]["value"]))->get() : null;
        $all_sponsors = Sponsor::where("isTop", false)->get();

        return $this->handleResponse(
            true,
            "عملية ناجحة",
            [],
            [
                "teaser_url" => $teaser_url,
                "google_play_url" => $google_play_url,
                "play_store_url" => $play_store_url,
                "facebook_url" => $facebook_url,
                "instagram_url" => $instagram_url,
                "linkedin_url" => $linkedin_url,
                "main_cat" => $main_cat,
                "amazing_sponsors" => $amazing_sponsors,
                'today_events' => $todayEvents,
                'tomorrow_events' => $tomorrowEvents,
                'upcoming_events' => $upcomingEvents,
                'main_restaurants' => $main_restaurants,
                'all_sponsors' => $all_sponsors,
            ],
            [
                "يبدا مسار الصورة من بعد الدومين مباشرا"
            ]
        );

    }
    public function getEventsScedual() {
        $today = Carbon::today("GMT+3")->endOfDay();  // Today at 00:00:00
        $tomorrow = $today->copy()->addDay()->endOfDay();  // Tomorrow at 00:00:00
        $dayAfterTomorrow = $tomorrow->copy()->addDay()->endOfDay();  // Day after tomorrow at 00:00:00

        $settings = Setting::all();

        // Get today's events
        $todayEvents = Event::select("id", "title", "sub_title", "title_ar", "sub_title_ar", "cover", "thumbnail", "landscape", "portrait", "url", "date_from", "date_to", "location_id")
                            ->with(["event_categories", 'relatedEvents' => function($query) {
                                $query->select("id", "title", "sub_title", "title_ar", "sub_title_ar", "cover", "thumbnail", "landscape", "portrait", "url", "date_from", "date_to", "location_id")
                                    ->where('date_to', '>=', now("GMT+3")); // Ensure active events only
                            }, "location"])
                            ->whereDate('date_from', '<=', $today)
                            ->whereDate('date_to', '>=', $today)
                            ->get();

        foreach ($todayEvents as $event) {
            if ($event) {
                $event->time_from = Carbon::parse($event->date_from)->format('h:i A');
                $event->time_to = Carbon::parse($event->date_to)->format('h:i A');
                foreach ($event->relatedEvents as $relatedEvent) {
                    $relatedEvent->time_from = Carbon::parse($relatedEvent->date_from)->format('h:i A');
                    $relatedEvent->time_to = Carbon::parse($relatedEvent->date_to)->format('h:i A');
                }
            }
        }
        // Get tomorrow's events
        $tomorrowEvents = Event::select("id", "title", "sub_title", "title_ar", "sub_title_ar", "cover", "thumbnail", "landscape", "portrait", "url", "date_from", "date_to", "location_id")
                                ->with(["event_categories", 'relatedEvents' => function($query) {
                                    $query->select("id", "title", "sub_title", "title_ar", "sub_title_ar", "cover", "thumbnail", "landscape", "portrait", "url", "date_from", "date_to", "location_id")
                                        ->where('date_to', '>=', now("GMT+3")); // Ensure active events only
                                }, "location"])
                                ->whereDate('date_from', '<=', $tomorrow)
                                ->whereDate('date_to', '>=', $tomorrow)
                                ->get();

        foreach ($tomorrowEvents as $event) {
            if ($event) {
                $event->time_from = Carbon::parse($event->date_from)->format('h:i A');
                $event->time_to = Carbon::parse($event->date_to)->format('h:i A');
                foreach ($event->relatedEvents as $relatedEvent) {
                    $relatedEvent->time_from = Carbon::parse($relatedEvent->date_from)->format('h:i A');
                    $relatedEvent->time_to = Carbon::parse($relatedEvent->date_to)->format('h:i A');
                }
            }
        }
        // Get upcoming events after tomorrow
        $upcomingEvents = Event::select("id", "title", "sub_title", "title_ar", "sub_title_ar", "cover", "thumbnail", "landscape", "portrait", "url", "date_from", "date_to", "location_id")
                                ->with(["event_categories", 'relatedEvents' => function($query) {
                                    $query->select("id", "title", "sub_title", "title_ar", "sub_title_ar", "cover", "thumbnail", "landscape", "portrait", "url", "date_from", "date_to", "location_id")
                                    ->where('date_to', '>=', now("GMT+3")); // Ensure active events only
                                }, "location"])
                                ->whereDate('date_from', '<=', $dayAfterTomorrow)
                                ->whereDate('date_to', '>=', $dayAfterTomorrow)
                               ->get();
        $main_restaurants = (isset($settingsArray["main_restaurants"]) && $settingsArray["main_restaurants"]["value"]) ? Restaurant::whereIn("id", json_decode($settingsArray["main_restaurants"]["value"]))->get() : null;

        foreach ($upcomingEvents as $event) {
            if ($event) {
                $event->time_from = Carbon::parse($event->date_from)->format('h:i A');
                $event->time_to = Carbon::parse($event->date_to)->format('h:i A');
                foreach ($event->relatedEvents as $relatedEvent) {
                    $relatedEvent->time_from = Carbon::parse($relatedEvent->date_from)->format('h:i A');
                    $relatedEvent->time_to = Carbon::parse($relatedEvent->date_to)->format('h:i A');
                }
            }
        }
        return $this->handleResponse(
            true,
            "عملية ناجحة",
            [],
            [
                'today_events' => $todayEvents,
                'tomorrow_events' => $tomorrowEvents,
                'upcoming_events' => $upcomingEvents,
                'main_restaurants' => $main_restaurants,
            ],
            [
                "يبدا مسار الصورة من بعد الدومين مباشرا"
            ]
        );

    }
}
