<?php

class IndexController extends Zend_Controller_Action {

	public function init() {
		/* Initialize action controller here */
	}

	public function indexAction() {
		$this->view
		     ->headLink()
		     ->appendStylesheet($this->view->pathCss . '/tooltipster/tooltipster.css')
		     ->appendStylesheet($this->view->pathCss . '/tooltipster/themes/tooltipster-shadow.css')
		     ->appendStylesheet($this->view->pathCss . '/index/index.css');
		$this->view->deny = $this->getRequest()->getParam('deny');

		if (!file_exists(Application_Config_Application::getProfilePath() . DIRECTORY_SEPARATOR .
			Application_Service_Session::getUser()->getId() . DIRECTORY_SEPARATOR .
			'./profile.png')) {
			$this->_redirect('usuario/generate_image');
		}

		$this->view->assign(
			'projects',
			Application_Service_Locator::getProjectService()->fetchAll()
		);

		$this->view->assign(
			'users',
			Application_Service_Locator::getUsuarioService()->fetchAll()
		);

		$status_all = Application_Service_Locator::getStatusService()->fetchAll();
		$status_list = array();
		foreach ($status_all as $status) {
			$status_list[$status->getId()] = $status->getDescripcion();
		}

		$this->view->assign(
			'status',
			$status_list
		);

		$this->view->headScript()
		     ->appendFile($this->view->webApp . '/js/tooltipster/jquery.tooltipster.js')
		     ->appendFile($this->view->webApp . '/js/index/index.js')
		     ->appendFile($this->view->webApp . '/js/index/socket.js');
	}

	public function menurightAction() {
		$this->_helper->layout->setLayout('empty');
	}
}
?>