<?php

class CommentAdminSpamExtension extends Extension {
    public function updateEditForm(&$form) {
        $tabs = $form->Fields()->findOrMakeTab('Root');
        $fields = $tabs->fieldList();
        foreach ($fields as $field) {
            error_log($field->getName());
        }

        // Comments with the most number of links
        $commentsWithLinks = Comment::get()->filter('Moderated', 0)
                                            ->where('NumberOfLinks > 0')
                                            ->sort('NumberOfLinks DESC');
        $linksGrid = new CommentsGridField(
            'LinkComments',
            _t('CommentsAdmin.LinkComments', 'Lots of Links'),
            $commentsWithLinks,
            CommentsGridFieldConfig::create()
        );
        $linksCount = '(' . count($commentsWithLinks) . ')';
        $linksTab = new Tab('WOOOOO', _t('CommentAdmin.LotsOfLinks', 'Many Links') . ' ' . $linksCount,
                    $linksGrid
        );
        $linksTab->setForm($form);
        $tabs->push($linksTab);

        // Comments with a high links to words ratio
        $wordsPerLink = Comment::get()->filter('Moderated', 0)
                                            ->where('WordsPerLink > 0')
                                            ->sort('WordsPerLink ASC');
        $wplGrid = new CommentsGridField(
            'WordsPerLink',
            _t('CommentsAdmin.WordsPerLink', 'Links Per Word High'),
            $commentsWithLinks,
            CommentsGridFieldConfig::create()
        );
        $wplCount = '(' . count($commentsWithLinks) . ')';
        $wplCount = '(' . count($commentsWithLinks) . ')';
        $wplTab = new Tab('WOOOOO2', _t('CommentAdmin.WordsPerLink', 'Link %') . ' ' . $linksCount,
                    $wplGrid
        );
        $wplTab->setForm($form);
        $tabs->push($wplTab);
    }
}
