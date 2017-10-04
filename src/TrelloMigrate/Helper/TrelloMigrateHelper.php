<?php

namespace Kanboard\Plugin\TrelloMigrate\Helper;

use Kanboard\Core\Base;
use Kanboard\Core\Filter\QueryBuilder;
use Kanboard\Filter\TaskDueDateRangeFilter;

/**
 * TrelloMigrate Helper
 *
 * @package helper
 * @author  Frederic Guillot
 * @property \Kanboard\Plugin\TrelloMigrate\Formatter\TaskTrelloMigrateFormatter  $taskTrelloMigrateFormatter
 */
class TrelloMigrateHelper extends Base
{
    /**
     * Render TrelloMigrate component
     *
     * @param  string $checkUrl
     * @param  string $saveUrl
     * @return string
     */
    public function render($checkUrl, $saveUrl)
    {
        $params = array(
            'checkUrl' => $checkUrl,
            'saveUrl' => $saveUrl,
        );

        return '<div class="js-TrelloMigrate" data-params=\''.json_encode($params, JSON_HEX_APOS).'\'></div>';
    }

    /**
     * Get formatted TrelloMigrate task due events
     *
     * @access public
     * @param  QueryBuilder       $queryBuilder
     * @param  string             $start
     * @param  string             $end
     * @return array
     */
    public function getTaskDateDueEvents(QueryBuilder $queryBuilder, $start, $end)
    {
        $formatter = $this->taskTrelloMigrateFormatter;
        $formatter->setFullDay();
        $formatter->setColumns('date_due');

        return $queryBuilder
            ->withFilter(new TaskDueDateRangeFilter(array($start, $end)))
            ->format($formatter);
    }

    /**
     * Get formatted TrelloMigrate task events
     *
     * @access public
     * @param  QueryBuilder       $queryBuilder
     * @param  string             $start
     * @param  string             $end
     * @return array
     */
    public function getTaskEvents(QueryBuilder $queryBuilder, $start, $end)
    {
        $startColumn = $this->configModel->get('TrelloMigrate_project_tasks', 'date_started');

        $queryBuilder->getQuery()->addCondition($this->getTrelloMigrateCondition(
            $this->dateParser->getTimestampFromIsoFormat($start),
            $this->dateParser->getTimestampFromIsoFormat($end),
            $startColumn,
            'date_due'
        ));

        $formatter = $this->taskTrelloMigrateFormatter;
        $formatter->setColumns($startColumn, 'date_due');

        return $queryBuilder->format($formatter);
    }

    /**
     * Get formatted TrelloMigrate subtask time tracking events
     *
     * @access public
     * @param  integer $user_id
     * @param  string  $start
     * @param  string  $end
     * @return array
     */
    public function getSubtaskTimeTrackingEvents($user_id, $start, $end)
    {
        return $this->subtaskTimeTrackingTrelloMigrateFormatter
            ->withQuery($this->subtaskTimeTrackingModel->getUserQuery($user_id)
                ->addCondition($this->getTrelloMigrateCondition(
                    $this->dateParser->getTimestampFromIsoFormat($start),
                    $this->dateParser->getTimestampFromIsoFormat($end),
                    'start',
                    'end'
                ))
            )
            ->format();
    }

    /**
     * Build SQL condition for a given time range
     *
     * @access public
     * @param  string   $start_time     Start timestamp
     * @param  string   $end_time       End timestamp
     * @param  string   $start_column   Start column name
     * @param  string   $end_column     End column name
     * @return string
     */
    public function getTrelloMigrateCondition($start_time, $end_time, $start_column, $end_column)
    {
        $start_column = $this->db->escapeIdentifier($start_column);
        $end_column = $this->db->escapeIdentifier($end_column);

        $conditions = array(
            "($start_column >= '$start_time' AND $start_column <= '$end_time')",
            "($start_column <= '$start_time' AND $end_column >= '$start_time')",
            "($start_column <= '$start_time' AND ($end_column = '0' OR $end_column IS NULL))",
        );

        return $start_column.' IS NOT NULL AND '.$start_column.' > 0 AND ('.implode(' OR ', $conditions).')';
    }
}
