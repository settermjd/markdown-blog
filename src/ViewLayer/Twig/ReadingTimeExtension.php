<?php

declare(strict_types=1);

namespace Settermjd\MarkdownBlog\ViewLayer\Twig;

use Settermjd\MarkdownBlog\Utilities\View\ReadingTimeCalculator;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * MarkdownToHtml is a simplistic extension for laminas-view to convert a Markdown
 * string into the equivalent HTML using CommonMarkConverter from the League of
 * Extraordinary Packages.
 */
final class ReadingTimeExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_reading_time', [ReadingTimeCalculator::class, 'getReadingTime']),
        ];
    }
}
