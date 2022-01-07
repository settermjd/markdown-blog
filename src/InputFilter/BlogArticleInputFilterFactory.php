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
use Laminas\Validator\StringLength;

class BlogArticleInputFilterFactory
{
    public function __invoke(): InputFilterInterface
    {
        $publishDate = new Input('publishDate');
        $publishDate
            ->getValidatorChain()
            ->attach(new Date(
                         [
                             'format' => 'd.m.Y',
                             'strict' => true
                         ]
                     ))
            ->attach(new StringLength(8));
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
        $tags
            ->getValidatorChain()
            ->attach(new IsCountable());

        $slug = new Input('slug');
        $slug
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
        $image
            ->getFilterChain()
            ->attach(new StringTrim())
            ->attach(new StripNewlines())
            ->attach(new StripTags());

        $content = new Input('content');
        $content
            ->getFilterChain()
            ->attach(new StringTrim())
            ->attach(new StripTags());

        return (new InputFilter())
            ->add($publishDate)
            ->add($slug)
            ->add($title)
            ->add($image)
            ->add($content)
            ->add($tags)
            ->add($categories)
            ;
    }
}
