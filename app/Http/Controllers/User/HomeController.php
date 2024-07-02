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
        $today = Carbon::today();
        $tomorrow = Carbon::tomorrow();
        $dayAfterTomorrow = Carbon::tomorrow()->addDay();

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
        $amazing_sponsors = Sponsor::where("isTop", true)->get();

        // Get today's events
        $todayEvents = Event::select("id", "title", "sub_title", "title_ar", "sub_title_ar", "cover", "thumbnail", "landscape", "portrait", "url", "date_from", "date_to", "location_id")
                            ->with(["event_categories", 'relatedEvents' => function($query) {
                                $query->select("id", "title", "sub_title", "title_ar", "sub_title_ar", "cover", "thumbnail", "landscape", "portrait", "url", "date_from", "date_to", "location_id")
                                    ->where('date_to', '>=', now()); // Ensure active events only
                            }, "location"])
                            ->whereDate('date_from', '>', $tomorrow)
                            ->whereDate('date_from', '<=', $today)
                            ->whereDate('date_to', '>=', $today)
                            ->take(6)->get();

        // Get tomorrow's events
        $tomorrowEvents = Event::select("id", "title", "sub_title", "title_ar", "sub_title_ar", "cover", "thumbnail", "landscape", "portrait", "url", "date_from", "date_to", "location_id")
                                ->with(["event_categories", 'relatedEvents' => function($query) {
                                    $query->select("id", "title", "sub_title", "title_ar", "sub_title_ar", "cover", "thumbnail", "landscape", "portrait", "url", "date_from", "date_to", "location_id")
                                        ->where('date_to', '>=', now()); // Ensure active events only
                                }, "location"])
                                ->whereDate('date_from', '>', $tomorrow)
                                ->whereDate('date_from', '<=', $tomorrow)
                                ->whereDate('date_to', '>=', $tomorrow)
                                ->take(6)->get();

        // Get upcoming events after tomorrow
        $upcomingEvents = Event::select("id", "title", "sub_title", "title_ar", "sub_title_ar", "cover", "thumbnail", "landscape", "portrait", "url", "date_from", "date_to", "location_id")
                                ->with(["event_categories", 'relatedEvents' => function($query) {
                                    $query->select("id", "title", "sub_title", "title_ar", "sub_title_ar", "cover", "thumbnail", "landscape", "portrait", "url", "date_from", "date_to", "location_id")
                                        ->where('date_to', '>=', now()); // Ensure active events only
                                }, "location"])
                                ->whereDate('date_from', '>', $tomorrow)
                               ->take(6)->get();
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
}
