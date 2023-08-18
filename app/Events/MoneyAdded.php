<?php

namespace App\Events;

use App\Enums\TransactionType;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class MoneyAdded extends ShouldBeStored
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $type;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public string $accountUuid,
        public int $amount,
        public $date,
        public string $description,
    ) {
        $this->type = TransactionType::MONEY_IN->value;
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
