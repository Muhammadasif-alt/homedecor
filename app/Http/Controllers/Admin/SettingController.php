<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Support\SettingsSchema;
use App\Http\Requests\Admin\SettingRequest;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

class SettingController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [new Middleware('can:update,'.Setting::class)];
    }

    public function edit(): View
    {
        return view('admin.settings.edit', [
            'groups' => SettingsSchema::groups(),
            'values' => Setting::all_values(),
        ]);
    }

    public function update(SettingRequest $request): RedirectResponse
    {
        Setting::put($request->payload());

        return $this->done('admin.settings.edit', [], 'Settings saved.');
    }
}
