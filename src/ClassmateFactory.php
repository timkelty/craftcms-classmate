<?php
namespace timkelty\craftcms\classmate;

class ClassmateFactory
{
    public function __call($name, $args)
    {
        return (new Classmate())->$name(...$args);
    }
}
