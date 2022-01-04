<?php

namespace MarkdownBlog\Feed;

use MarkdownBlog\Entity\BlogArticle;
use MarkdownBlog\Entity\Show;

/**
 * Interface FeedCreatorInterface.
 *
 * @author Matthew Setter <matthew@matthewsetter.com>
 * @copyright 2021 Matthew Setter
 */
interface FeedCreatorInterface
{
    /**
     * Generate a feed file from one or more item objects.
     */
    public function generateFeed(Show $show, array $episodeList = []): string;
}
