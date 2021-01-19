<?php

namespace timkelty\classmatetests\unit;

use Codeception\Test\Unit;
use Craft;
use timkelty\craftcms\classmate\Plugin;
use UnitTester;

class ExampleUnitTest extends Unit
{
    protected UnitTester $tester;

    public function testPluginInstance()
    {
        $this->assertInstanceOf(
            Plugin::class,
            Plugin::getInstance()
        );
    }
}
