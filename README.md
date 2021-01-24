# Classmate for Craft CMS 3

[![Donate](https://img.shields.io/badge/Donate-PayPal-green.svg)](https://www.paypal.com/donate?hosted_button_id=A5FPKHYV4GRTC)

Classmate is here to help with HTML class composition and is especially useful when paired with a [utility-first](https://tailwindcss.com/docs/utility-first) css framework, such as [Tailwind CSS](http://tailwindcss.com/).

### Before Classmate:

`template.twig`

```html
<h2 class="text-lg leading-6 font-medium text-gray-900 mb-3">
  Some reusable heading…
  <a href="#" class="text-orange-600 hover:text-orange-900">A link</a>
</h2>
```

### After Classmate:

`template.twig`

```twig
<h2
  class="{{ classmate.get('myHeading').remove('text-gray-900').add('mb-3', 'text-gray-400') }"
>
  Some reusable heading…
  <a href="#" class="{{ classmate.get('defaultLink') }}">A link</a>
</h2>
```

`classmate.json`

```json
{
  "myHeading": "text-lg leading-6 font-medium text-gray-900",
  "defaultLink": "text-orange-600 hover:text-orange-900"
}
```

## Why?

My opinons about [Extracting component classes with `@apply`](https://tailwindcss.com/docs/extracting-components#extracting-component-classes-with-apply) are pretty well summed up by [this tweet](https://twitter.com/adamwathan/status/1308944904786268161):

![Flowchart: "Should you extract a component class with @apply"](https://pbs.twimg.com/media/EipNW97WsAIl8QD?format=jpg&name=4096x4096)

In short, I tend to avoid `@apply` when possible.

_Classmate was created specifically for single element components (e.g. button, link, heading), where extracting to a template partial may be cumbersome and you still want to avoid `@apply`._

**How is this better than @apply? Aren't we just moving problem elsewhere?**

Yes and no. It is true that the practice of extracting components with `@apply` is similar to defining classes in classmate JSON file. However, Classmate abstracts the definition on the backend (PHP), while `@apply` abstracts it as part of the front-end build. Advantages to this are:

- No additional CSS bloat from component classes
- No added compile time from `@apply`
- Your resulting HTML remains all-utility. Onboarding a new developer to project, especially if they're already familiar with Tailwind or whatever framework, is much easier if they don't have to decipher a set of component classes.
- Errors are more easily caught.
  - With an extracted component, misuse can happen easily and go unnoticed, e.g. a typo in your class attribute. With Classmate, when a class definition is missing, it is readily apparent to the developer.
- Config can be shared with frontend JS.

## Configuration

Copy `./src/config.php` to `<yourProject>/config/classmate.php`.

### `filePath`

The location of your Classmate file. Aliases and environment variables are supported.
Defaults to `@config/classmate.json`, but I suggest you put it alongside your pre-compiled frontend assets, e.g. `./src/css/classmate.json`

If using Tailwind or PurgeCSS directly, you will also want to include this path. E.g.

`tailwind.config.js`

```js
module.exports = {
  purge: {
    content: [
      "./src/**/*.css",
      "./src/**/*.js",
      "./templates/**/*.*",
      "./config/tailwind.json",
    ],
    options: {
      safelist: [],
    },
  },
};
```

## Classmate File

Your Classmate file is a JSON file with a single object.
The values can be space separated strings or arrays, or a combination of both.

```json
{
  "heading1": "text-2xl font-bold",
  "heading2": ["text-lg", "font-bold"],
  "buttonBase": [
    "text-center inline-flex items-center justify-center font-bold",
    "rounded-full"
  ],
  "buttonWhite": "bg-white text-gray-900",
  "buttonLg": "leading-none text-xl py-4 px-8",
  "buttonSm": "leading-none text-sm py-2 px-4",
  "centerX": "left-1/2 transform -translate-x-1/2",
  "centerY": "top-1/2 transform -translate-y-1/2"
}
```

Since this is just a JSON file, it is easily consumable by Javascript, too!

```js
import classmate from "../../config/classmate.json";
document.querySelector("body").class = classmate.body;
```

## Usage

### `tag` function

```twig
{{ tag('a', classmate.get('defaultLink').asAttributes({
  text: 'A link'
  href: '#'
})) }}
```

### `tag` tag

_Craft 3.6+ only_

```twig
{% tag('a', classmate.get('defaultLink').asAttributes({href: '#' })) %}
  A link
{% endtag %}
```

### `attr` function

```twig
<a {{ attr({
  href: '#'
  class: classmate.get('defaultLink').asClasses()
}) }}>A link</a>
```

### `attr` filter

```twig
{% set tag = '<a href="#">' %}
{{ tag|attr({
    class: classmate.get('defaultLink').asClasses()
}) }}
```

### Class string

```twig
<a href="#" class="{{ classmate.get('defaultLink') }}">A link</a>
```

## API

`classmate` is a chainable API, available as a global in your Twig templates.

### `get(string ...$keys): Classmate`

Retrive classes of given `$keys` from your classmate file. Multiple keys will be merged right to left.

```twig
{{ classmate.get('buttonBase', 'buttonLarge', 'buttonBlue') }}
```

### `asClasses(): array`

Retrive an array of classes. Duplicates and empty values are removed.

```twig
<div {{ attr({
  class: classmate.get('foo').asClasses()
}) }}">
```

### `asAttributes(iterable $attributes = []): iterable`

Retreive an `attr`-compatible iterable, with `class` set, merged into any passed `$attributes`.

```twig
<div {{ attr(classmate.get('foo').asAttributes({
  id: 'buttonLarge'
})) }}">
```

### `add(string ...$classes): Classmate`

Add classes to the current `ClassList`.

```twig
{{ classmate.get('foo').add('mb-4') }}
```

### `remove(string ...$classes): Classmate`

Remove classes to the current `ClassList`.

```twig
{{ classmate.get('foo').remove('mb-4') }}
```

### `matching(string $pattern): Classmate`

Filter the current `ClassList`, keeping those that match `$pattern`.

```twig
{{ classmate.get('foo').matching('/^text-/') }}
```

### `notMatching(string $pattern): Classmate`

Filter the current `ClassList`, removing those that match `$pattern`.

```twig
{{ classmate.get('foo').notMatching('/^mb-/') }}
```

### `replace(string $search, string $replace, bool $partial = false): Classmate`

Replace `$search` with `$replace`. Set `$partial` to `true` to match partial strings, otherwise only complete matches will be replaced.

```twig
{{ classmate.get('foo').replace('text-red-500', 'text-red-100') }}
{{ classmate.get('bar').replace('md:', 'lg:', true) }}
```

### `prepend(string $string): Classmate`

Prepend `$string` to each item in the `ClassList`.

```twig
{{ classmate.get('foo').prepend('md:') }}
```

### `append(string $string): Classmate`

Append `$string` to each item in the `ClassList`.

## Cache

Retrival of the JSON file is cached, and invalidated by modifications to the file, so you really shouldn't have to worry much about invalidation. However, you can selectively clear the cache via the CP or with the CLI command:

```bash
./craft clear-caches/classmate-cache
```

## Requirements

- Craft CMS 3.0+
- PHP 7.4+

## Installation

```bash
composer require timkelty/craftcms-classmate
```

## Roadmap

### Arrow Functions

I'd love to allow the use of arrow functions, but Twig currently doesn't allow it.
For example, instead of our `matches` and `prepend` methods, I'd rather have:

```twig
classmate.get('foo').filter(c => c starts with 'f')
classmate.get('foo').filter(c => c matches '/^f/')
classmate.get('foo').map(c => "md:#{c}")
```

While this works for Twig's `map`, `filter`, and `reduce` filters, it doesn't work here. See https://github.com/twigphp/Twig/issues/3402

### CP settings

I don't really have a desire for this, but happy to accept a PR if someone wants to add this.
