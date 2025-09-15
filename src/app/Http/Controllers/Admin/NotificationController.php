<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\NotificationMail;
use App\Models\User;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class NotificationController extends Controller
{
    public function send(Request $request)
    {
        $validated = $request->validate([
            'target'  => 'required|string',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $target = $validated['target'];
        $subject = $validated['subject'];
        $message = $validated['message'];

        if ($target === 'all') {
            $users = User::all();
        } elseif ($target === 'frequent_users') {
            $userIds = Reservation::select('user_id')
                ->groupBy('user_id')
                ->havingRaw('COUNT(*) >= 5')
                ->pluck('user_id');

            $users = User::whereIn('id', $userIds)->get();
        } elseif ($target === 'owners') {
            $users = User::where('role', 'owner')->get();
        } else {
            $users = collect();
        }

        if ($users->isEmpty()) {
            return redirect()->back()->with('error', '条件に当てはまるユーザーはいませんでした。');
        }

        foreach ($users as $user) {
            Mail::to($user->email)->send(new NotificationMail($subject, $message));
        }

        return redirect()->back()->with('success', 'お知らせメールを送信しました。');
    }
}