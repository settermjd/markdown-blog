<?php

declare(strict_types=1);

namespace MarkdownBlogTest\Sorter;

use MarkdownBlog\Entity\BlogArticle;
use MarkdownBlog\Sorter\SortByReverseDateOrder;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass SortByReverseDateOrder
 */
class SortByReverseDateOrderTest extends TestCase
{
    /**
     * @covers ::__invoke
     */
    public function testResultsAreSortedInTheCorrectOrder()
    {
        $itemListing = [
            new BlogArticle([
                'publishDate' => '2013-01-01'
            ]),
            new BlogArticle([
                'publishDate' => '2015-01-01'
            ]),
            new BlogArticle([
                'publishDate' => '2014-01-01'
            ]),
        ];

        $sorter = new SortByReverseDateOrder();
        usort($itemListing, $sorter);

        $item = array_shift($itemListing);
        $this->assertEquals(new \DateTime('2015-01-01'), $item->getPublishDate());

        $item = array_shift($itemListing);
        $this->assertEquals(new \DateTime('2014-01-01'), $item->getPublishDate());

        $item = array_shift($itemListing);
        $this->assertEquals(new \DateTime('2013-01-01'), $item->getPublishDate());
    }
}
