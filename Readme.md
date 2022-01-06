# Markdown Blog

This is a Markdown-based blog module, one that does just the basics of converting a series of, minimalist, Markdown files with Yaml front-matter into an array of BlogArticle entities. 
These entities can be used to provide a list of blog articles, or to render a given article.

## Usage

### Use standalone

The code can be used standalone or as part of code in larger, likely framework-based, setup.

If you want to use the code standalone, you need to first create a series of Markdown files that have YAML front-matter. 
Then, you can use the code below as a starting point, to convert the Markdown files into BlogArticle entities, which you can then paginate over or render individually using the templating engine of choice (if you are using one).

```php
require_once '../vendor/autoload.php';

$itemLister = new \MarkdownBlog\Items\Adapter\ItemListerFilesystem(
    __DIR__ . '/../data/posts',
    new \Mni\FrontYAML\Parser()
);

/** @var \MarkdownBlog\Entity\BlogArticle $item */
$items = $itemLister->getArticles();
```

### Use as part of a framework

If you are using the code as part of a framework, ideally one that implements [PSR-11](https://www.php-fig.org/psr/psr-11/), such as Mezzio, then you can take a slightly different approach.

In this case, first add a configuration entry to your application's configuration that has the following structure.

```php
use Mni\FrontYAML\Parser;

return [
    'blog' => [
        'type' => 'filesystem',
        'path' => __DIR__ . '/../../data/posts',
        'parser' => new Parser(),
    ]
];
```

Then, in your DI container, add an entry where the key is `ItemListerInterface::class` and the value is `ItemListerFactory`. 
The example below shows how to do this when using Mezzio.

```php
'factories'  => [
    \MarkdownBlog\Items\ItemListerInterface::class => \MarkdownBlog\Items\ItemListerFactory::class,
]
```

This will then instantiate an `ItemListerInterface` object, based on your configuration and make it available to your application, when retrieved.

Then, use it as in the standalone version above, such as in the example below.

```php
/** @var \MarkdownBlog\Entity\BlogArticle $item */
$items = $itemLister->getArticles();
```
