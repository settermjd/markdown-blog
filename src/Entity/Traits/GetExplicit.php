<?php

declare(strict_types=1);

namespace MarkdownBlog\Entity\Traits;

/**
 * Trait GetExplicit
 * As both the Show and BlogArticle entities use this function,
 * it's being shared via a trait.
 *
 * @author Matthew Setter <matthew@matthewsetter.com>
 * @copyright 2021 Matthew Setter
 */
trait GetExplicit
{
    protected string $explicit;

    /**
     * Is the item of an explicit nature or not.
     *
     * @return string
     */
    public function getExplicit()
    {
        return (is_null($this->explicit)) ? 'no' : $this->explicit;
    }
}
