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
     * Allow an article if it has any of the same tags or categories but doesn't have the same slug,
     * i.e, isn't the same article.
     */
    public function accept(): bool
    {
        /** @var BlogArticle $post */
        $post = $this->getInnerIterator()->current();

        $matchingTags = array_intersect($post->getTags(), $this->blogArticle->getTags());
        $matchingCategories = array_intersect($post->getCategories(), $this->blogArticle->getCategories());

        return (!empty($matchingTags) || !empty($matchingCategories));
    }
}