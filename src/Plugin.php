<?php
namespace timkelty\craftcms\classmate;

use Craft;
use timkelty\craftcms\classmate\models\Settings;
use timkelty\craftcms\classmate\services\Classmate;
use timkelty\craftcms\classmate\TwigExtension;

class Plugin extends \craft\base\Plugin
{
    public function init()
    {
        parent::init();

        if (Craft::$app->request->getIsSiteRequest()) {
            Craft::$app->view->registerTwigExtension(new TwigExtension());
        }
    }

    protected function createSettingsModel()
    {
        return new Settings();
    }
}
