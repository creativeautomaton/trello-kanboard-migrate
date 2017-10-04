<?= $this->projectHeader->render($project, 'TrelloMigrateController', 'project', false, 'TrelloMigrate') ?>

<?= $this->TrelloMigrate->render(
    $this->url->href('TrelloMigrateController', 'projectEvents', array('project_id' => $project['id'], 'plugin' => 'TrelloMigrate')),
    $this->url->href('TrelloMigrateController', 'save', array('project_id' => $project['id'], 'plugin' => 'TrelloMigrate'))
) ?>
