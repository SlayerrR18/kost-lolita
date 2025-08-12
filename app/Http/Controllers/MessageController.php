<?php
namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class MessageController extends Controller
{
    public function index()
        {
            $user = Auth::user();
            $admin = User::where('role', 'admin')->first();
            $adminId = optional($admin)->id;

            if ($user->role === 'admin') {
                // daftar percakapan (user unik) + unread count + last message
                $conversations = Message::selectRaw('user_id, MAX(created_at) as last_at')
                    ->groupBy('user_id')
                    ->orderByDesc('last_at')
                    ->get()
                    ->map(function($row) use ($adminId){
                        $u = User::find($row->user_id);
                        if(!$u || $u->id == $adminId) return null;
                        $u->unread_count = Message::where('user_id',$u->id)
                            ->where('admin_id',$adminId)->where('is_read',false)->count();
                        $u->last_message  = Message::where(function($q)use($u){
                                $q->where('user_id',$u->id)->orWhere('admin_id',$u->id);
                            })->latest()->first();
                        return $u;
                    })->filter()->values();

                $selectedUserId = request('user_id');
                $messages = collect();
                if ($selectedUserId) {
                    $messages = Message::where(function($q) use($selectedUserId){
                        $q->where('user_id',$selectedUserId)->orWhere('admin_id',$selectedUserId);
                    })->with(['user','admin'])->orderBy('created_at')->get();

                    // tandai pesan user -> admin sebagai read
                    Message::where('user_id',$selectedUserId)
                        ->where('admin_id',$adminId)
                        ->where('is_read',false)
                        ->update(['is_read'=>true]);
                }

                $totalUnread = $conversations->sum('unread_count');
                $users = User::where('role','user')->orderBy('name')->get();

                return view('admin.message.index', compact(
                    'conversations','messages','selectedUserId','adminId','totalUnread','users'
                ));
            }

            // user view
            $messages = Message::where(function($q) use ($user){
                $q->where('user_id',$user->id)->orWhere('admin_id',$user->id);
            })->with(['user','admin'])->orderBy('created_at')->get();

            // tandai pesan admin -> user sebagai read
            Message::where('user_id',$user->id)->where('admin_id',$user->id)->update(['is_read'=>true]);

            return view('user.message.index', compact('messages','adminId'));
        }

    public function store(Request $request)
    {
        try {
            \Log::info('Received message request', $request->all());

            $validated = $request->validate([
                'content' => 'required|string|min:1',
                'attachment' => 'nullable|file|max:5120',
                'recipient_id' => 'required|exists:users,id'
            ], [
                'content.required' => 'Pesan tidak boleh kosong',
                'content.min' => 'Pesan tidak boleh kosong',
            ]);

            $user = Auth::user();

            if ($request->hasFile('attachment')) {
                $path = $request->file('attachment')->store('chat-attachments', 'public');
                $validated['attachment'] = $path;
            }

            $message = Message::create([
                'user_id' => $user->id,
                'admin_id' => $validated['recipient_id'],
                'content' => $validated['content'],
                'attachment' => $validated['attachment'] ?? null,
                'is_read' => false
            ]);

            $message->load(['user', 'admin']);

            \Log::info('Message created successfully', [
                'message_id' => $message->id,
                'content' => $message->content
            ]);

            return response()->json([
                'success' => true,
                'message' => $message,
                'redirect_url' => route('messages.index', ['user_id' => $request->recipient_id])
            ]);

        } catch (ValidationException $e) {
            \Log::error('Validation failed', ['errors' => $e->errors()]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim pesan: ' . $e->errors()['content'][0]
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Message store failed', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim pesan: ' . $e->getMessage()
            ], 500);
        }
    }
}
