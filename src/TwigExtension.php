<?php
namespace timkelty\craftcms\classmate;

use timkelty\craftcms\classmate\Classmate;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

class TwigExtension extends AbstractExtension implements GlobalsInterface
{
    /**
     * @inheritdoc
     */
    public function getGlobals(): array
    {
        return [
            'classmate' => new Classmate(),
        ];
    }
}
