<?php

declare(strict_types=1);

namespace MarkdownBlogTest\Items;

use Laminas\InputFilter\InputFilterInterface;
use MarkdownBlog\InputFilter\BlogArticleInputFilterFactory;
use MarkdownBlog\Items\Adapter\ItemListerFilesystem;
use MarkdownBlog\Items\ItemListerFactory;
use Mni\FrontYAML\Parser;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Container\ContainerInterface;

class ItemListerFactoryTest extends TestCase
{
    use ProphecyTrait;

    public function testCanInstantiateItemListerInterfaceObject()
    {
        $config = [
            'blog' => [
                'type' => 'filesystem',
                'path' => __DIR__ . '/../../data/posts',
                'parser' => new Parser(),
            ]
        ];

        /** @var ContainerInterface|ObjectProphecy $container */
        $container = $this->prophesize(ContainerInterface::class);
        $container
            ->get('config')
            ->willReturn($config);

        $inputFilter = $this->prophesize(InputFilterInterface::class);
        $container
            ->get(InputFilterInterface::class)
            ->willReturn($inputFilter->reveal());

        $factory = new ItemListerFactory();
        $itemLister = $factory($container->reveal());
        $this->assertInstanceOf(ItemListerFilesystem::class, $itemLister);
    }
}