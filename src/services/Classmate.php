<?php
namespace timkelty\craftcms\classmate\services;

use Craft;
use craft\helpers\Html;
use craft\helpers\Json;
use Illuminate\Support\Collection;
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
// TODO: where to do unique, sort?

class Classmate extends Component
{
    private Collection $definitions;
    private Collection $classes;

    public function init()
    {
        $settings = Plugin::getInstance()->getSettings();
        $filePath = Craft::parseEnv($settings->filePath);
        $this->definitions = $this->getDefinitions($filePath);
        $this->create();
    }

    public function __toString(): string
    {
        return Html::encode($this->classes->join(' '));
    }

    public function create()
    {
        $this->classes = new Collection();

        return $this;
    }

    public function get(string ...$keys): self
    {
        $classes = $this->definitions->only($keys)->values();
        $this->create()->add(...$classes);

        return $this;
    }

    public function add(string ...$classes): self
    {
        $this->classes->push(...$classes)->normalize();

        return $this;
    }

    public function remove(string ...$classes): self
    {
        $this->classes = $this->classes->filter(function ($class) use ($classes) {
            return !in_array($this->normalizeClasses($classes), $class);
        });

        return $this;
    }

    public function normalize(): self
    {
        $this->classes = $this->normalizeClasses($this->classes);

        return $this;
    }

    private function normalizeClasses(mixed $classes): Collection
    {
        return (new Collection($classes))->flatMap(function ($class) {
            return explode($class, ' ');
        })->unique();
    }

    private function getDefinitions(string $filePath): Collection
    {
        if (!is_file($filePath)) {
            throw new FileNotFoundException($filePath);
        }

        $dependency = new ChainedDependency([
            'dependencies' => [
                new FileDependency(['fileName' => $this->filePath]),
                new TagDependency(['tags' => [__CLASS__]]),
            ]
        ]);

        $cacheKey = [
            __CLASS__,
            $this->filePath,
        ];

        $fileContents = Craft::$app->cache->getOrSet(
            $cacheKey,
            file_get_contents($this->filePath),
            null,
            $dependency
        );

        return new Collection($this->decodeJson($fileContents));
    }

    private function decodeJson(string $string): ?array
    {
        $json = Json::decodeIfJson($string);

        if (is_string($json)) {
            throw new JsonDecodeException($string);
        }

        return $json;
    }
}
