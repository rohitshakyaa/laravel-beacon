<?php

namespace RohitShakya\Beacon\Support;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User;
use Illuminate\Notifications\DatabaseNotification;
use RohitShakya\Beacon\Presenters\BeaconNotification;

class NotificationFeed
{
    /**
     * @return LengthAwarePaginator<BeaconNotification>
     */
    public function inbox(
        User $user,
        int $perPage = 15,
        bool $unreadOnly = false,
        ?string $type = null
    ): LengthAwarePaginator {
        /** @var Builder $query */
        $query = $user->notifications()->latest();

        if ($unreadOnly) {
            $query->whereNull('read_at');
        }

        if ($type) {
            $query->where('type', $type);
        }

        /** @var LengthAwarePaginator<DatabaseNotification> $paginator */
        $paginator = $query->paginate($perPage);

        // Convert each DatabaseNotification to BeaconNotification presenter
        $paginator->setCollection(
            $paginator->getCollection()
                ->map(fn(DatabaseNotification $n) => BeaconNotification::make($n))
        );

        return $paginator;
    }

    /**
     * For building type filter options (based on registry).
     * @return array<int, array{value:string,label:string}>
     */
    public function typeOptions(): array
    {
        $all = app('beacon.registry')->all(); // [type => def]
        $opts = [];

        foreach ($all as $type => $def) {
            $label = $def['label'] ?? class_basename($type);
            $opts[] = ['value' => $type, 'label' => is_string($label) ? $label : class_basename($type)];
        }

        // Sort by label for nice UX
        usort($opts, fn($a, $b) => strcmp($a['label'], $b['label']));

        return $opts;
    }
}
