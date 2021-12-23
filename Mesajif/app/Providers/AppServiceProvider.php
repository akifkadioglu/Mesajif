<?php

namespace App\Providers;

use App\Models\Settings;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $data = Settings::first();
        if (empty($data)) {
            $data = new Settings();
            $data->title = "Mesajif";
            $data->save();
        }
        $setting = [
            'title' => Settings::first()->title,
            'keywords' => Settings::first()->keywords,
            'description' => Settings::first()->description,
            'image' => Settings::first()->image,

            'address' => Settings::first()->address,
            'phone' => Settings::first()->phone,

            'facebook' => Settings::first()->facebook,
            'instagram' => Settings::first()->instagram,
            'youtube' => Settings::first()->youtube,
            'email' => Settings::first()->mail,

        ];

        view()->share('setting', $setting);
    }
}
