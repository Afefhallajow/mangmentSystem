<?php

namespace App\Events;

use App\Models\HrMangement\Attendance;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AttendanceCheckedOut
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $attendance;
    /**
     * Create a new event instance.
     */
    public function __construct(Attendance $attendance)
    {
        $this->attendance = $attendance;
    }
    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
