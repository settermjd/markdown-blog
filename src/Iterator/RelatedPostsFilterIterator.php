<?php

declare(strict_types=1);

namespace MarkdownBlog\Iterator;

use Iterator;
use MarkdownBlog\Entity\BlogArticle;

class RelatedPostsFilterIterator extends \FilterIterator
{
    private BlogArticle $blogArticle;

    public function __construct(Iterator $iterator, BlogArticle $blogArticle)
    {
        parent::__construct($iterator);

        $this->blogArticle = $blogArticle;
    }

    /**
     * @inheritDoc
     */
    public function accept()
    {
        /** @var BlogArticle $post */
        $post = $this->getInnerIterator()->current();

        $matchingTags = array_intersect($post->getTags(), $this->blogArticle->getTags());
        $matchingCategories = array_intersect($post->getCategories(), $this->blogArticle->getCategories());

        return (!empty($matchingTags) || !empty($matchingCategories));
    }
}