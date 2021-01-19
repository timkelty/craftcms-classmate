<?php
namespace timkelty\craftcms\classmate;

use Craft;
use craft\events\RegisterCacheOptionsEvent;
use craft\utilities\ClearCaches;
use timkelty\craftcms\classmate\Classmate;
use timkelty\craftcms\classmate\models\Settings;
use timkelty\craftcms\classmate\TwigExtension;
use yii\base\Event;

class Plugin extends \craft\base\Plugin
{
    public function init()
    {
        parent::init();

        if (Craft::$app->request->getIsSiteRequest()) {
            Craft::$app->view->registerTwigExtension(new TwigExtension());
        }

        Event::on(
            ClearCaches::class,
            ClearCaches::EVENT_REGISTER_CACHE_OPTIONS,
            function (RegisterCacheOptionsEvent $event) {
                $event->options[] = [
                    'key' => 'classmate-cache',
                    'label' => Craft::t($this->handle, 'Classmate Cache'),
                    'action' => [Classmate::class, 'invalidateCache'],
                ];
            }
        );
    }

    protected function createSettingsModel()
    {
        return new Settings();
    }
}
