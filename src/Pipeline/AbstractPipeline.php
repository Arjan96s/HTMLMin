<?php

namespace ArjanSchouten\HTMLMin\Pipeline;

use League\Pipeline\CallableStage;
use ArjanSchouten\HTMLMin\MinifyPipelineContext;
use ArjanSchouten\HTMLMin\Minifiers\Html\CommentMinifier;
use ArjanSchouten\HTMLMin\Placeholders\CommentPlaceholder;
use ArjanSchouten\HTMLMin\Minifiers\Html\WhitespaceMinifier;
use ArjanSchouten\HTMLMin\Placeholders\WhitespacePlaceholder;
use ArjanSchouten\HTMLMin\Minifiers\Html\AttributeQuoteMinifier;
use ArjanSchouten\HTMLMin\Minifiers\Html\EmptyAttributeMinifier;
use ArjanSchouten\HTMLMin\Minifiers\Html\JavascriptEventsMinifier;
use ArjanSchouten\HTMLMin\Minifiers\Html\RedundantAttributeMinifier;

abstract class AbstractPipeline
{
    private static $placeholderPipelineBuilder;

    private static $minifierPipelineBuilder;

    public function placeholders($options = [])
    {
        if (self::$placeholderPipelineBuilder == null) {
            self::$placeholderPipelineBuilder = (new PipelineBuilder())
                ->add(new CommentPlaceholder())
                ->add(new WhitespacePlaceholder());
        }

        return self::$placeholderPipelineBuilder;
    }

    public function minifiers($options = [])
    {
        if (self::$minifierPipelineBuilder == null) {
            self::$minifierPipelineBuilder = (new PipelineBuilder())
                ->add(new AttributeQuoteMinifier(), $options)
                ->add(new CommentMinifier(), $options)
                ->add(new EmptyAttributeMinifier(), $options)
                ->add(new JavascriptEventsMinifier(), $options)
                ->add(new RedundantAttributeMinifier(), $options)
                ->add(new WhitespaceMinifier(), $options);
        }

        return self::$minifierPipelineBuilder;
    }

    public function restores($options = [])
    {
        return new CallableStage(function (MinifyPipelineContext $context) {
            return $context->setContents($context->getPlaceholderContainer()->restorePlaceholders($context->getContents()));
        });
    }
}
