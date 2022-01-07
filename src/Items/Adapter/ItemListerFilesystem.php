<?php

namespace MarkdownBlog\Items\Adapter;

use DirectoryIterator;
use Laminas\Hydrator\ArraySerializableHydrator;
use Laminas\InputFilter\InputFilterInterface;
use MarkdownBlog\Items\ItemListerInterface;
use MarkdownBlog\Iterator\MarkdownFileFilterIterator;
use MarkdownBlog\Entity\BlogArticle;
use Mni\FrontYAML\Document;
use Mni\FrontYAML\Parser;
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
    private InputFilterInterface $inputFilter;
    protected Parser $fileParser;
    protected MarkdownFileFilterIterator $episodeIterator;
    protected ?object $cache = null;

    public function __construct(
        string $postDirectory,
        Parser $fileParser,
        InputFilterInterface $inputFilter,
        $cache = null
    ) {
        $this->postDirectory = $postDirectory;
        $this->fileParser = $fileParser;

        if (is_object($cache)) {
            $this->cache = $cache;
        }

        $this->episodeIterator = new MarkdownFileFilterIterator(
            new DirectoryIterator($this->postDirectory)
        );
        $this->inputFilter = $inputFilter;
    }

    /**
     * Return the available articles.
     */
    public function getArticles($cacheKeySuffix = self::CACHE_KEY_SUFFIX_ALL): array
    {
        if ($this->cache) {
            $cacheKey = self::CACHE_KEY_EPISODES_LIST.$cacheKeySuffix;
            $result = $this->cache->getItem($cacheKey);
            if (!$result) {
                $result = $this->buildArticlesList();
                $this->cache->setItem($cacheKey, $result);
            }
            return $result;
        }

        return $this->buildArticlesList();
    }

    protected function buildArticlesList(): array
    {
        $episodeListing = [];
        foreach ($this->episodeIterator as $file) {
            $article = $this->buildArticleFromFile($file);
            if (! is_null($article)) {
                $episodeListing[] = $article;
            }
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
    public function getArticle($episodeSlug)
    {
        foreach ($this->episodeIterator as $file) {
            $article ??= $this->buildArticleFromFile($file);
            if (! is_null($article) && $article->getSlug() === $episodeSlug) {
                return $article;
            }
        }

        return '';
    }

    public function buildArticleFromFile(\SplFileInfo $file): ?BlogArticle
    {
        $fileContent = file_get_contents($file->getPathname());

        /** @var Document $document */
        $document = $this->fileParser->parse($fileContent, false);
        $articleData = $this->getArticleData($document);

        $this->inputFilter->setData($articleData);
        if (! $this->inputFilter->isValid()) {
            return null;
        }

        return (new ArraySerializableHydrator())
            ->hydrate(
                $this->inputFilter->getValues(),
                new BlogArticle()
            );
    }

    public function getArticleData(Document $document): array
    {
        return [
            'publishDate' => $document->getYAML()['publish_date'] ?? '',
            'slug' => $document->getYAML()['slug'] ?? '',
            'synopsis' => $document->getYAML()['synopsis'] ?? '',
            'title' => $document->getYAML()['title'] ?? '',
            'image' => $document->getYAML()['image'] ?? '',
            'categories' => $document->getYAML()['categories'] ?? [],
            'tags' => $document->getYAML()['tags'] ?? [],
            'content' => $document->getContent(),
        ];
    }
}
