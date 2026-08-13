<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->string('tipo')->toString();

        $reviews = Review::query()
            ->where('status', 'Aprovado')
            ->with('product')
            ->latest()
            ->get()
            ->when($type && $type !== 'Todos', fn ($collection) => $collection->filter(fn (Review $review) => $review->type === $type));

        return view('reviews.index', [
            'reviews' => $reviews,
            'activeType' => $type ?: 'Todos',
            'products' => Product::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'product_id' => ['required', 'exists:products,id'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:2000'],
            'before_photo' => ['nullable', 'image', 'max:5120'],
            'after_photo' => ['nullable', 'image', 'max:5120'],
            'video_url' => ['nullable', 'url', 'max:255'],
        ]);

        if ($request->hasFile('before_photo')) {
            $validated['before_photo'] = $request->file('before_photo')->store('reviews', 'public');
        }

        if ($request->hasFile('after_photo')) {
            $validated['after_photo'] = $request->file('after_photo')->store('reviews', 'public');
        }

        $validated['user_id'] = auth()->id();
        $validated['status'] = 'Pendente';

        Review::create($validated);

        return back()->with('review_success', 'Obrigado! A sua avaliação foi enviada e será publicada após aprovação.');
    }
}
