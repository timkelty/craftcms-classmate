<?php
namespace timkelty\craftcms\classmate;

use craft\helpers\Html;
use Illuminate\Support\Collection;

/**
 * @inheritdoc
 */
class ClassList extends Collection
{
    public function __toString(): string
    {
        return Html::encode($this->asClasses()->join(' '));
    }

    public function asClasses(): self
    {
        return $this->flatMap(function ($class) {
            return explode(' ', $class);
        })
        ->unique()
        ->filter()
        ->values();
    }

    public function asAttributes(iterable $attributes = []): iterable
    {
        $attributes =  new Collection($attributes);
        $attributes->put('class', $this->all());

        return $attributes->all();
    }
}
