<?php

declare(strict_types=1);

namespace MarkdownBlog\Iterator;
use Iterator;
use MarkdownBlog\Entity\BlogArticle;

class FilterPostByTagIterator extends \FilterIterator
{
    private string $tag;

    public function __construct(Iterator $iterator, string $tag)
    {
        parent::__construct($iterator);

        $this->tag = $tag;
    }

    /**
     * Filter out articles that don't have a matching category.
     * A lowercase comparison is made to reduce the likelihood of false negative matches.
     */
    public function accept()
    {
        /** @var BlogArticle $post */
        $post = $this->getInnerIterator()->current();

        return in_array(
            strtolower($this->tag),
            array_map('strtolower', $post->getTags())
        );
    }
}