<?php
namespace timkelty\craftcms\classmate;

use timkelty\craftcms\classmate\ClassmateFactory;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

/**
 * @inheritdoc
 */
class TwigExtension extends AbstractExtension implements GlobalsInterface
{
    /**
     * @inheritdoc
     */
    public function getGlobals(): array
    {
        return [
            'classmate' => new ClassmateFactory(),
        ];
    }
}
