<?php

declare(strict_types=1);

namespace App\Tests\Page;

use App\Page\Component\ComponentInputValidator;
use App\Tests\TestClass\UnitTestCase;

class ComponentInputValidatorTest extends UnitTestCase
{
    public function testColorValidation()
    {
        $cases = [
            ['#FFF', true],
            ['#ffffff', true],
            ['#012345', true],
            ['#0123456', false],
            ['0123456', false],
            ['<div>', false],
            ['rgba(0,0,0,0)', false],
            ['#FFG', false],
        ];

        foreach ($cases as list($color, $expected)) {
            $actual = ComponentInputValidator::color($color);
            $this->assertSame($actual, $expected, "Failed for $color");
        }
    }
}
