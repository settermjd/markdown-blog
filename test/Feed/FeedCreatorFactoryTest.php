<?php

declare(strict_types=1);

namespace MarkdownBlogTest\Feed;

use PHPUnit\Framework\TestCase;

/**
 * Class FeedCreatorFactoryTest
 *
 * @coversDefaultClass \MarkdownBlog\Feed\FeedCreatorFactory
 */
class FeedCreatorFactoryTest extends TestCase
{
    /**
     * @covers ::factory
     */
    public function testCanInstantiateTheCorrectFeedObject()
    {
        $this->markTestSkipped();

        $feedTypes = ['rss', 'atom', 'itunes'];

        foreach ($feedTypes as $type) {
            $this->assertInstanceOf(
                '\PodcastSite\Feed\iTunesFeedCreator',
                FeedCreatorFactory::factory($type),
                'Incorrect feed generator object instantiated'
            );
        }
    }
}
