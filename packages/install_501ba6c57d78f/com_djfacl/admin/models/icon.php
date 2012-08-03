<?php
/**
 * @package HelloWorld
 * @version 1.5
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * Joomla! is free software and parts of it may contain or be derived from the
 * GNU General Public License or other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// no direct access
defined ( '_JEXEC' ) or die ( 'Restricted access' );
jimport ( 'joomla.application.component.model' );

class iconModelicon extends JModel {
	
	var $_data = null;
	var $_total = null;
	var $_pagination = null;
	var $_table_prefix = null;
	var $_query = null;
	
	function __construct() {
		parent::__construct ();
		$option = JRequest::getCmd('option'); $mainframe =& JFactory::getApplication(); $context = JRequest::getCmd('context');
		$this->_table_prefix = '#__djfacl_';
		
		//DEVNOTE: Parametri di paginazione
		$limit = $mainframe->getUserStateFromRequest ( $context . 'limit', 'limit', $mainframe->getCfg ( 'list_limit' ), 0 );
		$limitstart = $mainframe->getUserStateFromRequest ( $context . 'limitstart', 'limitstart', 0 );
		$this->setState ( 'limit', $limit );
		$this->setState ( 'limitstart', $limitstart );
	}
	
	/**
	 * Method to get a icon data
	 *
	 * questo metodo � chiamato da ogni proprietario della vista
	 */
	
	function getData() {
		if (empty ( $this->_data )) {
			$query = $this->_buildQuery ();
			$this->_query = $query;
			$this->_data = $this->_getList ( $query, $this->getState ( 'limitstart' ), $this->getState ( 'limit' ) );
		}
		return $this->_data;
	}
	
	/**
	 * Il metodo restituisce il numero totale di righe del componente
	 */
	
	function getTotal() {
		if (empty ( $this->_total )) {
			//$query = $this->_buildQuery ();
			$query = $this->_query;
			$this->_total = $this->_getListCount ( $query );
		}
		return $this->_total;
	}
	
	/**
	 * Method to get a pagination object for the icon
	 */
	
	function getPagination() {
		if (empty ( $this->_pagination )) {
			jimport ( 'joomla.html.pagination' );
			$this->_pagination = new JPagination ( $this->getTotal (), $this->getState ( 'limitstart' ), $this->getState ( 'limit' ) );
		}
		return $this->_pagination;
	}
	
	/**
	 * Metodo che effettua la query vera e propria sul db
	 */
	
	function _buildQuery() {
		
		$orderby = $this->_buildContentOrderBy (); // costruisce l'order by (vedi sotto)
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		$post ['id'] = $cid [0];
		$search = utility::getDjfVar('search','');		
		if ($search!="") $query_search = " and (h.text like '%" . $search . "%' or h.target like '%" . $search . "%')"; else $query_search=""; 
		$query = ' SELECT 
		h.id, 
		h.text, h.target, h.icon
		 FROM
		' . $this->_table_prefix . 'quickicon as h
		where h.id=h.id  ' . $query_search . $orderby;
		//echo ($query);
		
		//exit();
		

		return $query;
	}
	/**
	 * Costruisce l'order by automatico su colonna
	 */
	
	function _buildContentOrderBy() {
		$option = JRequest::getCmd('option'); $mainframe =& JFactory::getApplication(); $context = JRequest::getCmd('context');
		
		$filter_order = $mainframe->getUserStateFromRequest ( $context . 'filter_order', 'filter_order', '1' );
		$filter_order_Dir = $mainframe->getUserStateFromRequest ( $context . 'filter_order_Dir', 'filter_order_Dir', '' );
		
		if ($filter_order == 'ordering') {
			$orderby = ' ORDER BY 1 ';
		} else {
			$orderby = ' ORDER BY ' . $filter_order . ' ' . $filter_order_Dir ;
		}
		return $orderby;
	}
	

	function delete()
	{
		$cids = JRequest::getVar('cid', array(0), 'post', 'array');
		$row = $this->getTable('icon_detail');
	
		foreach($cids as $cid)
		{
			if(!$row->delete($cid))
			{
				$this->setError($row->_db->getErrorMsg());
	
				return false;
			}
		}
	
		return true;
	}
	
	

}

?>

