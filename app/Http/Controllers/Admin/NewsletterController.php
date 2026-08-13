<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\RedirectResponse;

class NewsletterController extends Controller
{
    public function index()
    {
        return view('admin.newsletter.index', [
            'subscribers' => NewsletterSubscriber::latest('subscribed_at')->paginate(30),
        ]);
    }

    public function destroy(NewsletterSubscriber $subscriber): RedirectResponse
    {
        $subscriber->delete();

        return back()->with('admin_success', 'Subscritor removido.');
    }
}
