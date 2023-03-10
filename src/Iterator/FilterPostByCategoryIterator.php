<?php

declare(strict_types=1);

namespace MarkdownBlog\Iterator;

use FilterIterator;
use Iterator;
use MarkdownBlog\Entity\BlogArticle;

class FilterPostByCategoryIterator extends FilterIterator
{
    private string $category;

    public function __construct(Iterator $iterator, string $tag)
    {
        parent::__construct($iterator);

        $this->category = $tag;
    }

    /**
     * Filter out articles that don't have a matching category.
     * A lowercase comparison is made to reduce the likelihood of false negative matches.
     */
    public function accept(): bool
    {
        /** @var BlogArticle $episode */
        $episode = $this->getInnerIterator()->current();

        return in_array(
            strtolower($this->category),
            array_map('strtolower', $episode->getCategories())
        );
    }
}