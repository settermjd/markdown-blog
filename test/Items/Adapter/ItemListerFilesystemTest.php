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
        $filePath = __DIR__ . '/../../_data/posts';
        $episodeLister = new ItemListerFilesystem($filePath, new Parser());

        $content =<<<EOF
### Synopsis

In this episode I have a fireside chat about what it’s like to live the life of a developer evangelist with Jack Skinner, otherwise known as @developerjack, whilst he was at the first BuzzConf. He talked with me about the crazy hours, random locations, shared some stories from the road, such as having a conference call whilst walking down the boarding gate to catch a flight.

If you don’t, yet, know Jack, he’s a developer evangelist at MYOB, which is an Australian software development company specialising in accounting and business management software, the market leader I believe. He shared so much gold in this chat that I’m itching to share it with you. Here’s a summary of the key things he said.

- An evangelist works closely with the community
- He helps developers build awesome software, preferably with our (MYOB’s) platform
- You’re always still learning
- Be really passionate
- Be passionate about one particular thing and grow it from there
- Speak from the heart about what you love

He’s a very warm, genuine, and passionate person, so I know you’re going to love this episode.

### Related Links

EOF;

        $this->assertEquals($episodeLister->getDataDirectory(), __DIR__ . '/../../_data/posts');
        $itemList = $episodeLister->getItemList();
        $this->assertCount(2, $itemList);
        $blogArticle = $itemList[1];
        $this->assertInstanceOf(BlogArticle::class, $blogArticle, 'Built blogArticle is not an BlogArticle instance');
        $this->assertNotNull($blogArticle, 'BlogArticle entity should have been initialised');
        $this->assertEquals('blogArticle-0011', $blogArticle->getSlug());
        $this->assertEquals(new \DateTime('21.12.2015 15:00'), $blogArticle->getPublishDate());
        $this->assertEquals(
            'Item 11 - The Life of a Developer Evangelist, with Developer Jack',
            $blogArticle->getTitle()
        );
        $this->assertEquals($content, $blogArticle->getContent());
    }
}
