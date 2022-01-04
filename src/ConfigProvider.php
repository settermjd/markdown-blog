<?php

declare(strict_types=1);

namespace MarkdownBlog;

use MarkdownBlog\Items\ItemListerFactory;
use MarkdownBlog\Items\ItemListerInterface;

/**
 * The configuration provider for the MarkdownBlog module
 *
 * @see https://docs.laminas.dev/laminas-component-installer/
 */
class ConfigProvider
{
    /**
     * Returns the configuration array
     *
     * To add a bit of a structure, each section is defined in a separate
     * method which returns an array with its configuration.
     */
    public function __invoke() : array
    {
        return [
            'dependencies' => $this->getDependencies(),
            'templates'    => $this->getTemplates(),
        ];
    }

    /**
     * Returns the container dependencies
     */
    public function getDependencies() : array
    {
        return [
            'factories'  => [
                ItemListerInterface::class => ItemListerFactory::class
            ],
        ];
    }

    /**
     * Returns the templates configuration
     */
    public function getTemplates() : array
    {
        return [
            'paths' => [
                'markdown-blog'    => [__DIR__ . '/../templates/'],
            ],
        ];
    }
}
