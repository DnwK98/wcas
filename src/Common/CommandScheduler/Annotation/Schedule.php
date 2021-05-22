<?php

declare(strict_types=1);

namespace App\Common\CommandScheduler\Annotation;

/**
 * Annotation class for @Schedule.
 *
 * @Annotation
 * @Target({"CLASS"})
 */
class Schedule
{
    public string $cron;

    public string $name;

    public ?string $command = null;

    public string $arguments = '';

    public function __construct()
    {
        $this->name = md5(time() . rand(0, 999999));
    }
}
