<li <?= $this->app->checkMenuSelection('TrelloMigrateController') ?>>
    <?= $this->url->icon('stack-overflow', t('TrelloMigrate'), 'TrelloMigrateController', 'project', array('project_id' => $project['id'], 'search' => $filters['search'], 'plugin' => 'TrelloMigrate'), false, 'view-TrelloMigrate', t('Keyboard shortcut: "%s"', 'v c')) ?>
</li>
