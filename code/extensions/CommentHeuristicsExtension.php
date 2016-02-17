<?php

class CommentHeuristicsExtension extends DataExtension {

    private static $db = array(
        'NumberOfLinks' => 'Int',
        'NumberOfEmails' => 'Int',
        'NumberOfWords' => 'Int',
        'Processed' => 'Boolean(0)',
        'WordsPerLink' => 'Float'
    );

    public function onBeforeWrite() {
        $this->updateHeuristics();
        parent::onBeforeWrite();
    }

    public function updateHeuristics() {
        error_log('OBW');
        $trimmed = trim($this->owner->Comment);
        if (empty($trimmed)) {
            $this->owner->NumberOfWords = 0;
            $this->owner->NumberOfLinks = 0;
        } else {
            $tokens = preg_split ('/[,\s]+/', $this->owner->Comment);
            $this->owner->NumberOfWords = sizeof($tokens);
            error_log(print_r($tokens,1));
            $this->owner->NumberOfLinks = 0;
            $this->owner->NumberOfEmails = 0;
            foreach ($tokens as $token) {
                //$token = 'http://www.buyviagara.com';

                error_log('TOKEN: *' . $token . '*');
                error_log('URL CHECK: ' . filter_var($token, FILTER_VALIDATE_URL));
                if (filter_var($token, FILTER_VALIDATE_URL)) {
                    error_log('^^^^ URL');
                    $this->owner->NumberOfLinks++;
                } else {
                    // check for likes of <a href=http://www.spamsite.com/>spammer
                    // if href and http present in string assume it's a link
                    $lower = strtolower($token);
                    if (strpos($token, 'href') !== false &&
                        strpos($token, 'http') !== false) {
                        $this->owner->NumberOfLinks++;
                    }
                }

                if (filter_var($token, FILTER_VALIDATE_EMAIL)) {
                    error_log('^^^^ URL');
                    $this->owner->NumberOfEmails++;
                }
            }

            // flag value is no links
            $this->owner->WordsPerLink = -1;
            if ($this->owner->NumberOfLinks > 0) {
                $this->owner->WordsPerLink = $this->owner->NumberOfWords / $this->owner->NumberOfLinks;
            }
        }

        $this->owner->Processed = 1;
    }

}
