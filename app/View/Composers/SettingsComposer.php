<?php

namespace App\View\Composers;

use App\Models\Setting;
use Illuminate\View\View;

class SettingsComposer
{
    public function compose(View $view)
    {
        $settings = Setting::all()->pluck('value', 'key')->toArray();
        $view->with('settings', $settings);
    }
}