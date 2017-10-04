<?php

namespace Kanboard\Plugin\TrelloMigrate;

use Kanboard\Core\Plugin\Base;
use Kanboard\Core\Translator;
use Kanboard\Plugin\TrelloMigrate\Formatter\ProjectApiFormatter;
use Kanboard\Plugin\TrelloMigrate\Formatter\TaskTrelloMigrateFormatter;

class Plugin extends Base
{
    public function initialize()
    {
        $this->helper->register('TrelloMigrate', '\Kanboard\Plugin\TrelloMigrate\Helper\TrelloMigrateHelper');

        $this->container['taskTrelloMigrateFormatter'] = $this->container->factory(function ($c) {
            return new TaskTrelloMigrateFormatter($c);
        });

        $this->container['projectApiFormatter'] = $this->container->factory(function ($c) {
            return new ProjectApiFormatter($c);
        });

        $this->template->hook->attach('template:dashboard:page-header:menu', 'TrelloMigrate:dashboard/menu');
        $this->template->hook->attach('template:project:dropdown', 'TrelloMigrate:project/dropdown');
        $this->template->hook->attach('template:project-header:view-switcher', 'TrelloMigrate:project_header/views');
        $this->template->hook->attach('template:config:sidebar', 'TrelloMigrate:config/sidebar');

        $this->hook->on('template:layout:css', array('template' => 'plugins/TrelloMigrate/Assets/fullTrelloMigrate.min.css'));
        $this->hook->on('template:layout:js', array('template' => 'plugins/TrelloMigrate/Assets/moment.min.js'));
        $this->hook->on('template:layout:js', array('template' => 'plugins/TrelloMigrate/Assets/fullTrelloMigrate.min.js'));
        $this->hook->on('template:layout:js', array('template' => 'plugins/TrelloMigrate/Assets/locale-all.js'));
        $this->hook->on('template:layout:js', array('template' => 'plugins/TrelloMigrate/Assets/TrelloMigrate.js'));
    }

    public function onStartup()
    {
        Translator::load($this->languageModel->getCurrentLanguage(), __DIR__.'/Locale');
    }

    public function getPluginName()
    {
        return 'TrelloMigrate';
    }

    public function getPluginDescription()
    {
        return t('TrelloMigrate view for Kanboard');
    }

    public function getPluginAuthor()
    {
        return 'Creative Automaton';
    }

    public function getPluginVersion()
    {
        return '0.0.1 Beta';
    }

    public function getPluginHomepage()
    {
        return 'https://github.com/creativeautomaton/plugin-TrelloMigrate';
    }

    public function getCompatibleVersion()
    {
        return '>=0.0.1';
    }
}
