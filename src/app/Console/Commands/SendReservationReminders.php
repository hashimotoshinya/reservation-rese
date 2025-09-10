<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservation;
use App\Mail\ReservationReminderMail;
use Illuminate\Support\Facades\Mail;

class SendReservationReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reservations:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminder emails for today\'s reservations';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = now()->toDateString();

        $reservations = Reservation::where('date', $today)
            ->where('status', 'active')
            ->get();

        foreach ($reservations as $reservation) {
            Mail::to($reservation->user->email)
                ->send(new ReservationReminderMail($reservation));

            $this->info("Reminder sent to: {$reservation->user->email}");
        }

        return 0;
    }
}