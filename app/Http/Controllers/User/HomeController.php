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
        $main_cat = (isset($settingsArray["main_cat"]) && $settingsArray["main_cat"]["value"]) ? Category::with(["events" => function ($q) {
            $q->latest()->with("location");
        }])->find($settingsArray["main_cat"]["value"]) : null;
        $amazing_sponsors = Sponsor::where("isTop", true)->get();

        // Get today's events
        $todayEvents = Event::with(["event_categories", "location"])->whereDate('date_from', '<=', $today)
                            ->whereDate('date_to', '>=', $today)
                            ->take(6)->get();

        // Get tomorrow's events
        $tomorrowEvents = Event::with(["event_categories", "location"])->whereDate('date_from', '<=', $tomorrow)
                               ->whereDate('date_to', '>=', $tomorrow)
                               ->take(6)->get();

        // Get upcoming events after tomorrow
        $upcomingEvents = Event::with(["event_categories", "location"])->whereDate('date_from', '>', $tomorrow)
                               ->take(6)->get();
        $main_restaurants = (isset($settingsArray["main_restaurants"]) && $settingsArray["main_restaurants"]["value"]) ? Restaurant::whereIn("id", json_decode($settingsArray["main_restaurants"]["value"]))->get() : null;
        $all_sponsors = Sponsor::where("isTop", false)->get();

        return $this->handleResponse(
            true,
            "عملية ناجحة",
            [],
            [
                "teaser_url" => $teaser_url,
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
