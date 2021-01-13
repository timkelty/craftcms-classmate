# Classmate for Craft CMS 3.x

Classmate is here to help with HTML class composition and is especially useful when paired with a [utility-first](https://tailwindcss.com/docs/utility-first) css framework, such as [Tailwind CSS](http://tailwindcss.com/).

My opinons about [Extracting component classes with `@apply`](https://tailwindcss.com/docs/extracting-components#extracting-component-classes-with-apply) are pretty well summed up by this tweet:

<blockquote class="twitter-tweet"><p lang="en" dir="ltr">Should you extract a component class in Tailwind with @​apply? 🤔<br><br>(Thanks <a href="https://twitter.com/klickreflex?ref_src=twsrc%5Etfw">@klickreflex</a> for the idea 😅) <a href="https://t.co/4x3X6F07gH">pic.twitter.com/4x3X6F07gH</a></p>&mdash; Adam Wathan (@adamwathan) <a href="https://twitter.com/adamwathan/status/1308944904786268161?ref_src=twsrc%5Etfw">September 24, 2020</a></blockquote> <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>

In short, I avoid `@apply` at all costs.

Classmate was created specifically for single element components (e.g. button, link, heading), where extracting to a template partial may be cumbersome and you still want to avoid `@apply`.

Before Classmate:

`template.twig`

```html
<h2 class="text-lg leading-6 font-medium text-gray-900 mb-3">
  Some reusable heading…
  <a href="#" class="text-orange-600 hover:text-orange-900">A link</a>
</h2>
```

After Classmate:

`template.twig`

```html
<h2 class="{{ classmate.get('myHeading').add('mb-3') }}">
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

### `tag`

### `attr`

### HTML

### Isn't this just moving complexity from

## Requirements

- Craft CMS 3.0+
- PHP 7.4+

## Installation

```bash
composer require timkelty/craftcms-classmate
```
