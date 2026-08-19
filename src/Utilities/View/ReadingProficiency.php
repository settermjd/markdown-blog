<?php

declare(strict_types=1);

namespace Settermjd\MarkdownBlog\Utilities\View;

/**
 * This enum provides a simple representation of reading speeds.
 *
 * Based on https://wordstotime.com/, it provides values for three reading
 * speeds:
 *
 * - "Slow"
 * - "Average"
 * - "Fast"
 */
enum ReadingProficiency
{
    case Slow;
    case Average;
    case Fast;
}
