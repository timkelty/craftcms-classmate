<?php

namespace timkelty\classmatetests\unit;

use Codeception\Test\Unit;
use craft\helpers\Html;
use timkelty\craftcms\classmate\Classmate;
use timkelty\craftcms\classmate\ClassmateFactory;
use timkelty\craftcms\classmate\exceptions\KeyNotFoundException;
use UnitTester;

class ClassmateTest extends Unit
{
    protected UnitTester $tester;

    public function testItHandlesSpacedValues(): void
    {
        $this->assertEquals(
            ['foo', 'bar', 'baz'],
            (new Classmate())->get('spaced')->asClasses()
        );
    }

    public function testItHandlesArrayValues(): void
    {
        $this->assertEquals(
            ['foo', 'bar', 'baz'],
            (new Classmate())->get('array')->asClasses()
        );
    }

    public function testItHandlesMixedValues(): void
    {
        $this->assertEquals(
            ['foo', 'bar', 'baz', 'qux'],
            (new Classmate())->get('mixed')->asClasses()
        );
    }

    public function testItHandlesMessyValues(): void
    {
        $this->assertEquals(
            ['foo', 'bar', 'baz', 'qux'],
            (new Classmate())->get('messy')->asClasses()
        );
    }

    public function testItCanGetMultipleValues(): void
    {
        $this->assertEquals(
            ['foo', 'bar', 'baz', 'text-4xl', 'tracking-tight', 'mb-4'],
            (new Classmate())->get('spaced', 'tailwind')->asClasses()
        );
    }

    public function testItCanAddClasses(): void
    {
        $this->assertEquals(
            ['foo', 'bar', 'baz', 'qux', 'quux'],
            (new Classmate())->get('spaced')->add('qux', 'quux')->asClasses()
        );
    }

    public function testItCanRemoveClasses(): void
    {
        $this->assertEquals(
            ['bar'],
            (new Classmate())->get('spaced')->remove('foo', 'baz')->asClasses()
        );
    }

    public function testItCanFilterPatterns(): void
    {
        $this->assertEquals(
            ['text-4xl'],
            (new Classmate())->get('tailwind')->matching('/^text-/')->asClasses()
        );

        $this->assertEquals(
            ['text-4xl', 'tracking-tight'],
            (new Classmate())->get('tailwind')->notMatching('/^mb-/')->asClasses()
        );
    }

    public function testItCanPrependClasses(): void
    {
        $this->assertEquals(
            ['md:text-4xl', 'md:tracking-tight', 'md:mb-4'],
            (new Classmate())->get('tailwind')->prepend('md:')->asClasses()
        );
    }

    public function testItCanReplaceClasses(): void
    {
        $this->assertEquals(
            ['foo', 'qux', 'baz'],
            (new Classmate(['foo', 'bar', 'baz']))
                ->replace('bar', 'qux')->replace('fo', 'bo')->asClasses()
        );
    }

    public function testItCanReplacePartialClasses(): void
    {
        $this->assertEquals(
            ['group-hover:text-red-500', 'group-hover:font-bold'],
            (new Classmate([
                'hover:text-red-500',
                'hover:font-bold'
            ]))->replace('hover:', 'group-hover:', true)->asClasses()
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

    public function testItThrowsOnMissingKeys(): void
    {
        $this->expectException(KeyNotFoundException::class);
        (new Classmate())->get('missing');
    }

    public function testItUsesAFactoryToFixParseOrder(): void
    {
        $classmate = new ClassmateFactory();
        $foo = $classmate->get('foo');

        $this->assertEquals(['bar'], $classmate->get('bar')->asClasses());
        $this->assertEquals(['foo'], $foo->asClasses());
    }
}
