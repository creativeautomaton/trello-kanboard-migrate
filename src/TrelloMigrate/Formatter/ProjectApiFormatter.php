<?php

namespace Kanboard\Plugin\TrelloMigrate\Formatter;

/**
 * Class ProjectApiFormatter
 *
 * @package Kanboard\Plugin\TrelloMigrate\Formatter
 */
class ProjectApiFormatter extends \Kanboard\Formatter\ProjectApiFormatter
{
    public function format()
    {
        $project = parent::format();

        if (! empty($project)) {
            $project['url']['TrelloMigrate'] = $this->helper->url->to('TrelloMigrateController', 'project', array('project_id' => $project['id'], 'plugin' => 'TrelloMigrate'), '', true);
        }

        return $project;
    }
}
