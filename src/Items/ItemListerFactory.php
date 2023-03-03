<?php

declare(strict_types=1);

namespace MarkdownBlog\Items;

use Laminas\InputFilter\InputFilterInterface;
use Laminas\ServiceManager\Exception\InvalidServiceException;
use MarkdownBlog\Items\Adapter\ItemListerFilesystem;
use Mni\FrontYAML\Parser;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Log\LoggerInterface;

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
     *   'parser' => Parser::class,
     * ]
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): ItemListerInterface
    {
        $config = $container->get('config');
        if (empty($config['blog'])) {
            throw new InvalidServiceException('Blog configuration was invalid.');
        }
        $blogConfig = $config['blog'];

        $inputFilter = $container->get(InputFilterInterface::class);
        if (! $inputFilter instanceof InputFilterInterface) {
            throw new InvalidServiceException('Input filter is invalid.');
        }

        $parser = $container->get($blogConfig['parser']);
        if (! $parser instanceof $blogConfig['parser']) {
            throw new InvalidServiceException(sprintf(
                'Parse is not of the correct type. Received %s, but was expecting %s.',
                $parser,
                $blogConfig['parser']
            ));
        }
        if ($container->has(LoggerInterface::class)) {
            $logger = $container->get(LoggerInterface::class);
        }

        switch ($blogConfig['type']) {
            case 'filesystem':
            default:
                return new ItemListerFilesystem(
                    $blogConfig['path'],
                    $parser,
                    $inputFilter,
                    (array_key_exists('cache', $blogConfig)) ? $blogConfig['cache'] : '',
                    $logger ?? null
                );
        }
    }
}
