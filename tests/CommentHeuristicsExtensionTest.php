<?php

class CommentHeuristicsExtensionTest extends SapphireTest {

    public function setUp() {
        $this->comment = new Comment();
        $this->comment->woot();
        parent::setUp();
    }

    public function testNoWords() {
        $this->comment->Comment  = '';
        $this->comment->updateHeuristics();
        $this->assertEquals(0, $this->comment->NumberOfWords);
        $this->assertEquals(1, $this->comment->Processed);
    }

    public function testOneWord() {
        $this->comment->Comment  = 'Fred';
        $this->comment->updateHeuristics();
        $this->assertEquals(1, $this->comment->NumberOfWords);
        $this->assertEquals(1, $this->comment->Processed);
    }

    public function testManyWords() {
        $this->comment->Comment  = 'Fred Smith is a rather common name';
        $this->comment->updateHeuristics();
        $this->assertEquals(7, $this->comment->NumberOfWords);
        $this->assertEquals(1, $this->comment->Processed);
    }

    public function testMultipleURLs() {
        $this->comment->Comment  = 'Buy viagara.com from http://www.buyviagara.com';
        error_log('TEST FILTER 1: ' . filter_var('http://example.com', FILTER_VALIDATE_URL));
        error_log('TEST FILTER 2: ' . filter_var('wibble', FILTER_VALIDATE_URL));

        if(filter_var('http://example.com', FILTER_VALIDATE_URL) === FALSE) {
            error_log('A URL!');
        }

        $this->comment->updateHeuristics();
        $this->assertEquals(4, $this->comment->NumberOfWords);
        $this->assertEquals(1, $this->comment->NumberOfLinks);
        $this->assertEquals(1, $this->comment->Processed);


    }
}
