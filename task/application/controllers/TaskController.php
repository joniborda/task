<?php
class TaskController extends Zend_Controller_Action
{
	public function addAction()
	{
		$this->_helper->layout->setLayout('empty');
		$this->view->assign('response', array('response' => false));
		
		$users = $this->getRequest()->getParam('users');
		$user_ids = array();
		$usuario_service = Application_Service_Locator::getUsuarioService();
		
		if (empty($users)) {
			$session_user = Application_Service_Locator::getSessionService()->getUser();
			if ($session_user) {
				$users = array($session_user->getName());
				$user_ids = array($session_user->getId());
			} else {
				$this->view->assign('response', 
					array(
						'response' => false,
						'message' => 'No se logueó'
					)
				);
			}
		} else {
			foreach ($users as $name) {
				
				$user = $usuario_service->getByName($name);
				if ($user) {
					$user_ids[] = $user->getId();
				}
			}
		}
		
		if (
				($title = $this->getRequest()->getParam('title')) &&
				($project_id = $this->getRequest()->getParam('project_id'))
		) {
			
			if (
					($task = Application_Service_Locator::getTaskService()->crear($title,$project_id, $user_ids))
			) {
				
				$this->view->assign('response', array(
					'response' => true,
					'id' => $task->getId(),
					'users' => $users,
					'status' => $task->getStatusId()
				));
			}
		}
	}
	
	public function listAction()
	{
		$this->_helper->layout->setLayout('empty');
		$this->view->assign('response', array('response' => false));
		$status_id = $this->getRequest()->getParam('status_id', null);
		$user_id = $this->getRequest()->getParam('user_id', null);
		$project_id = $this->getRequest()->getParam('project_id', null);
		
		$ret = array();
		if (
			($tasks = Application_Service_Locator::getTaskService()->getAllByFilters($project_id, $status_id, $user_id))
		) {
			$project = Application_Service_Locator::getProjectService()->getById($project_id);
			
			if ($project) {
				Application_Service_Session::set(
					'redirect',  
					$this->view->baseUrl . '#' . $project->getName());
			}
			
			$ret = array();
			foreach ($tasks as $task) {
				$ret[] = array(
					'id' => $task->getId(),
					'title' => $task->getTitle(),
					'users' => $task->getUsers(),
					'status' => $task->getStatusId(),
					'created' => $task->getCreated()
				);
			}
		}
		
		$this->view->assign('response', array(
				'response' => true,
				'tasks' => $ret,
				'status_id' => $status_id
		));
	}

	public function changestatusAction()
	{
		$this->_helper->layout->setLayout('empty');
		$this->view->assign('response', array('response' => false));
		
		$id = $this->_request->getParam('id');
		$descripcion = $this->_request->getParam('status');
		
		$status = Application_Service_Locator::getStatusService()->getByDescripcion($descripcion);
		
		
		$userSession = Application_Service_Locator::getSessionService()->getUser();
		if ($userSession) {
			
			if ($status) {
				$data = array(
					'id' => $id,
					'status_id' => $status->getId(),
					'user_id' => $userSession->getId()
				);
				
				$ret = Application_Service_Locator::getTaskService()->updateById($id, $data);
				if ($ret) {
					$this->view->assign('response', array('response' => true));
				}
			}
		}
	}
	
	public function viewAction()
	{
		$this->_helper->layout->setLayout('empty');
		
		$id = $this->_request->getParam('id');
		
		if ($id != null) {
			$task_service = Application_Service_Locator::getTaskService();
			
			$task = $task_service->getById($id);
			
			if ($task != null) {
				$this->view->assign('task', $task);
				$changesTask = Application_Service_Locator::getChangeTaskService()->getAllByTask($task->getId());
				$this->view->assign('changes' , $changesTask);
			}
		}
	}
	
	public function removeAction()
	{
		$this->_helper->layout->setLayout('empty');
		$id = $this->_request->getParam('id');
		
		$this->view->assign('deleted', false);
		
		if ($id != null) {
			$task_service = Application_Service_Locator::getTaskService();
			
			$ret = $task_service->deleteById($id);
			$this->view->assign('deleted', $ret);
		}
	}
	
	public function editAction()
	{
		$this->_helper->layout->setLayout('empty');
		$id = $this->_request->getParam('id');
		$title = $this->_request->getParam('title');
		
		$this->view->assign('response', false);
		
		if ($id != null) {
			$task_service = Application_Service_Locator::getTaskService();
			
			$ret = $task_service->updateById($id, array('title' => $title));
			
			if ($ret > 0) {
				$this->view->assign('response', true);
			}
		}
	}
	
	public function asignAction()
	{
		$this->_helper->layout->setLayout('empty');
		$id = $this->_request->getParam('id');
		$name = $this->_request->getParam('name');
		
		$this->view->assign('response', false);
		
		if ($id != null) {			
			$user = Application_Service_Locator::getUsuarioService()->getByName($name);
			
			if ($user) {
				$ret = Application_Service_Locator::getUserTaskService()->crear($id, $user->getId());
				
				if ($ret) {
					$this->view->assign('response', true);
				}
			}
		}
	}
	
	public function removeasignAction()
	{
		$this->_helper->layout->setLayout('empty');
		$id = $this->_request->getParam('id');
		$name = $this->_request->getParam('name');
		
		$this->view->assign('response', false);
		
		if ($id != null) {
			$user = Application_Service_Locator::getUsuarioService()->getByName($name);
				
			if ($user) {
				$ret = Application_Service_Locator::getUserTaskService()->remove($id, $user->getId());
		
				if ($ret > 0) {
					$this->view->assign('response', true);
				}
			}
		}
	}
	
	/**
	 * Search task by filters
	 */
	public function searchAction()
	{
		$this->_helper->layout->setLayout('empty');
		$this->view->assign('response', array('response' => false));
		
		$status_id = $this->getRequest()->getParam('status_id', null);
		$user_id = $this->getRequest()->getParam('user_id', 8);
		$project_id = $this->getRequest()->getParam('id', null);
		
		$tasks = Application_Service_Locator::getTaskService()->getAllByFilters($project_id, $status_id, $user_id);
		
		if (!empty($tasks)) {
			$ret = array();
			foreach ($tasks as $task) {
				$ret[] = array(
						'id' => $task->getId(),
						'title' => $task->getTitle(),
						'users' => $task->getUsers(),
						'status' => $task->getStatusId()
				);
			}
			$this->view->assign('response', array(
					'response' => true,
					'tasks' => $ret
			));
		}
	}
}