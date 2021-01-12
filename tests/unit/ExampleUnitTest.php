<?php
/**
 * Classmate plugin for Craft CMS 3.x
 *
 * HTML class manager
 *
 * @link      http://github.com/timkelty
 * @copyright Copyright (c) 2021 Tim Kelty
 */

namespace timkelty\classmatetests\unit;

use Codeception\Test\Unit;
use UnitTester;
use Craft;
use timkelty\classmate\Classmate;

/**
 * ExampleUnitTest
 *
 *
 * @author    Tim Kelty
 * @package   Classmate
 * @since     0.1.0
 */
class ExampleUnitTest extends Unit
{
    // Properties
    // =========================================================================

    /**
     * @var UnitTester
     */
    protected $tester;

    // Public methods
    // =========================================================================

    // Tests
    // =========================================================================

    /**
     *
     */
    public function testPluginInstance()
    {
        $this->assertInstanceOf(
            Classmate::class,
            Classmate::$plugin
        );
    }

    /**
     *
     */
    public function testCraftEdition()
    {
        Craft::$app->setEdition(Craft::Pro);

        $this->assertSame(
            Craft::Pro,
            Craft::$app->getEdition()
        );
    }
}
