<?php

class UpdateSpamHeuristicsTask extends BuildTask {
    protected $title = 'Update Spam Heuristics Task';

    protected $description = 'For unprocessed comments update link and word count';

    function run($request) {
        $comments = Comment::get()->filter('Processed', 0);
        foreach ($comments as $comment) {
            error_log('Updating heuristics for ' . $comment->Comment);
            $comment->write();
        }
    }
}
