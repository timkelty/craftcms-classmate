<?php

namespace timkelty\classmatetests\unit;

use Codeception\Test\Unit;
use craft\helpers\Html;
use timkelty\craftcms\classmate\ClassList;
use timkelty\craftcms\classmate\Classmate;
use timkelty\craftcms\classmate\Plugin;
use UnitTester;

class ClassmateTest extends Unit
{
    protected UnitTester $tester;

    public function testItHandlesSpacedValues(): void
    {
        $this->assertEquals(
            ['foo', 'bar', 'baz'],
            (new Classmate())->get('spaced')->asClasses()->all()
        );
    }

    public function testItHandlesArrayValues(): void
    {
        $this->assertEquals(
            ['foo', 'bar', 'baz'],
            (new Classmate())->get('array')->asClasses()->all()
        );
    }

    public function testItHandlesMixedValues(): void
    {
        $this->assertEquals(
            ['foo', 'bar', 'baz', 'qux'],
            (new Classmate())->get('mixed')->asClasses()->all()
        );
    }

    public function testItHandlesMessyValues(): void
    {
        $this->assertEquals(
            ['foo', 'bar', 'baz', 'qux'],
            (new Classmate())->get('messy')->asClasses()->all()
        );
    }

    public function testItCanGetMultipleValues(): void
    {
        $this->assertEquals(
            ['foo', 'bar', 'baz', 'text-4xl', 'tracking-tight', 'mb-4'],
            (new Classmate())->get('spaced', 'tailwind')->asClasses()->all()
        );
    }

    public function testItCanAddClasses(): void
    {
        $this->assertEquals(
            ['foo', 'bar', 'baz', 'qux', 'quux'],
            (new Classmate())->get('spaced')->add('qux', 'quux')->asClasses()->all()
        );
    }

    public function testItCanRemoveClasses(): void
    {
        $this->assertEquals(
            ['bar'],
            (new Classmate())->get('spaced')->remove('foo', 'baz')->asClasses()->all()
        );
    }

    public function testItCanFilterPatterns(): void
    {
        $this->assertEquals(
            ['text-4xl'],
            (new Classmate())->get('tailwind')->matching('/^text-/')->asClasses()->all()
        );

        $this->assertEquals(
            ['text-4xl', 'tracking-tight'],
            (new Classmate())->get('tailwind')->notMatching('/^mb-/')->asClasses()->all()
        );
    }

    public function testItCanPrependClasses(): void
    {
        $this->assertEquals(
            ['md:text-4xl', 'md:tracking-tight', 'md:mb-4'],
            (new Classmate())->get('tailwind')->prepend('md:')->asClasses()->all()
        );
    }

    public function testItCanOutputAsString(): void
    {
        $this->assertEquals(
            'foo bar baz qux',
            (string) (new Classmate())->get('mixed', 'messy')
        );
    }

    public function testItCanOutputAsAttributes(): void
    {
        $value = (new Classmate())->get('mixed')->asAttributes(['id' => 'foo']);
        $this->assertEquals(
            [
                'class' => ['foo', 'bar', 'baz', 'qux'],
                'id' => 'foo'
            ],
            $value
        );

        $this->assertEquals(
            ' id="foo" class="foo bar baz qux"',
            Html::renderTagAttributes($value)
        );
    }
}
