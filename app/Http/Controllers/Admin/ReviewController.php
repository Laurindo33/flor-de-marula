<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->string('status')->toString() ?: 'Pendente';

        $reviews = Review::query()
            ->with('product')
            ->when($status !== 'Todos', fn ($q) => $q->where('status', $status))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.reviews.index', compact('reviews', 'status'));
    }

    public function updateStatus(Request $request, Review $review): RedirectResponse
    {
        return $this->safely(function () use ($request, $review) {
            $validated = $request->validate([
                'status' => ['required', 'in:Pendente,Aprovado,Rejeitado'],
            ]);

            $review->update($validated);

            return back()->with('admin_success', 'Avaliação atualizada.');
        });
    }
}
