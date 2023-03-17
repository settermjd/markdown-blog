<?php

declare(strict_types=1);

namespace MarkdownBlog\Iterator;

use DateTime;
use Exception;
use Iterator;
use MarkdownBlog\Entity\BlogArticle;

/**
 * Class PublishedItemFilterIterator.
 *
 * @author Matthew Setter <matthew@matthewsetter.com>
 * @copyright 2015 Matthew Setter
 */
class PublishedItemFilterIterator extends \FilterIterator implements \Countable
{
    public function __construct(Iterator $iterator)
    {
        parent::__construct($iterator);
        $this->rewind();
    }

    /**
     * Determine if the current item has a publish date of later than today.
     *
     * @throws Exception
     */
    public function accept(): bool
    {
        /** @var BlogArticle $episode */
        $episode = $this->getInnerIterator()->current();

        return $episode->getPublishDate() <= new DateTime();
    }

    public function count(): int
    {
        return iterator_count($this->getInnerIterator());
    }
}
