<?php

use ArjanSchouten\HTMLMin\Minify;
use ArjanSchouten\HTMLMin\MinifyContext;
use ArjanSchouten\HTMLMin\PlaceholderContainer;

class MinifyTest extends PHPUnit_Framework_TestCase
{
    public function testBuildPipeline()
    {
        $minify = new Minify();
        $placeholderContainer = new PlaceholderContainer();
        $context = new MinifyContext($placeholderContainer);
        $context->setContents('<html><!----></html>');

        $this->assertEquals('<html></html>', $minify->run($context)->getContents());
    }
}
