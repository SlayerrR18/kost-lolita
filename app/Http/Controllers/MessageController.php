<?php
namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MessageController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $admin = User::where('role', 'admin')->first();
        $adminId = optional($admin)->id;

        if ($user->role === 'admin') {
            $conversations = Message::selectRaw('user_id, MAX(created_at) as last_at')
                ->groupBy('user_id')
                ->orderByDesc('last_at')
                ->get()
                ->map(function($row) use ($adminId){
                    $u = User::find($row->user_id);
                    if(!$u || $u->id == $adminId) return null;
                    $u->unread_count = Message::where('user_id', $u->id)
                        ->where('admin_id', $adminId)
                        ->where('is_read', false)
                        ->count();
                    $u->last_message = Message::where(function($q)use($u){
                        $q->where('user_id', $u->id)
                          ->orWhere('admin_id', $u->id);
                    })->latest()->first();
                    return $u;
                })->filter()->values();

            $selectedUserId = request('user_id');
            $messages = collect();
            if ($selectedUserId) {
                $messages = Message::where(function($q) use($selectedUserId, $adminId){
                    $q->where('user_id', $selectedUserId)
                      ->where('admin_id', $adminId);
                })
                ->orWhere(function($q) use($selectedUserId, $adminId){
                    $q->where('admin_id', $selectedUserId)
                      ->where('user_id', $adminId);
                })
                ->with(['user', 'admin'])
                ->orderBy('created_at')
                ->get();

                Message::where('user_id', $selectedUserId)
                    ->where('admin_id', $adminId)
                    ->where('is_read', false)
                    ->update(['is_read' => true]);
            }

            $totalUnread = $conversations->sum('unread_count');
            $users = User::where('role', 'user')->orderBy('name')->get();

            return view('admin.message.index', compact(
                'conversations','messages','selectedUserId','adminId','totalUnread','users'
            ));
        }

        // user view
        $admin = User::where('role', 'admin')->first();
        $adminId = optional($admin)->id;

        $messages = Message::where(function($q) use ($user, $adminId){
            $q->where('user_id', $user->id)
              ->where('admin_id', $adminId);
        })
        ->orWhere(function($q) use ($user, $adminId){
            $q->where('admin_id', $user->id)
              ->where('user_id', $adminId);
        })
        ->with(['user', 'admin'])
        ->orderBy('created_at')
        ->get();

        Message::where('user_id', $user->id)
            ->where('admin_id', $adminId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('user.message.index', compact('messages', 'adminId'));
    }

    public function store(Request $request)
    {
        // Logika store Anda sudah cukup baik, tidak perlu perubahan besar
        // Saya hanya akan merapikan sedikit untuk kejelasan
        try {
            DB::beginTransaction();

            $validated = $request->validate([
                'content' => 'required_without:attachment|string|min:1|nullable',
                'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
                'recipient_id' => 'required|exists:users,id'
            ], [
                'content.required_without' => 'Pesan tidak boleh kosong jika tidak ada lampiran.',
                'content.min' => 'Pesan tidak boleh kosong.',
            ]);

            $user = Auth::user();
            $attachmentPath = null;

            if ($request->hasFile('attachment')) {
                $attachmentPath = $request->file('attachment')->store('chat-attachments', 'public');
            }

            $message = Message::create([
                'user_id' => $user->id,
                'admin_id' => $validated['recipient_id'],
                'content' => $validated['content'] ?? '(Lampiran)',
                'attachment' => $attachmentPath,
                'is_read' => false
            ]);

            DB::commit();

            $message->load(['user', 'admin']);

            return response()->json([
                'success' => true,
                'message' => $message,
                'redirect_url' => route('messages.index', ['user_id' => $request->recipient_id])
            ]);

        } catch (ValidationException $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim pesan.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }
}
