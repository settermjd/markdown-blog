<?php

declare(strict_types=1);

namespace MarkdownBlogTest\Items\Adapter;

use MarkdownBlog\Entity\BlogArticle;
use MarkdownBlog\Items\ItemListerFactory;
use MarkdownBlog\Items\ItemListerInterface;
use PHPUnit\Framework\TestCase;
use MarkdownBlog\Items\Adapter\ItemListerFilesystem;
use Mni\FrontYAML\Parser;

/**
 * Class ItemListerFilesystem
 *
 * @coversDefaultClass \MarkdownBlog\Items\Adapter\ItemListerFilesystem
 */
class ItemListerFilesystemTest extends TestCase
{
    /**
     * @covers ::buildEpisode
     */
    public function testAdapterCanProperlyBuildEpisodeObject()
    {
        $this->markTestIncomplete();
        $filePath = __DIR__ . '/../../_data/posts';
        $episodeLister = new ItemListerFilesystem($filePath, new Parser());

        $content =<<<EOF
### Synopsis

In this blogArticle I have a fireside chat about what it’s like to live the life of a developer evangelist with Jack Skinner, otherwise known as @developerjack, whilst he was at the first BuzzConf. He talked with me about the crazy hours, random locations, shared some stories from the road, such as having a conference call whilst walking down the boarding gate to catch a flight.

If you don’t, yet, know Jack, he’s a developer evangelist at MYOB, which is an Australian Software Accounting firm, the market leader I believe. He shared so much gold in this chat that I’m itching to share it with you. Here’s a summary of the key things he said.

- An evangelist works closely with the community
- He helps developers build awesome software, preferably with our (MYOB’s) platform
- You’re always still learning
- Be really passionate
- Be passionate about one particular thing and grow it from there
- Speak from the heart about what you love

He’s a very warm, genuine, and passionate person, so I know you’re going to love this blogArticle.

### Related Links
EOF;

        $fileInfo = new \SplFileInfo($filePath . '/blogArticle-0011.md');
        $blogArticle = $episodeLister->buildEpisode($fileInfo);

        $this->assertInstanceOf('\PodcastSite\Entity\Episode', $blogArticle, 'Built blogArticle is not an BlogArticle instance');
        $this->assertNotNull($blogArticle, 'BlogArticle entity should have been initialised');
        $this->assertEquals('blogArticle-0011', $blogArticle->getSlug());
        $this->assertEquals('21.12.2015 15:00', $blogArticle->getPublishDate());
        $this->assertEquals(
            'BlogArticle 11 - The Life of a Developer Evangelist, with Developer Jack',
            $blogArticle->getTitle()
        );
        $this->assertEquals(
            'http://traffic.libsyn.com/thegeekyfreelancer/FreeTheGeek-Episode0011.mp3',
            $blogArticle->getLink()
        );
        $this->assertEquals('FreeTheGeek-Episode0011.mp3', $blogArticle->getDownload());
        $this->assertEquals($content, $blogArticle->getContent());
        $this->assertEquals(
            [
                "Matthew Setter" => [
                    'email' => 'matthew@matthewsetter.com',
                    'twitter' => 'settermjd'
                ]
            ],
            $blogArticle->getGuests()
        );
    }

    /**
     * @covers ::hydrateEpisode
     */
    public function testAdapterCanProperlyHydrateEpisodeObject()
    {
        $this->markTestSkipped("Need to revisit this");

        $filePath = __DIR__ . '/../../_data/posts';
        $episodeLister = new ItemListerFilesystem($filePath, new Parser());

        $content = <<<EOF
### Synopsis

In this item I have a fireside chat about what it’s like to live the life of a developer evangelist with Jack Skinner, otherwise known as @developerjack, whilst he was at the first BuzzConf. He talked with me about the crazy hours, random locations, shared some stories from the road, such as having a conference call whilst walking down the boarding gate to catch a flight.

If you don’t, yet, know Jack, he’s a developer evangelist at MYOB, which is an Australian Software Accounting firm, the market leader I believe. He shared so much gold in this chat that I’m itching to share it with you. Here’s a summary of the key things he said.

- An evangelist works closely with the community
- He helps developers build awesome software, preferably with our (MYOB’s) platform
- You’re always still learning
- Be really passionate
- Be passionate about one particular thing and grow it from there
- Speak from the heart about what you love

He’s a very warm, genuine, and passionate person, so I know you’re going to love this item.

### Related Links
EOF;

        $itemData = [
            'content' => $content,
            'tags' => ["PHP", "Docker"],
            'categories' => ["Software Development"],
            'link' => 'http://traffic.libsyn.com/thegeekyfreelancer/FreeTheGeek-Episode0002.mp3',
            'publish_date' => '2015-01-01',
            'slug' => '/item-001',
            'title' => 'BlogArticle 001',
        ];

        $item = $episodeLister->getEpisodeData($itemData);

        $this->assertInstanceOf(BlogArticle::class, $item, 'Built item is not an BlogArticle instance');
        $this->assertNotNull($item, 'BlogArticle entity should have been initialised');
        $this->assertEquals($itemData['slug'], $item->getSlug());
        $this->assertEquals($itemData['publish_date'], $item->getPublishDate());
        $this->assertEquals($itemData['title'], $item->getTitle());
        $this->assertEquals($itemData['link'], $item->getLink());
        $this->assertEquals($content, $item->getContent());
        $this->assertEquals($itemData['guests'], $item->getGuests());
    }

    public function testEpisodeListerImplementsCorrectInterface()
    {
        $filePath = __DIR__ . '/../../_data/posts';
        $episodeLister = new ItemListerFilesystem($filePath, new Parser());
        $this->assertInstanceOf(ItemListerInterface::class, $episodeLister);
    }

    /**
     * @covers ::__construct
     */
    public function testEpisodeListerSearchCorrectDirectory()
    {
        $filePath = __DIR__ . '/../../_data/posts';
        $episodeLister = new ItemListerFilesystem($filePath, new Parser());
        $this->assertEquals(
            $episodeLister->getDataDirectory(),
            __DIR__ . '/../../_data/posts'
        );
    }
}
