<?php

declare(strict_types=1);

namespace MarkdownBlogTest\Items;

use Laminas\InputFilter\InputFilterInterface;
use Laminas\ServiceManager\Exception\InvalidServiceException;
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

    private array $config;

    public function setUp(): void
    {
        $this->config = [
            'blog' => [
                'type' => 'filesystem',
                'path' => __DIR__ . '/../_data/posts',
                'parser' => Parser::class,
            ]
        ];
    }

    public function testCanInstantiateItemListerInterfaceObject()
    {
        /** @var ContainerInterface|ObjectProphecy $container */
        $container = $this->prophesize(ContainerInterface::class);
        $container
            ->get('config')
            ->willReturn($this->config);
        $container
            ->has(Parser::class)
            ->willReturn(true);
        $container
            ->get(Parser::class)
            ->willReturn(new Parser());

        $inputFilter = $this->prophesize(InputFilterInterface::class);
        $container
            ->get(InputFilterInterface::class)
            ->willReturn($inputFilter->reveal());

        $factory = new ItemListerFactory();
        $itemLister = $factory($container->reveal());
        $this->assertInstanceOf(ItemListerFilesystem::class, $itemLister);
    }

    public function testThrowsExceptionIfTestDirectoryIsNotAvailableOrUsable()
    {
        $this->expectException(\UnexpectedValueException::class);

        $config = [
            'blog' => [
                'type' => 'filesystem',
                'path' => __DIR__ . '/../data/posts',
                'parser' => Parser::class,
            ]
        ];

        /** @var ContainerInterface|ObjectProphecy $container */
        $container = $this->prophesize(ContainerInterface::class);
        $container
            ->get('config')
            ->willReturn($config);
        $container
            ->has(Parser::class)
            ->willReturn(true);
        $container
            ->get(Parser::class)
            ->willReturn(new Parser());

        $inputFilter = $this->prophesize(InputFilterInterface::class);
        $container
            ->get(InputFilterInterface::class)
            ->willReturn($inputFilter->reveal());

        $factory = new ItemListerFactory();
        $factory($container->reveal());
    }

    public function testThrowsExceptionIfParserServiceRetrievedFromTheContainerIsNotOfTheCorrectType()
    {
        $this->expectException(InvalidServiceException::class);
        $this->expectExceptionMessage(sprintf(
            'Parse is not of the correct type. Received %s, but was expecting %s.',
            null,
            Parser::class
        ));

        /** @var ContainerInterface|ObjectProphecy $container */
        $container = $this->prophesize(ContainerInterface::class);
        $container
            ->get('config')
            ->willReturn($this->config);
        $container
            ->has(Parser::class)
            ->willReturn(true);
        $container
            ->get(Parser::class)
            ->willReturn(null);

        $inputFilter = $this->prophesize(InputFilterInterface::class);
        $container
            ->get(InputFilterInterface::class)
            ->willReturn($inputFilter->reveal());

        $factory = new ItemListerFactory();
        $factory($container->reveal());
    }

    /**
     * @dataProvider invalidBlogConfigurationProvider
     */
    public function testThrowsExceptionIfBlogConfigurationIsInvalidOrMissing(?array $config)
    {
        $this->expectException(InvalidServiceException::class);
        $this->expectExceptionMessage('Blog configuration was invalid.');

        /** @var ContainerInterface|ObjectProphecy $container */
        $container = $this->prophesize(ContainerInterface::class);
        $container
            ->get('config')
            ->willReturn($config);
        $container
            ->has(Parser::class)
            ->willReturn(true);
        $container
            ->get(Parser::class)
            ->willReturn(null);

        $inputFilter = $this->prophesize(InputFilterInterface::class);
        $container
            ->get(InputFilterInterface::class)
            ->willReturn($inputFilter->reveal());

        $factory = new ItemListerFactory();
        $factory($container->reveal());
    }

    public function invalidBlogConfigurationProvider(): ?array
    {
        return [
            [
                'blog' => [
                    'type' => 'filesystem',
                    'path' => __DIR__ . '/../_data/posts',
                    'parser' => Parser::class,
                ]
            ],
            [
                null
            ],
        ];
    }

    /**
     * @dataProvider invalidInputFilterProvider
     */
    public function testThrowsExceptionIfInputFilterIsInvalidOrMissing($inputFilter = null)
    {
        if (! $inputFilter instanceof InputFilterInterface) {
            $this->expectException(InvalidServiceException::class);
            $this->expectExceptionMessage('Input filter is invalid.');
        }

        /** @var ContainerInterface|ObjectProphecy $container */
        $container = $this->prophesize(ContainerInterface::class);
        $container
            ->get('config')
            ->willReturn($this->config);
        $container
            ->has(Parser::class)
            ->willReturn(true);
        $container
            ->get(Parser::class)
            ->willReturn(new Parser());
        $container
            ->get(InputFilterInterface::class)
            ->willReturn($inputFilter);

        $factory = new ItemListerFactory();
        $factory($container->reveal());
    }

    public function invalidInputFilterProvider(): array
    {
        return [
            [
                null
            ],
            [
                []
            ]
        ];
    }
}
