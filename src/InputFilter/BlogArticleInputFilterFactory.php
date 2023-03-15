<?php

declare(strict_types=1);


namespace MarkdownBlog\InputFilter;


use Laminas\Filter\HtmlEntities;
use Laminas\Filter\StringTrim;
use Laminas\Filter\StripNewlines;
use Laminas\Filter\StripTags;
use Laminas\InputFilter\Input;
use Laminas\InputFilter\InputFilter;
use Laminas\InputFilter\InputFilterInterface;
use Laminas\Validator\Date;
use Laminas\Validator\IsCountable;
use Laminas\Validator\Regex;
use Laminas\Validator\StringLength;

class BlogArticleInputFilterFactory
{
    public function __invoke(): InputFilterInterface
    {
        $publishDate = new Input('publishDate');
        $publishDate
            ->getValidatorChain()
            ->attach(new Regex(
                         [
                             'pattern' => '/\d{4}\-\d{2}\-\d{2}|(\d{2}\.){2}\d{4}/',
                         ]
                     ));
        $publishDate
            ->getFilterChain()
            ->attach(new StringTrim())
            ->attach(new StripNewlines())
            ->attach(new StripTags());

        $categories = new Input('categories');
        $categories
            ->getValidatorChain()
            ->attach(new IsCountable());

        $tags = new Input('tags');
        $tags->setAllowEmpty(true);
        $tags->setRequired(false);
        $tags
            ->getValidatorChain()
            ->attach(new IsCountable());

        $slug = new Input('slug');
        $slug
            ->getFilterChain()
            ->attach(new StringTrim())
            ->attach(new StripNewlines())
            ->attach(new StripTags());

        $synopsis = new Input('synopsis');
        $synopsis
            ->getFilterChain()
            ->attach(new StringTrim())
            ->attach(new StripNewlines())
            ->attach(new StripTags());

        $title = new Input('title');
        $title
            ->getFilterChain()
            ->attach(new StringTrim())
            ->attach(new StripNewlines())
            ->attach(new StripTags());

        $image = new Input('image');
        $image->setAllowEmpty(true);
        $image->setRequired(false);
        $image
            ->getFilterChain()
            ->attach(new StringTrim())
            ->attach(new StripNewlines())
            ->attach(new StripTags());

        $content = new Input('content');
        $content
            ->getFilterChain()
            ->attach(new StringTrim());

        return (new InputFilter())
            ->add($publishDate)
            ->add($slug)
            ->add($synopsis)
            ->add($title)
            ->add($image)
            ->add($content)
            ->add($tags)
            ->add($categories)
            ;
    }
}
