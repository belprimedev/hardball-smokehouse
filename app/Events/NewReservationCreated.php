<?php

namespace App\Events;

use App\Models\Reservation;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewReservationCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $reservation;

    /**
     * Create a new event instance.
     */
    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('notifications'),
        ];
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'type' => 'reservation',
            'title' => 'New Reservation',
            'message' => "New reservation from {$this->reservation->customer_name} for {$this->reservation->number_of_guest} guests",
            'data' => [
                'reservation_id' => $this->reservation->id,
                'customer_name' => $this->reservation->customer_name,
                'customer_email' => $this->reservation->customer_email,
                'customer_phone' => $this->reservation->customer_phone,
                'reservation_date' => $this->reservation->reservation_date->format('Y-m-d'),
                'reservation_time' => $this->reservation->reservation_time,
                'number_of_guest' => $this->reservation->number_of_guest,
                'special_request' => $this->reservation->special_request,
                'created_at' => $this->reservation->created_at->toISOString(),
            ],
        ];
    }
}
