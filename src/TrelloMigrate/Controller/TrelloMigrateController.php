<?php

namespace Kanboard\Plugin\TrelloMigrate\Controller;

use Kanboard\Controller\BaseController;
use Kanboard\Filter\TaskAssigneeFilter;
use Kanboard\Filter\TaskProjectFilter;
use Kanboard\Filter\TaskStatusFilter;
use Kanboard\Model\TaskModel;

/**
 * TrelloMigrate Controller
 *
 * @package  Kanboard\Controller
 * @author   Frederic Guillot
 * @author   Timo Litzbarski
 */
class TrelloMigrateController extends BaseController
{
    /**
     * Show TrelloMigrate view for a user
     *
     * @access public
     */
    public function user()
    {
        $user = $this->getUser();

        $this->response->html($this->helper->layout->app('TrelloMigrate:TrelloMigrate/user', array(
            'user' => $user,
        )));
    }

    /**
     * Show TrelloMigrate view for a project
     *
     * @access public
     */
    public function project()
    {
        $project = $this->getProject();

        $this->response->html($this->helper->layout->app('TrelloMigrate:TrelloMigrate/project', array(
            'project'     => $project,
            'title'       => $project['name'],
            'description' => $this->helper->projectHeader->getDescription($project),
        )));
    }

    /**
     * Get tasks to display on the TrelloMigrate (project view)
     *
     * @access public
     */
    public function projectEvents()
    {
        $project_id = $this->request->getIntegerParam('project_id');
        $start = $this->request->getStringParam('start');
        $end = $this->request->getStringParam('end');
        $search = $this->userSession->getFilters($project_id);
        $queryBuilder = $this->taskLexer->build($search)->withFilter(new TaskProjectFilter($project_id));

        $events = $this->helper->TrelloMigrate->getTaskDateDueEvents(clone($queryBuilder), $start, $end);
        $events = array_merge($events, $this->helper->TrelloMigrate->getTaskEvents(clone($queryBuilder), $start, $end));

        $events = $this->hook->merge('controller:TrelloMigrate:project:events', $events, array(
            'project_id' => $project_id,
            'start' => $start,
            'end' => $end,
        ));

        $this->response->json($events);
    }

    /**
     * Get tasks to display on the TrelloMigrate (user view)
     *
     * @access public
     */
    public function userEvents()
    {
        $user_id = $this->request->getIntegerParam('user_id');
        $start = $this->request->getStringParam('start');
        $end = $this->request->getStringParam('end');
        $queryBuilder = $this->taskQuery
            ->withFilter(new TaskAssigneeFilter($user_id))
            ->withFilter(new TaskStatusFilter(TaskModel::STATUS_OPEN));

        $events = $this->helper->TrelloMigrate->getTaskDateDueEvents(clone($queryBuilder), $start, $end);
        $events = array_merge($events, $this->helper->TrelloMigrate->getTaskEvents(clone($queryBuilder), $start, $end));

        if ($this->configModel->get('TrelloMigrate_user_subtasks_time_tracking') == 1) {
            $events = array_merge($events, $this->helper->TrelloMigrate->getSubtaskTimeTrackingEvents($user_id, $start, $end));
        }

        $events = $this->hook->merge('controller:TrelloMigrate:user:events', $events, array(
            'user_id' => $user_id,
            'start' => $start,
            'end' => $end,
        ));

        $this->response->json($events);
    }

    /**
     * Update task due date
     *
     * @access public
     */
    public function save()
    {
        if ($this->request->isAjax() && $this->request->isPost()) {
            $values = $this->request->getJson();

            $this->taskModificationModel->update(array(
                'id' => $values['task_id'],
                'date_due' => substr($values['date_due'], 0, 10),
            ));
        }
    }
}
