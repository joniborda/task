<?php

class ProjectController extends Zend_Controller_Action
{

	public function init()
	{
		/* Initialize action controller here */
	}

	public function indexAction()
	{
		$this->view
			->headLink()
			->appendStylesheet($this->view->pathCss . '/project/index.css' );
		
		$this->view->appendFile($this->view->webApp . '/js/project/index.js');
		// action body
	}

	public function createAction()
	{
		$this->_helper->layout->setLayout('empty');
	}

	public function addAction()
	{
		$this->_helper->layout->setLayout('empty');
		$this->view->assign('response', array('response'=>false));
		if (
				($name = $this->getRequest()->getParam('name'))
		) {

			$site_url = $this->getRequest()->getParam('site_url', null);
			$description = $this->getRequest()->getParam('description', null);

			if (($project = Application_Service_Locator::getProjectService()->crear($name, $site_url, $description))) {
				$this->view->assign('response', array(
						'response' => true,
						'id' => $project->getId()
				));
			}
		}
	}

	public function editformAction()
	{
		$this->_helper->layout->setLayout('empty');
		$id = $this->_request->getParam('id');
		if ($id) {
			
			$project = Application_Service_Locator::getProjectService()->getById($id);
			if ($project) {
				$this->view->assign('project', $project);
				return;
			}
		}
		$this->view->assign('error', true);
	}
	public function editAction()
	{
		$this->_helper->layout->setLayout('empty');
		$id = $this->_request->getParam('id');
		$name = $this->_request->getParam('name');
		$site_url = $this->_request->getParam('site_url');
		$description = $this->_request->getParam('description');
		
		if ($id != null && $name != null) {
				
			Application_Service_Locator::getProjectService()->update($id, $name, $site_url, $description);
			$this->view->assign('error', false);
		}
		$this->view->assign('error', true);
	}
	
	public function removeAction()
	{
		$this->_helper->layout->setLayout('empty');
		$id = $this->_request->getParam('id');
		
		$this->view->assign('deleted', false);
		
		if ($id != null) {
			$project_service = Application_Service_Locator::getProjectService();
			
			$ret = $project_service->deleteById($id);
			$this->view->assign('deleted', $ret);
		}
	}
}