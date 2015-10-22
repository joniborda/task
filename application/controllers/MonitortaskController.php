<?php
class MonitortaskController extends Zend_Controller_Action {

	public function changesAction() {
		$this->_helper->layout->setLayout('empty');
		$change_task_service = Application_Service_Locator::getChangeTaskService();

		$change_tasks = $change_task_service->getUnrevised();

		if (!empty($change_tasks)) {
			$ret = array();
			foreach ($change_tasks as $change_task) {
				$task_new = $change_task->getTask();

				if ($task_new) {
					$text = $this->getTableHtml($task_new);

					$user = $change_task->getUser();

					if ($user) {
						$text .= '<br>La tarea fue modificada por: ' . $user->getName();
					}

					if ($change_task->getStatus()) {
						$text .= '<br>Se modificó el estado, antes estaba: "' . $change_task->getStatus() . '"';
					}
					if ($change_task->getTitle()) {
						$text .= '<br>Se modificó el nombre, antes era: "' . $change_task->getTitle() . '"';
					}

					$users = Application_Service_Locator::getUsuarioService()->fetchAll();

					foreach ($users as $user) {
						$validator = new Zend_Validate_EmailAddress();
						if ($validator->isValid($user->getMail())) {

							$mail = new Zend_Mail();
							$mail->addTo($user->getMail(), $user->getName());
							$project = $task_new->getProject();
							$project_name = '';
							if ($project) {
								$project_name = $project->getName();
							}

							$mail->setSubject($project_name . ' - Tarea Modificada #' . $change_task->getTaskId());
							$mail->setBodyHtml(utf8_decode($text));
							try {
								$mail->send();
							} catch (Zend_Mail_Transport_Exception $e) {
								// TODO: avisar que no se pudo enviar
							}
						}
					}
				}
				$change_task->setRevisado(true);

				$change_task_service->make_revised($change_task->getId());
			}
		}
	}

	private function getTableHtml($task_new) {
		$ret =
		'<h3>' .
		'<a href="' . $_SERVER['SERVER_NAME'] . '/task">#' . $task_new->getId() . '</a> ' .
		$task_new->getTitle() .
		'</h3>' .
		'<div>Estado: ' . $task_new->getStatus() . '</div>' .
		'<div>Asignado: ';

		foreach ($task_new->getUsers() as $user) {
			$ret .= ' ' . $user;
		}

		$ret .= '</div>';

		return $ret;
	}

	public function newtaskAction() {
		$this->_helper->layout->setLayout('empty');
		$task_service = Application_Service_Locator::getTaskService();

		$tasks = $task_service->getUnrevised();

		if (!empty($tasks)) {
			$ret = array();
			foreach ($tasks as $task) {

				$text = $this->getTableHtml($task);

				$user = $task->getUser();

				if ($user) {
					$text .= '<br>La tarea fue creada por: ' . $user->getName();
				}

				$users = Application_Service_Locator::getUsuarioService()->fetchAll();

				foreach ($users as $user) {
					$validator = new Zend_Validate_EmailAddress();
					if ($validator->isValid($user->getMail())) {

						$mail = new Zend_Mail();
						$mail->addTo($user->getMail(), $user->getName());

						$project = $task->getProject();
						$project_name = '';
						if ($project) {
							$project_name = $project->getName();
						}

						$mail->setSubject($project_name . ' - Nueva tarea #' . $task->getId());
						$mail->setBodyHtml(utf8_decode($text));
						try {
							$mail->send();
						} catch (Zend_Mail_Transport_Exception $e) {
							// TODO: avisar que no se pudo enviar
						}
					}
				}
				$task->setRevisado(true);

				$task_service->make_revised($task->getId());
			}
		}
	}

	public function svnAction() {
		svn_auth_set_parameter(SVN_AUTH_PARAM_DEFAULT_USERNAME, 'jborda');
		svn_auth_set_parameter(SVN_AUTH_PARAM_DEFAULT_PASSWORD, 'Q1w2e3r4');

		$revision_service = Application_Service_Locator::getRevisionService();
		$last_revision = $revision_service->getLastId();

		$revisions = svn_log('https://subversion.assembla.com/svn/taskmonitor/trunk/', SVN_REVISION_HEAD, ($last_revision + 1), null);

		if (!empty($revisions)) {

			$revisions = array_reverse($revisions);
			$users = Application_Service_Locator::getUsuarioService()->fetchAll();

			foreach ($revisions as $revision) {
				$html = 'Revision ' . $revision['rev'] . '<br>';
				$html .= '<br>Author: ' . $revision['author'];

				foreach ($revision['paths'] as $paths) {

					$string_old = svn_cat('https://subversion.assembla.com/svn/taskmonitor/' . $paths['path'], 30);
					$string_new = svn_cat('https://subversion.assembla.com/svn/taskmonitor/' . $paths['path'], 31);

					// lo que habia
					$diff_old = array_diff(
						preg_split("/\\r\\n|\\r|\\n/", $string_old),
						preg_split("/\\r\\n|\\r|\\n/", $string_new)
					);

					// lo que se cambio como nuevo y lo que se agregó
					$diff_new = array_diff(
						preg_split("/\\r\\n|\\r|\\n/", $string_new),
						preg_split("/\\r\\n|\\r|\\n/", $string_old)
					);

					$keys_unique = array_unique(array_merge(array_keys($diff_new), array_keys($diff_old)), SORT_NUMERIC);
					sort($keys_unique);

					$diff = '';
					foreach ($keys_unique as $key) {
						if (isset($diff_old[$key])) {
							$diff .= '<br>[' . $key . ']---' . htmlentities($diff_old[$key]);
						}
						if (isset($diff_new[$key])) {
							$diff .= '<br>[' . $key . ']+++' . htmlentities($diff_new[$key]);
						}
					}

					$html .= '<br>Path: ' . $paths['path'];
					$html .= $diff . '<br><br>';

				}

				foreach ($users as $user) {
					$validator = new Zend_Validate_EmailAddress();
					if ($validator->isValid($user->getMail())) {

						$mail = new Zend_Mail();
						$mail->addTo($user->getMail(), $user->getName());
						$mail->setSubject('COMMIT TASK');
						$mail->setBodyHtml(utf8_decode($html));
						try {
							$mail->send();
						} catch (Zend_Mail_Transport_Exception $e) {
							// TODO: avisar que no se pudo enviar
						}
					}
				}
				$revision_service->crear($revision['rev'], $revision['author'], $revision['msg'], $revision['date']);
			}
		}
		die();
	}
}