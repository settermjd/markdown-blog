<?php

declare(strict_types=1);

namespace MarkdownBlog\Items;

/**
 * Interface ItemListerInterface.
 *
 * @author Matthew Setter <matthew@matthewsetter.com>
 * @copyright 2021 Matthew Setter
 */
interface ItemListerInterface
{
    public function getArticle(string $episodeSlug);
    public function getArticles();
}
