<?php
namespace timkelty\craftcms\classmate\services;

use Craft;
use craft\helpers\Html;
use craft\helpers\Json;
use Illuminate\Support\Collection;
use timkelty\craftcms\classmate\ClassList;
use timkelty\craftcms\classmate\exceptions\FileNotFoundException;
use timkelty\craftcms\classmate\exceptions\JsonDecodeException;
use timkelty\craftcms\classmate\Plugin;
use yii\base\Component;
use yii\caching\ChainedDependency;
use yii\caching\FileDependency;
use yii\caching\TagDependency;

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
    private Collection $definitions;
    private ClassList $classList;

    public function init()
    {
        $settings = Plugin::getInstance()->getSettings();
        $filePath = Craft::parseEnv($settings->filePath);
        $this->definitions = $this->loadDefinitions($filePath);
        $this->create();
    }

    public function __toString(): string
    {
        return (string) $this->classList;
    }

    public function __call($method, $args)
    {
        if (method_exists($this->classList, $method)) {
            return $this->classList->$method(...$args);
        }
    }

    public function create()
    {
        $this->classList = new ClassList();

        return $this;
    }

    public function get(string ...$keys): self
    {
        $classes = $this->definitions->only($keys)->flatten();
        $this->create()->add(...$classes);

        return $this;
    }

    public function add(string ...$classes): self
    {
        $this->classList = $this->classList
            ->push(...$classes)
            ->normalize();

        return $this;
    }

    public function remove(string ...$classes): self
    {
        $toRemove = (new ClassList($classes))->normalize();
        $this->classList = $this->classList->filter(function ($class) use ($toRemove) {
            return !$toRemove->contains($class);
        });

        return $this;
    }

    private function loadDefinitions(string $filePath): Collection
    {
        if (!is_file($filePath)) {
            throw new FileNotFoundException($filePath);
        }

        $dependency = new ChainedDependency([
            'dependencies' => [
                new FileDependency(['fileName' => $filePath]),
                new TagDependency(['tags' => [__CLASS__]]),
            ]
        ]);

        $cacheKey = [
            __CLASS__,
            $filePath,
        ];

        $fileContents = Craft::$app->cache->getOrSet(
            $cacheKey,
            function () use ($filePath) {
                return file_get_contents($filePath);
            },
            null,
            $dependency
        );

        return new Collection($this->decodeJson($fileContents));
    }

    private function decodeJson(string $string): ?iterable
    {
        $json = Json::decodeIfJson($string);

        if (is_string($json)) {
            throw new JsonDecodeException($string);
        }

        return $json;
    }
}
