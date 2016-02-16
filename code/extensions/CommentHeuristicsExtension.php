<?php

class CommentHeuristicsExtension extends DataExtension {

	private static $db = array(
		'NumberOfLinks' => 'Int',
		'NumberOfWords' => 'Int',
		'Processed' => 'Boolean(0)'
	);

	public function onBeforeWrite() {
		$tokens = preg_split ('/[,\s]+/', $this->owner->Comment);
		$this->NumberOfWords = sizeof($tokens);
		foreach ($tokens as $token) {
			error_log('TOKEN: ' . $token);
		}
		$this->Processed = 1;
	}
}
