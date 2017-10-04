<?php

namespace Kanboard\Plugin\TrelloMigrate\Formatter;

use Kanboard\Core\Filter\FormatterInterface;

/**
 * TrelloMigrate event formatter for task filter
 *
 * @package  formatter
 * @author   Frederic Guillot
 */
class TaskTrelloMigrateFormatter extends BaseTaskTrelloMigrateFormatter implements FormatterInterface
{
    /**
     * Full day event flag
     *
     * @access private
     * @var boolean
     */
    private $fullDay = false;

    /**
     * When called TrelloMigrate events will be full day
     *
     * @access public
     * @return FormatterInterface
     */
    public function setFullDay()
    {
        $this->fullDay = true;
        return $this;
    }

    /**
     * Transform tasks to TrelloMigrate events
     *
     * @access public
     * @return array
     */
    public function format()
    {
        $events = array();

        foreach ($this->query->findAll() as $task) {
            $events[] = array(
                'timezoneParam' => $this->timezoneModel->getCurrentTimezone(),
                'id' => $task['id'],
                'title' => t('#%d', $task['id']).' '.$task['title'],
                'backgroundColor' => $this->colorModel->getBackgroundColor($task['color_id']),
                'borderColor' => $this->colorModel->getBorderColor($task['color_id']),
                'textColor' => 'black',
                'url' => $this->helper->url->to('TaskViewController', 'show', array('task_id' => $task['id'], 'project_id' => $task['project_id'])),
                'start' => date($this->getDateTimeFormat(), $task[$this->startColumn]),
                'end' => date($this->getDateTimeFormat(), $task[$this->endColumn] ?: time()),
                'editable' => $this->fullDay,
                'allday' => $this->fullDay,
            );
        }

        return $events;
    }

    /**
     * Get DateTime format for event
     *
     * @access private
     * @return string
     */
    private function getDateTimeFormat()
    {
        return $this->fullDay ? 'Y-m-d' : 'Y-m-d\TH:i:s';
    }
}
