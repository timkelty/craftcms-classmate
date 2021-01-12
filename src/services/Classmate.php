<?php
namespace timkelty\craftcms\classmate\services;

use craft\helpers\Html;
use yii\base\Component;

// API ideas
// classmate.get('foo').add(classmate.get('bar'))
// classmate.get('foo', 'bar')
// classmate.get(['foo', 'bar'])
// classmate.get('foo').get('bar')
// classmate.get('foo').add('bar')
// classmate.get('foo').remove('bar')
// classmate.get('foo').filter(class => class starts with 'bar')
// classmate.get('foo').filter(class => class matches '/bar/')
// classmate.get('foo').map(class => "pre-${class}")

class Classmate extends Component
{
    private array $classes = [];

    public function __toString(): string
    {
        return Html::encode(implode(' ', $this->classes));
    }

    public function get(string $key)
    {

        // Support multiple eg .get('foo', 'bar') or array?
        // reset $this->classes ?
        // get/cache json file
        // retrive $key
        // parse to array
        // set $classes

        return self;
    }

    public function with()
    {
        # code...
    }

    public function without()
    {
        # code...
    }

    public function asArray()
    {
        return $this->classes;
    }

    public function asAttributes(): array
    {
        return ['class' => $this->classes];
    }
}
