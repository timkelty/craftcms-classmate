<?php
namespace timkelty\craftcms\classmate;

use Craft;
use craft\helpers\Json;
use Illuminate\Support\Collection;
use timkelty\craftcms\classmate\ClassList;
use timkelty\craftcms\classmate\exceptions\FileNotFoundException;
use timkelty\craftcms\classmate\exceptions\JsonDecodeException;
use timkelty\craftcms\classmate\exceptions\KeyNotFoundException;
use timkelty\craftcms\classmate\models\Settings;
use timkelty\craftcms\classmate\Plugin;
use yii\base\Component;
use yii\caching\ChainedDependency;
use yii\caching\FileDependency;
use yii\caching\TagDependency;

/**
 * @inheritdoc
 */
class Classmate extends Component
{
    private Collection $definitions;
    private ClassList $classList;

    /**
     * @inheritdoc
     */
    public function init(): void
    {
        /** @var Settings */
        $settings = Plugin::getInstance()->getSettings();

        $filePath = Craft::parseEnv($settings->filePath);
        $this->definitions = $this->loadDefinitions($filePath);
        $this->create();
    }

    public function __toString(): string
    {
        return (string) $this->classList;
    }

    public function __call($name, $args)
    {
        if (method_exists($this->classList, $name)) {
            return $this->classList->$name(...$args);
        }

        return parent::__call($name, $args);
    }

    public function create(): self
    {
        $this->classList = new ClassList();

        return $this;
    }

    public function get(string ...$keys): self
    {
        $classes = (new Collection($keys))->map(function ($key) {
            $value = $this->definitions->get($key);

            if ($value === null) {
                throw new KeyNotFoundException($key);
            }

            return $value;
        })->flatten()->all();

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
        })->normalize();

        return $this;
    }

    public function matching(string $pattern, bool $inverse = false): self
    {
        $this->classList = $this->classList->filter(function ($class) use ($pattern, $inverse) {
            $match = preg_match($pattern, $class);

            return $inverse ? !$match : $match;
        })->normalize();

        return $this;
    }

    public function notMatching(string $pattern): self
    {
        return $this->matching($pattern, true);
    }

    public function prepend(string $string): self
    {
        $this->classList = $this->classList->map(function ($class) use ($string) {
            return "{$string}{$class}";
        })->normalize();

        return $this;
    }

    public function append(string $string): self
    {
        $this->classList = $this->classList->map(function ($class) use ($string) {
            return "{$class}{$string}";
        })->normalize();

        return $this;
    }

    public static function invalidateCache(): void
    {
        TagDependency::invalidate(Craft::$app->getCache(), __CLASS__);
        Craft::info('Classmate cache cleared', __METHOD__);
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
