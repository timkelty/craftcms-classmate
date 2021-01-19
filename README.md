# Classmate for Craft CMS 3

Classmate is here to help with HTML class composition and is especially useful when paired with a [utility-first](https://tailwindcss.com/docs/utility-first) css framework, such as [Tailwind CSS](http://tailwindcss.com/).

## Why?

My opinons about [Extracting component classes with `@apply`](https://tailwindcss.com/docs/extracting-components#extracting-component-classes-with-apply) are pretty well summed up by [this tweet](https://twitter.com/adamwathan/status/1308944904786268161):

![Flowchart: "Should you extract a component class with @apply"](https://pbs.twimg.com/media/EipNW97WsAIl8QD?format=jpg&name=4096x4096)

In short, I tend to avoid `@apply`.

Classmate was created specifically for single element components (e.g. button, link, heading), where extracting to a template partial may be cumbersome and you still want to avoid `@apply`.

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

### HTML

### Isn't this just moving complexity from

## Requirements

- Craft CMS 3.0+
- PHP 7.4+

## Installation

```bash
composer require timkelty/craftcms-classmate
```
