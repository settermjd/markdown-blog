<?php

declare(strict_types=1);

namespace MarkdownBlog\Iterator;

use DateTime;
use MarkdownBlog\Entity\BlogArticle;

/**
 * Class UpcomingItemFilterIterator.
 *
 * @author Matthew Setter <matthew@matthewsetter.com>
 * @copyright 2015 Matthew Setter
 */
class UpcomingItemFilterIterator extends \FilterIterator
{
    public function __construct(\Iterator $iterator)
    {
        parent::__construct($iterator);
        $this->rewind();
    }

    /**
     * Determine if the current episode has a publish date of later than today.
     */
    public function accept(): bool
    {
        /** @var BlogArticle $episode */
        $episode = $this->getInnerIterator()->current();

        return $episode->getPublishDate() > new DateTime();
    }
}
