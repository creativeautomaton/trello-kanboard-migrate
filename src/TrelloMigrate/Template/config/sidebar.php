<li <?= $this->app->checkMenuSelection('ConfigController', 'show', 'TrelloMigrate') ?>>
    <?= $this->url->link(t('Trello Migrate'), 'ConfigController', 'show', array('plugin' => 'TrelloMigrate')) ?>
</li>
