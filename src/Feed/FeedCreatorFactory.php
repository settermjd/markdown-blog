<?php

namespace MarkdownBlog\Feed;

/**
 * Class FeedCreatorFactory.
 *
 * @author Matthew Setter <matthew@matthewsetter.com>
 * @copyright 2021 Matthew Setter
 */
class FeedCreatorFactory
{
    public static function factory(string $feedType): FeedCreatorInterface
    {
        switch (strtolower($feedType)) {
            case 'itunes':
            default:
                return new iTunesFeedCreator();
        }
    }
}
