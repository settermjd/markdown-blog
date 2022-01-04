<?php

namespace MarkdownBlog\Items\Adapter;

use ArrayIterator;
use DirectoryIterator;
use MarkdownBlog\Items\ItemListerInterface;
use MarkdownBlog\Iterator\CurrentItemFilterIterator;
use MarkdownBlog\Sorter\SortByReverseDateOrder;
use MarkdownBlog\Entity\BlogArticle;
use MarkdownBlog\Iterator\UpcomingItemFilterIterator;
use MarkdownBlog\Iterator\PastItemFilterIterator;
use Mni\FrontYAML\Document;
use Traversable;

/**
 * Class ItemListerFilesystem.
 *
 * @author Matthew Setter <matthew@matthewsetter.com>
 * @copyright 2015 Matthew Setter
 */
class ItemListerFilesystem implements ItemListerInterface
{
    public const CACHE_KEY_EPISODES_LIST = 'episodes_';
    public const CACHE_KEY_SUFFIX_ALL = 'all';
    public const CACHE_KEY_SUFFIX_UPCOMING = 'upcoming';
    public const CACHE_KEY_SUFFIX_PAST = 'past';

    protected string $postDirectory;
    protected object $fileParser;
    protected CurrentItemFilterIterator $episodeIterator;
    protected ?object $cache = null;

    public function __construct(string $postDirectory, object $fileParser, $cache = null)
    {
        $this->postDirectory = $postDirectory;
        $this->fileParser = $fileParser;

        if (is_object($cache)) {
            $this->cache = $cache;
        }

        $this->episodeIterator = new CurrentItemFilterIterator(
            new DirectoryIterator($this->postDirectory)
        );
    }

    /**
     * Return the current available items.
     *
     * @return array|Traversable
     */
    public function getItemList($cacheKeySuffix = self::CACHE_KEY_SUFFIX_ALL): array
    {
        if ($this->cache) {
            $cacheKey = self::CACHE_KEY_EPISODES_LIST.$cacheKeySuffix;
            $result = $this->cache->getItem($cacheKey);
            if (!$result) {
                $result = $this->buildEpisodesList();
                $this->cache->setItem($cacheKey, $result);
            }
            return $result;
        }

        return $this->buildEpisodesList();
    }

    public function getUpcomingItems(): array
    {
        $list = [];
        $upcomingEpisodeIterator = new UpcomingItemFilterIterator(
            new ArrayIterator(
                $this->getItemList(self::CACHE_KEY_SUFFIX_UPCOMING)
            )
        );

        foreach ($upcomingEpisodeIterator as $upcomingEpisode) {
            $list[] = $upcomingEpisode;
        }

        return $list;
    }

    /**
     * Get all past episodes, optionally excluding the latest.
     */
    public function getPastItems(bool $includeLatest = true): array
    {
        $list = [];
        $iterator = new PastItemFilterIterator(
            new ArrayIterator(
                $this->getItemList(self::CACHE_KEY_SUFFIX_PAST)
            )
        );

        foreach ($iterator as $episode) {
            $list[] = $episode;
        }

        // Sort the records in reverse date order
        $sorter = new SortByReverseDateOrder();
        usort($list, $sorter);

        if (!$includeLatest) {
            return array_splice($list, 1);
        }

        return $list;
    }

    /**
     * @return BlogArticle|void
     */
    public function getLatestItem()
    {
        $episodes = $this->getPastItems();
        if (!empty($episodes)) {
            return $episodes[0];
        }
    }

    protected function buildEpisodesList(): array
    {
        $episodeListing = [];
        foreach ($this->episodeIterator as $file) {
            $episodeListing[] = $this->buildEpisode($file);
        }

        return $episodeListing;
    }

    /**
     * Returns the directory being searched by the episode lister.
     */
    public function getDataDirectory(): string
    {
        return $this
            ->episodeIterator
            ->getInnerIterator()
            ->getPath();
    }

    /**
     * @return BlogArticle|string
     */
    public function getItem($episodeSlug)
    {
        foreach ($this->episodeIterator as $file) {
            $fileContent = file_get_contents($file->getPathname());
            /** @var Document $document */
            $document = $this->fileParser->parse($fileContent, false);
            if ($document->getYAML()['slug'] === $episodeSlug) {
                $episode = new BlogArticle($this->getEpisodeData($document));

                return new BlogArticle($this->getEpisodeData($document));
            }
        }

        return '';
    }

    public function buildEpisode(\SplFileInfo $file): BlogArticle
    {
        $fileContent = file_get_contents($file->getPathname());

        /** @var Document $document */
        $document = $this->fileParser->parse($fileContent, false);

        return new BlogArticle($this->getEpisodeData($document));
    }

    public function getEpisodeData(Document $document): array
    {
        return [
            'publishDate' => $document->getYAML()['publish_date'] ?? '',
            'slug' => $document->getYAML()['slug'] ?? '',
            'title' => $document->getYAML()['title'] ?? '',
            'image' => $document->getYAML()['image'] ?? '',
            'categories' => $document->getYAML()['categories'] ?? [],
            'tags' => $document->getYAML()['tags'] ?? [],
            'content' => $document->getContent(),
        ];
    }
}
