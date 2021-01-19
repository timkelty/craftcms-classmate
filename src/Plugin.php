<?php
namespace timkelty\craftcms\classmate;

use Craft;
use craft\console\Request as ConsoleRequest;
use craft\events\RegisterCacheOptionsEvent;
use craft\utilities\ClearCaches;
use craft\web\Request as WebRequest;
use craft\web\View;
use timkelty\craftcms\classmate\Classmate;
use timkelty\craftcms\classmate\models\Settings;
use timkelty\craftcms\classmate\TwigExtension;
use yii\base\Event;

class Plugin extends \craft\base\Plugin
{
    /**
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();

        /** @var WebRequest|ConsoleRequest */
        $request = Craft::$app->getRequest();

        if ($request->getIsSiteRequest()) {

            /** @var View */
            $view = Craft::$app->getView();
            $view->registerTwigExtension(new TwigExtension());
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

    /**
     * @inheritdoc
     */
    protected function createSettingsModel(): Settings
    {
        return new Settings();
    }
}
