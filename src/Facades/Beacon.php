<?php

namespace RohitShakya\Beacon\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void register(string $type, array $definition)
 * @method static bool has(string $type)
 * @method static array|null get(string $type)
 * @method static array all()
 */
class Beacon extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'beacon.registry';
    }
}
