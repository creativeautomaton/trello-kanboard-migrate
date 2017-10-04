<?php

namespace Kanboard\Plugin\TrelloMigrate\Controller;

/**
 * Class ConfigController
 *
 * @package Kanboard\Plugin\TrelloMigrate\Controller
 */
class ConfigController extends \Kanboard\Controller\ConfigController
{
    public function show()
    {
        $this->response->html($this->helper->layout->config('TrelloMigrate:config/TrelloMigrate', array(
            'title' => t('Settings').' &gt; '.t('TrelloMigrate settings'),
        )));
    }

    public function save()
    {
        $values =  $this->request->getValues();
        $values += array('TrelloMigrate_user_subtasks_time_tracking' => 0);

        if ($this->configModel->save($values)) {
            $this->flash->success(t('Settings saved successfully.'));
        } else {
            $this->flash->failure(t('Unable to save your settings.'));
        }

        $this->response->redirect($this->helper->url->to('ConfigController', 'show', array('plugin' => 'TrelloMigrate')));
    }
}
