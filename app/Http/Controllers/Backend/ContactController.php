<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\ContactMessage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    /**
     * Display a listing of contact messages
     */
    public function index(Request $request)
    {
        $query = ContactMessage::query();

        // Calculate statistics
        $stats = [
            'total_messages' => ContactMessage::count(),
            'unread_messages' => ContactMessage::where('is_read', false)->count(),
            'replied_messages' => ContactMessage::whereNotNull('replied_at')->count(),
            'today_messages' => ContactMessage::whereDate('created_at', Carbon::today())->count(),
        ];

        // Apply filters
        if ($request->filled('status')) {
            switch ($request->status) {
                case 'unread':
                    $query->where('is_read', false);
                    break;
                case 'read':
                    $query->where('is_read', true)->whereNull('replied_at');
                    break;
                case 'replied':
                    $query->whereNotNull('replied_at');
                    break;
            }
        }

        // Date filters
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('message', 'LIKE', "%{$search}%");
            });
        }

        // Get paginated messages
        $messages = $query->latest()->paginate(10);

        return view('backend.contacts.index', compact('messages', 'stats'));
    }

    /**
     * Display the specified contact message
     */
    public function show($id)
    {
        $message = ContactMessage::findOrFail($id);

        // Mark as read if not already
        if (! $message->is_read) {
            $message->update([
                'is_read' => true,
                'read_at' => now(),
                'read_by' => Auth::id(),
            ]);
        }

        return view('backend.contacts.show', compact('message'));
    }

    /**
     * Show the reply form
     */
    public function reply($id)
    {
        $message = ContactMessage::findOrFail($id);

        // Check if already replied
        if ($message->replied_at) {
            return redirect()
                ->route('backend.contacts.show', $message->id)
                ->with('info', 'This message has already been replied to.');
        }

        // Mark as read if not already
        if (! $message->is_read) {
            $message->update([
                'is_read' => true,
                'read_at' => now(),
                'read_by' => Auth::id(),
            ]);
        }

        return view('backend.contacts.reply', compact('message'));
    }

    /**
     * Send reply to contact message
     */
    public function sendReply(Request $request, $id)
    {
        $message = ContactMessage::findOrFail($id);

        $validated = $request->validate([
            'reply_message' => 'required|string|min:10|max:5000',
            'subject'       => 'required|string|min:5|max:255',
        ]);

        $sendEmail = $request->input('send_email') === 'on';
        $markResolved = $request->input('mark_resolved') === 'on';

        try {
            DB::beginTransaction();

            if ($sendEmail) {
                Mail::send('backend.contacts.email', [
                    'contactMessage' => $message,
                    'reply' => $validated['reply_message'],
                    'subject' => $validated['subject'],
                ], function ($mail) use ($message, $validated) {
                    $mail->to($message->email, $message->name)
                        ->subject($validated['subject']);
                });
            }
            $message->update([
                'reply_message' => $validated['reply_message'],
                'replied_at' => now(),
                'replied_by' => Auth::id(),
                'is_read' => true,
                'read_at' => $message->read_at ?? now(),
            ]);

            DB::commit();

            if ($sendEmail) {
                return redirect()
                    ->route('backend.contacts.show', $message->id)
                    ->with('success', 'Reply sent successfully to ' . $message->email);
            }

            return redirect()
                ->route('backend.contacts.show', $message->id)
                ->with('success', 'Reply saved successfully (email not sent).');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->route('backend.contacts.show', $message->id)
                ->with('error', 'Failed to send reply: ' . $e->getMessage());
        }
    }

    /**
     * Mark message as read
     */
    public function markRead($id)
    {
        $message = ContactMessage::findOrFail($id);

        $message->update([
            'is_read' => true,
            'read_at' => now(),
            'read_by' => Auth::id(),
        ]);

        return redirect()
            ->back()
            ->with('success', 'Message marked as read.');
    }

    /**
     * Delete contact message
     */
    public function destroy($id)
    {
        $message = ContactMessage::findOrFail($id);
        $message->delete();

        return redirect()
            ->route('backend.contacts.index')
            ->with('success', 'Contact message deleted successfully.');
    }

    /**
     * Bulk delete messages
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'message_ids' => 'required|array',
            'message_ids.*' => 'exists:contact_messages,id',
        ]);

        ContactMessage::whereIn('id', $request->message_ids)->delete();

        return redirect()
            ->route('backend.contacts.index')
            ->with('success', count($request->message_ids).' messages deleted successfully.');
    }

    /**
     * Bulk mark as read
     */
    public function bulkMarkRead(Request $request)
    {
        $request->validate([
            'message_ids' => 'required|array',
            'message_ids.*' => 'exists:contact_messages,id',
        ]);

        ContactMessage::whereIn('id', $request->message_ids)
            ->update([
                'is_read' => true,
                'read_at' => now(),
                'read_by' => Auth::id(),
            ]);

        return redirect()
            ->route('backend.contacts.index')
            ->with('success', count($request->message_ids).' messages marked as read.');
    }
}
