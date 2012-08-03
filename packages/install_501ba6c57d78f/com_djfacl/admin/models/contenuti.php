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

class contenutiModelcontenuti extends JModel {
	
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
	 * Method to get a contenuti data
	 *
	 * questo metodo è chiamato da ogni proprietario della vista
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
			//$this->_total = $this->_getListCount ( $query );
		}
		return $this->_total;
	}
	
	/**
	 * Method to get a pagination object for the contenuti
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
		
		
		$extension = JRequest::getvar('extension');
		$extensionid = utility::getExtensionId($extension);
		if ($extensionid!="")
		$query_components = " and h.id_components = ".$extensionid;
		else $query_components="";
		$post = JRequest::get ( 'post' );
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		$post ['id'] = $cid [0];
		$search = JRequest::getVar ( 'search' );
		if (isset ( $post ['search'] ))
			$search = $post ['search']; // se c'è un parametro search settato.
		$grupposearch = utility::getDjfVar('idgroup','0' );
		
		if ($search != "")
			$query_search = " 
		and (
		modu.title like '%" . $search . "%'
		or g.title like '%" . $search . "%'
		or h.jtask like '%" . $search . "%'
		or h.css_block like '%" . $search . "%'
		) 		
		";
		else
			$query_search = "";
		
		if ($grupposearch != "") {
			if ($grupposearch=="0")$query_gruppo = ""; else
			$query_gruppo = " and (g.id = '" . $grupposearch . "')";
		} else
			$query_gruppo = "";
		
		
		$query = 'select h.*, g.title as gruppo, case (h.id_modules)
		WHEN "999999" THEN "Tutti"
		WHEN "0" THEN "Nessuno"
		else modu.title END as modulo 
		from #__djfacl_contenuti as h 
			left join #__modules as modu on (modu.id = h.id_modules)
			left join #__usergroups as g on (g.id = h.id_group) where h.id=h.id'.$query_search.$query_gruppo.$orderby;
		//echo ($query);
		

		//exit();
		

		return $query;
	}
	/**
	 * Costruisce l'order by automatico su colonna
	 */
	
	function _buildContentOrderBy() {
		$option = JRequest::getCmd('option'); $mainframe =& JFactory::getApplication(); $context = JRequest::getCmd('context');
		
		$filter_order = $mainframe->getUserStateFromRequest ( $context . 'filter_order', 'filter_order', 'g.title' );
		$filter_order_Dir = $mainframe->getUserStateFromRequest ( $context . 'filter_order_Dir', 'filter_order_Dir', '' );
		
		if ($filter_order == 'ordering') {
			$orderby = ' ORDER BY g.title ';
		} else {
			$orderby = ' ORDER BY ' . $filter_order . ' ' . $filter_order_Dir;
		}
		return $orderby;
	}
	
	function getComponents($order = '`option`') {
		global $mainframe;
		$query = 'SELECT id AS value, `option` AS text' . ' FROM #__djfacl_components where parent=0 ORDER BY ' . $order;
		$this->_db->setQuery ( $query );
		
		return $this->_db->loadObjectList ();
	}
	
	function getGruppi($order = 'name') {
		global $mainframe;
		$query = 'SELECT id AS value, name AS text FROM #__core_acl_aro_groups ORDER BY ' . $order;
		//echo $query;
		$this->_db->setQuery ( $query );
		return $this->_db->loadObjectList ();
	}
	function delete()
	{
		$cids = JRequest::getVar('cid', array(0), 'post', 'array');
		$row = $this->getTable('contenuti_detail');
	
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

