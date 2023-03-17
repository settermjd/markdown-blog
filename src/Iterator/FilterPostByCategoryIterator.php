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

        $this->category = strtolower($tag);
    }

    /**
     * Filter out articles that don't have a matching category.
     * A lowercase comparison is made to reduce the likelihood of false negative matches.
     */
    public function accept(): bool
    {
        /** @var BlogArticle $episode */
        $episode = $this->getInnerIterator()->current();

        // Filter out empty/null entries, which will break array_map's use of strtolower
        $categories = array_filter($episode->getCategories());

        if (! empty($categories)) {
            return in_array($this->category, array_map('strtolower', $categories));
        }

        return false;
    }
}