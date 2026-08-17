<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TestimonialController extends Controller
{
    public function index()
    {
        return view('admin.testimonials.index', [
            'testimonials' => Testimonial::orderBy('sort_order')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        return $this->safely(function () use ($request) {
            $validated = $request->validate([
                'screenshots' => ['required', 'array'],
                'screenshots.*' => ['image', 'max:5120'],
            ]);

            $nextOrder = (int) Testimonial::max('sort_order') + 1;

            foreach ($validated['screenshots'] as $file) {
                Testimonial::create([
                    'image_path' => 'storage/' . $file->store('testimonials', 'public'),
                    'sort_order' => $nextOrder++,
                ]);
            }

            return back()->with('admin_success', 'Depoimentos adicionados com sucesso.');
        });
    }

    public function destroy(Testimonial $testimonial): RedirectResponse
    {
        Storage::disk('public')->delete(Str::after($testimonial->image_path, 'storage/'));
        $testimonial->delete();

        return back()->with('admin_success', 'Depoimento removido.');
    }
}
