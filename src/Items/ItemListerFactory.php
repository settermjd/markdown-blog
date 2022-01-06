<?php

declare(strict_types=1);

namespace MarkdownBlog\Items;

use Laminas\InputFilter\InputFilterInterface;
use MarkdownBlog\Items\Adapter\ItemListerFilesystem;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * Class ItemListerFactory.
 *
 * @author Matthew Setter <matthew@matthewsetter.com>
 * @copyright 2021 Matthew Setter
 */
class ItemListerFactory
{
    /**
     * Build an ItemListerInterface object based on a configuration array.
     *
     * The array has to have the following structure:
     *
     * 'blog' => [
     *   'type' => 'filesystem',
     *   'path' => __DIR__ . '/../../data/posts',
     *   'parser' => new Parser(),
     * ]
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): ItemListerInterface
    {
        $config = $container->get('config')['blog'];
        $inputFilter = $container->get(InputFilterInterface::class);

        switch ($config['type']) {
            case 'filesystem':
            default:
                return new ItemListerFilesystem(
                    $config['path'],
                    $config['parser'],
                    $inputFilter,
                    (array_key_exists('cache', $config)) ? $config['cache'] : ''
                );
        }
    }
}
