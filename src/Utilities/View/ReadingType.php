<?php

declare(strict_types=1);

namespace Settermjd\MarkdownBlog\Utilities\View;

enum ReadingType
{
    /**
     * Calculates reading time for when reading aloud.
     */
    case Aloud;

    /**
     * Calculates reading time for when reading silently, in the reader's head.
     */
    case Silent;
}
