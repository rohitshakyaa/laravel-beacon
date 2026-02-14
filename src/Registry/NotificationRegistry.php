<?php

namespace RohitShakya\Beacon\Registry;

use InvalidArgumentException;

class NotificationRegistry
{
    /** @var array<class-string, array<string,mixed>> */
    protected array $map = [];

    /**
     * @param class-string $type
     * @param array<string,mixed> $definition
     */
    public function register(string $type, array $definition): void
    {
        if ($type === '' || !class_exists($type)) {
            throw new InvalidArgumentException("Notification type [$type] must be a valid class name.");
        }

        // Normalize minimal defaults (we can expand later)
        $this->map[$type] = array_merge([
            'view' => null,
            'detail_view' => null,
            'icon' => null,
            'severity' => 'info',
            'route' => null, // callable(DatabaseNotification $n): string
            'label' => class_basename($type),
        ], $definition);
    }

    /** @param class-string $type */
    public function has(string $type): bool
    {
        return array_key_exists($type, $this->map);
    }

    /** @param class-string $type */
    public function get(string $type): ?array
    {
        return $this->map[$type] ?? null;
    }

    /** @return array<class-string, array<string,mixed>> */
    public function all(): array
    {
        return $this->map;
    }

    public function clear(): void
    {
        $this->map = [];
    }
}
