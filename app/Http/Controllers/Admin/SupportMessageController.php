<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SupportMessageController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->string('status')->toString();

        return view('admin.messages.index', [
            'messages' => SupportMessage::query()
                ->when($status, fn ($q) => $q->where('status', $status))
                ->latest()
                ->paginate(20)
                ->withQueryString(),
            'activeStatus' => $status,
        ]);
    }

    public function updateStatus(Request $request, SupportMessage $message): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:Novo,Em atendimento,Resolvido'],
        ]);

        $message->update($validated);

        return back()->with('admin_success', 'Mensagem atualizada.');
    }
}
