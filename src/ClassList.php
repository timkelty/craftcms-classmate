<?php
namespace timkelty\craftcms\classmate;

use craft\helpers\Html;
use Illuminate\Support\Collection;

class ClassList extends Collection
{
    public function __toString(): string
    {
        return Html::encode($this->join(' '));
    }

    public function asAttributes(iterable $attributes = []): iterable
    {
        $attributes =  new Collection($attributes);
        $attributes->put('class', $this->all());

        return $attributes->all();
    }

    public function normalize(): self
    {
        return $this->flatMap(function ($class) {
            return explode(' ', $class);
        })
        ->unique()
        ->filter();
    }
}