<?php

namespace RohitShakya\Beacon\Presenters;

use Illuminate\Notifications\DatabaseNotification;
use RohitShakya\Beacon\Registry\NotificationRegistry;

class BeaconNotification
{
    public function __construct(
        public readonly DatabaseNotification $notification,
        protected NotificationRegistry $registry,
    ) {}

    public static function make(DatabaseNotification $notification): self
    {
        /** @var NotificationRegistry $registry */
        $registry = app('beacon.registry');

        return new self($notification, $registry);
    }

    /** @return array<string,mixed>|null */
    public function definition(): ?array
    {
        return $this->registry->get($this->notification->type);
    }

    public function view(): string
    {
        $def = $this->definition();

        // registry view > config fallback
        return $def['view']
            ?? config('beacon.views.item', 'beacon::item.default');
    }

    public function label(): string
    {
        $def = $this->definition();
        $label = $def['label'] ?? class_basename($this->notification->type);

        return $this->evaluate($label) ?? class_basename($this->notification->type);
    }

    public function title(): string
    {
        $def = $this->definition();

        // prefer registry callable/string, else data['title'], else label
        $title = $def['title'] ?? ($this->notification->data['title'] ?? null);

        return $this->evaluate($title) ?? $this->label();
    }

    public function body(): ?string
    {
        $def = $this->definition();

        $body = $def['body'] ?? ($this->notification->data['body'] ?? null);

        return $this->evaluate($body);
    }

    public function icon(): ?string
    {
        $def = $this->definition();
        $icon = $def['icon'] ?? ($this->notification->data['icon'] ?? null);

        return $this->evaluate($icon);
    }

    public function severity(): string
    {
        $def = $this->definition();
        $severity = $def['severity'] ?? ($this->notification->data['severity'] ?? 'info');

        return $this->evaluate($severity) ?? 'info';
    }

    public function url(): ?string
    {
        $def = $this->definition();

        // support 'url' OR 'route' for dev comfort
        $url = $def['url'] ?? $def['route'] ?? ($this->notification->data['url'] ?? null);

        return $this->evaluate($url);
    }

    /**
     * @template T
     * @param T|callable|null $value
     * @return T|null
     */
    protected function evaluate(mixed $value): mixed
    {
        if (is_string($value)) {
            if (str_contains($value, '::') && is_callable($value)) {
                return $value($this->notification, $this);
            }

            return $value;
        }

        if (is_callable($value)) {
            return $value($this->notification, $this);
        }

        return $value;
    }
}
