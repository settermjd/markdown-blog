<?php

declare(strict_types=1);

namespace MarkdownBlog\Sorter;

use DateTime;
use Exception;
use MarkdownBlog\Entity\BlogArticle;

/**
 * A simple invokable class to help sort a list of episodes.
 *
 * Class SortByReverseDateOrder
 *
 * @author Matthew Setter <matthew@matthewsetter.com>
 * @copyright 2015 Matthew Setter
 */
class SortByReverseDateOrder
{
    /**
     * Sort the entries in reverse date order.
     *
     * @throws Exception
     */
    public function __invoke(BlogArticle $a, BlogArticle $b): int
    {
        $firstDate = $a->getPublishDate();
        $secondDate = $b->getPublishDate();

        if ($firstDate == $secondDate) {
            return 0;
        }

        return ($firstDate > $secondDate) ? -1 : 1;
    }
}
