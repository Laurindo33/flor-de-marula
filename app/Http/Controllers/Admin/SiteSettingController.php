<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SiteSettingController extends Controller
{
    public function edit()
    {
        return view('admin.settings.edit', [
            'settings' => SiteSetting::current(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        return $this->safely(function () use ($request) {
            $validated = $request->validate([
                'phone' => ['nullable', 'string', 'max:30'],
                'email' => ['nullable', 'email', 'max:255'],
                'address' => ['nullable', 'string', 'max:255'],
                'instagram' => ['nullable', 'string', 'max:255'],
            ]);

            if (! empty($validated['instagram'])) {
                $validated['instagram'] = Str::of($validated['instagram'])
                    ->after('instagram.com/')
                    ->ltrim('@')
                    ->trim('/')
                    ->toString();
            }

            SiteSetting::current()->update($validated);

            return back()->with('admin_success', 'Contactos atualizados.');
        });
    }
}
