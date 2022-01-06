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
        $blogArticle1 = new BlogArticle();
        $blogArticle1->populate(
            [
                'publishDate' => '2013-01-01'
            ]
        );
        $blogArticle2 = new BlogArticle();
        $blogArticle2->populate(
            [
                'publishDate' => '2015-01-01'
            ]
        );
        $blogArticle3 = new BlogArticle();
        $blogArticle3->populate(
            [
                'publishDate' => '2014-01-01'
            ]
        );
        $itemListing = [
            $blogArticle1,
            $blogArticle2,
            $blogArticle3,
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
