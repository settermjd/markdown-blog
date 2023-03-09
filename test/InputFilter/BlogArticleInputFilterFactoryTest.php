<?php

namespace MarkdownBlogTest\InputFilter;

use MarkdownBlog\InputFilter\BlogArticleInputFilterFactory;
use PHPUnit\Framework\TestCase;

class BlogArticleInputFilterFactoryTest extends TestCase
{
    /**
     * @dataProvider filterDataProvider
     */
    public function testCanSetAndRetrieveDataCorrectly(array $filterData, array $expectedResult)
    {
        $filter = (new BlogArticleInputFilterFactory())();
        $filter->setData($filterData);
        $this->assertTrue($filter->isValid());
        $this->assertEmpty($filter->getMessages());
        $this->assertCount(1, $filter->getValue('categories'));
        $this->assertCount(5, $filter->getValue('tags'));
        $this->assertArrayHasKey('categories', $filter->getValidInput());
        $this->assertArrayHasKey('content', $filter->getValidInput());
        $this->assertArrayHasKey('image', $filter->getValidInput());
        $this->assertArrayHasKey('publishDate', $filter->getValidInput());
        $this->assertArrayHasKey('slug', $filter->getValidInput());
        $this->assertArrayHasKey('synopsis', $filter->getValidInput());
        $this->assertArrayHasKey('tags', $filter->getValidInput());
        $this->assertArrayHasKey('title', $filter->getValidInput());
    }

    public function filterDataProvider(): array
    {
        return [
            [
                [
                    'categories' => [
                        'Education'
                    ],
                    'content' => "After an unexpected hiatus, I'm back learning Golang and continuing to grow my knowledge in this wonderful, fun, and light language.
If you've been following [my journey so far](/tags/golang/), I've been working through [the Go Tour](https://go.dev/tour/welcome/1) as a way of having a proper sense of structure to my learning efforts.

However, toward the end of the time, before the unexpected break, I felt that it was becoming quite arbitrary to follow that approach.
    This is because the things I was learning weren't tied to a practical project which held any genuine sense of meaning for me.",
                    'image' => 'learning-golang-day13.png',
                    'publishDate' => '2023-04-02',
                    'slug' => 'learning-golang/day-13/',
                    'synopsis' => 'Here we are on day 13. Today, I continued learning Golang by working on the Golang version of my PHP/Python weather station, adding a function to render static pages. Let me share my learnings with you.',
                    'tags' => [
                        'Golang', 'Go', 'Gorilla Mux', 'Regular Expressions', 'vim-go'
                    ],
                    'title' => 'Learning Golang, Day 13 — Regular Expressions and the Gorilla Mux Router',
                ],
                [

                ]
            ]
        ];
    }
}
