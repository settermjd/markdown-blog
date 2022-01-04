<?php

declare(strict_types=1);

namespace MarkdownBlogTest\Entity;

use MarkdownBlog\Entity\BlogArticle;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \MarkdownBlog\Entity\BlogArticle
 */
class BlogArticleTest extends TestCase
{
    /**
     * @covers ::__construct
     */
    public function testCanSetAndRetrieveUserProperties()
    {
        $options = [
            'publishDate' => '2015-01-01',
            'slug' => '/blogArticle-001',
            'title' => 'BlogArticle 001',
            'content' => 'Lorem ipsum dolar',
            'image' => 'http://traffic.libsyn.com/thegeekyfreelancer/FreeTheGeek-Episode0002.mp3',
            'tags' => ['PHP', 'Docker'],
            'categories' => ['Software Development'],
        ];

        $blogArticle = new BlogArticle($options);

        $this->assertEquals(
            new \DateTime($options['publishDate']),
            $blogArticle->getPublishDate()
        );
        $this->assertEquals($options['slug'], $blogArticle->getSlug());
        $this->assertEquals($options['title'], $blogArticle->getTitle());
        $this->assertEquals($options['content'], $blogArticle->getContent());
        $this->assertEquals($options['image'], $blogArticle->getImage());
        $this->assertEquals($options['categories'], $blogArticle->getCategories());
        $this->assertEquals($options['tags'], $blogArticle->getTags());
    }

    /**
     * @covers ::getSynopsis
     */
    public function testCanRetrieveShortSynopsis()
    {
        $content = <<<EOF
In this blogArticle, I have a fireside chat with internationally recognized PHP expert, and all around good fella [Paul M. Jones](http://paul-m-jones.com), about one of his all-time favorite books - [The Mythical Man Month](http://www.amazon.co.uk/The-Mythical-Man-month-Software-Engineering/dp/0201835959).

We talk about why the book is so valuable to him, how it's helped shape his career over the years, and the lessons it can teach all of us as software developers, lessons still relevant over 50 years after it was first published, in 1975.

I've also got updates on what's been happening for me personally in my freelancing business; including speaking at php[world], attending the inaugural PHP South Coast, **and much, much more**.

> **Correction:** Thanks to [@asgrim](https://twitter.com/@asgrim) for correcting me about employers rarely, if ever, paying for flights and hotels when sending staff to conferences. That was a slip up on my part. I'd only meant to say that they cover the costs of the ticket.
EOF;

        $synopsis = 'In this blogArticle, I have a fireside chat with internationally recognized PHP expert Paul M. Jones about one of his all-time favorite books, The Mythical Man Month.';

        $options = [
            "publishDate" => "2015-01-01",
            "slug" => "blogArticle-001",
            "title" => "BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001",
            "content" => $content,
            "synopsis" => $synopsis,
            "image" => "http://traffic.libsyn.com/thegeekyfreelancer/FreeTheGeek-Episode0002.mp3",
            'tags' => ['PHP', 'Docker'],
            'categories' => ['Software Development'],
        ];

        $blogArticle = new BlogArticle($options);

        $this->assertEquals($blogArticle->getSynopsis(), $synopsis, "The retrieved synopsis does not match the expected value.");
    }

    /**
     * @covers ::getSynopsis
     */
    public function testCanRetrieveSynopsisFromContentStrippingHeaderTag()
    {
        $content = <<<EOF
### Synopsis

In this blogArticle, I have a fireside chat with internationally recognized PHP expert, and all around good fella [Paul M. Jones](http://paul-m-jones.com), about one of his all-time favorite books - [The Mythical Man Month](http://www.amazon.co.uk/The-Mythical-Man-month-Software-Engineering/dp/0201835959).

We talk about why the book is so valuable to him, how it's helped shape his career over the years, and the lessons it can teach all of us as software developers, lessons still relevant over 50 years after it was first published, in 1975.

I've also got updates on what's been happening for me personally in my freelancing business; including speaking at php[world], attending the inaugural PHP South Coast, **and much, much more**.

### Related Links

- [The E-Myth Revisited by Michael E. Gerber](http://www.amazon.co.uk/The-E-Myth-Revisited-Michael-Gerber-ebook/dp/B000RO9VJK)
- [How to Network – Even if You’re Self-Conscious](http://www.matthewsetter.com/how-to-network-even-if-you-are-self-conscious/)

> **Correction:** Thanks to [@asgrim](https://twitter.com/@asgrim) for correcting me about employers rarely, if ever, paying for flights and hotels when sending staff to conferences. That was a slip up on my part. I'd only meant to say that they cover the costs of the ticket.
EOF;

        $synopsis = "In this blogArticle, I have a fireside chat with internationally recognized PHP expert Paul M. Jones, about one of his all-time favorite books, The Mythical Man Month. We talk about why the book is so valuable to him, how it's helped shape his career over the years, and the lessons it can teach all of us as software developers, lessons still relevant over 50 years after it was first published, in 1975. I've also got updates on what's been happening for me personally in my freelancing business; including speaking at php[world], attending the inaugural PHP South Coast, and much, much more.";

        $options = [
            "publishDate" => "2015-01-01",
            "slug" => "blogArticle-001",
            "title" => "BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001",
            "content" => $content,
            "synopsis" => $synopsis,
            "image" => "http://traffic.libsyn.com/thegeekyfreelancer/FreeTheGeek-Episode0002.mp3",
            'tags' => ['PHP', 'Docker'],
            'categories' => ['Software Development'],
        ];

        $blogArticle = new BlogArticle($options);

        $this->assertEquals(
            $blogArticle->getSynopsis(),
            $synopsis,
            "The retrieved synopsis does not match the expected value."
        );
    }

    /**
     * @covers ::getSynopsis
     */
    public function testReturnsFalseWhenNoSynopsisFoundOrPresent()
    {
        $content = <<<EOF
### Related Links

- [The E-Myth Revisited by Michael E. Gerber](http://www.amazon.co.uk/The-E-Myth-Revisited-Michael-Gerber-ebook/dp/B000RO9VJK)
- [How to Network – Even if You’re Self-Conscious](http://www.matthewsetter.com/how-to-network-even-if-you-are-self-conscious/)

> **Correction:** Thanks to [@asgrim](https://twitter.com/@asgrim) for correcting me about employers rarely, if ever, paying for flights and hotels when sending staff to conferences. That was a slip up on my part. I'd only meant to say that they cover the costs of the ticket.
EOF;

        $options = [
            "publishDate" => "2015-01-01",
            "slug" => "blogArticle-001",
            "title" => "BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001",
            "content" => $content,
            "synopsis" => '',
            "image" => "http://traffic.libsyn.com/thegeekyfreelancer/FreeTheGeek-Episode0002.mp3",
            'tags' => ['PHP', 'Docker'],
            'categories' => ['Software Development'],
        ];

        $blogArticle = new BlogArticle($options);

        $this->assertEquals(
            $blogArticle->getSynopsis(),
            '',
            "The retrieved synopsis does not match the expected value."
        );
    }
}
