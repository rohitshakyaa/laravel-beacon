<?php

namespace RohitShakya\Beacon\Tests\Feature;

use Illuminate\Notifications\DatabaseNotification;
use RohitShakya\Beacon\Facades\Beacon;
use RohitShakya\Beacon\Presenters\BeaconNotification;
use RohitShakya\Beacon\Tests\TestCase;

class PresenterTest extends TestCase
{
    protected function makeDbNotification(string $type, array $data = []): DatabaseNotification
    {
        // No DB required — DatabaseNotification is just an Eloquent model.
        return new DatabaseNotification([
            'id' => '00000000-0000-0000-0000-000000000000',
            'type' => $type,
            'notifiable_type' => 'App\\Models\\User',
            'notifiable_id' => 1,
            'data' => $data,
            'read_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function test_presenter_uses_registered_view(): void
    {
        Beacon::register(DummyTypeA::class, [
            'view' => 'notifications.custom-a',
        ]);

        $n = $this->makeDbNotification(DummyTypeA::class, ['title' => 'Hello']);
        $ui = BeaconNotification::make($n);

        $this->assertSame('notifications.custom-a', $ui->view());
    }

    public function test_presenter_falls_back_to_config_view(): void
    {
        config()->set('beacon.views.item', 'beacon::item.default');

        $n = $this->makeDbNotification(DummyTypeB::class, ['title' => 'Hello']);
        $ui = BeaconNotification::make($n);

        $this->assertSame('beacon::item.default', $ui->view());
    }

    public function test_title_prefers_registry_over_data(): void
    {
        Beacon::register(DummyTypeA::class, [
            'title' => 'Registry Title',
        ]);

        $n = $this->makeDbNotification(DummyTypeA::class, ['title' => 'Data Title']);
        $ui = BeaconNotification::make($n);

        $this->assertSame('Registry Title', $ui->title());
    }

    public function test_url_can_be_callable(): void
    {
        Beacon::register(DummyTypeA::class, [
            'route' => fn(DatabaseNotification $n) => '/x/' . $n->data['id'],
        ]);

        $n = $this->makeDbNotification(DummyTypeA::class, ['id' => 99]);
        $ui = BeaconNotification::make($n);

        $this->assertSame('/x/99', $ui->url());
    }

    public function test_severity_defaults_to_info(): void
    {
        $n = $this->makeDbNotification(DummyTypeB::class, []);
        $ui = BeaconNotification::make($n);
        
        $this->assertSame('info', $ui->severity());
    }
}

class DummyTypeA {}
class DummyTypeB {}
