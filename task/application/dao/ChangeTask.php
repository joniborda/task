<?php
class Application_Dao_ChangeTask {
	protected $_dbTable;
	public function setDbTable($dbTable) {
		if (is_string ( $dbTable )) {
			$dbTable = new $dbTable ( array (
					'db' => 'localhost' 
			) );
		}
		if (! $dbTable instanceof Zend_Db_Table_Abstract) {
			throw new Exception ( 'Invalid table data gateway provided' );
		}
		$this->_dbTable = $dbTable;
		return $this;
	}
	
	/**
	 *
	 * @return Application_Model_DbTable_ChangeTask
	 */
	public function getDbTable() {
		if (null === $this->_dbTable) {
			$this->setDbTable ( 'Application_Model_DbTable_ChangeTask' );
		}
		return $this->_dbTable;
	}
	
	/**
	 * Consigue todos los ChangeTasks
	 *
	 * @return array
	 */
	public function fetchAll() {
		$resultSet = $this->getDbTable ()->fetchAll ( null, array (
				'id desc' 
		), null, null );
		$entries = array ();
		foreach ( $resultSet as $row ) {
			$entry = new Application_Model_ChangeTask ( $row );
			$entries [] = $entry;
		}
		return $entries;
	}
	
	/**
	 * Consigue por id.
	 *
	 * @param Integer $id
	 *        	Id del ChangeTask.
	 *        	
	 * @return Application_Model_ChangeTask
	 */
	public function getById($id) {
		$resultSet = $this->getDbTable ()->find ( ( int ) $id )->current ();
		
		if ($resultSet != null) {
			return new Application_Model_ChangeTask ( $resultSet );
		}
		
		return null;
	}
	
	/**
	 * Crea un ChangeTask
	 *
	 * @param String $title        	
	 * @param String $project_id        	
	 * @param Integer $user_id        	
	 *
	 * @return Application_Model_ChangeTask
	 */
	public function crear($data) {
		$data ['id'] = $this->getDbTable ()->insert ( $data );
		
		return new Application_Model_ChangeTask ( $data );
	}
	
	/**
	 * Set status
	 *
	 * @param Integer $id        	
	 * @param Array $data        	
	 *
	 * @return Integer The number of rows updated
	 */
	public function updateById($id, $data) {
		if (is_array ( $data )) {
			
			return $this->getDbTable ()->update ( $data, array (
					'id = ?' => $id 
			) );
		}
	}
	
	/**
	 * Delete by id
	 *
	 * @param Integer $id        	
	 *
	 * @return Boolean
	 */
	public function deleteById($id) {
		if ($id != null) {
			
			if ($this->getDbTable ()->delete ( array (
					'id = ?' => $id 
			) )) {
				return true;
			}
		}
		return false;
	}
	
	/**
	 * Delete by id
	 *
	 * @param Integer $id        	
	 *
	 * @return Boolean
	 */
	public function deleteByProjectId($id) {
		if ($id != null) {
			
			if ($this->getDbTable ()->delete ( array (
					'projects_id = ?' => $id 
			) )) {
				return true;
			}
		}
		return false;
	}
	
	/**
	 * Consigue todos los ChangeTasks de una tarea
	 *
	 * @return array
	 */
	public function getAllByTask($task_id) {
		if ($task_id) {
			$resultSet = $this->getDbTable ()->fetchAll ( array (
					'task_id = ?' => $task_id 
			), array (
					'id desc' 
			), null, null );
			$entries = array ();
			foreach ( $resultSet as $row ) {
				$entry = new Application_Model_ChangeTask ( $row );
				$entries [] = $entry;
			}
			return $entries;
		}
	}
	
	/**
	 * Get Last Modified by User
	 *
	 * @param string $user_id        	
	 *
	 * @return Date
	 */
	public function getLastModifiedByUser($user_id = null) {
		$where = array ();
		if ($user_id) {
			$where = array (
					'user_id = ?' => $user_id 
			);
		}
		
		$select = $this->getDbTable ()->select ()->from ( $this->getDbTable (), 'max(created)' );
		
		foreach ( $where as $key => $value ) {
			$select = $select->where ( $key, $value );
		}
		
		if ($data = $this->getDbTable ()->fetchRow ( $select )) {
			
			if (isset($data->max)) {
				return $data->max;
			}
		}
	}
}
?>