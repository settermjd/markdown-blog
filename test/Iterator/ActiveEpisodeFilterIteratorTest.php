<?php

declare(strict_types=1);

namespace MarkdownBlogTest;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;

class ActiveEpisodeFilterIteratorTest extends TestCase
{
    private vfsStreamDirectory $root;

    protected function setUp(): void
    {
        $this->root = vfsStream::setup();
    }

    // tests
    public function testMe()
    {
        /*$cache = new FileSystemCache($this->root->url() . '/cache');
        $this->assertTrue($this->root->hasChild('cache'));*/
    }
}
