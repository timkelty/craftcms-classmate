<?php
namespace timkelty\craftcms\classmate;

use timkelty\craftcms\classmate\Plugin;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

class TwigExtension extends AbstractExtension implements GlobalsInterface
{
    public function getGlobals(): array
    {
        return [
            'classmate' => Plugin::getInstance()->classmate,
        ];
    }
}
