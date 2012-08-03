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
defined( '_JEXEC' ) or die( 'Restricted access' );

//DEVNOTE: import VIEW object class
jimport( 'joomla.application.component.view' );
jimport('joomla.application.component.helper');
require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'toolbar.php' );

/**
 [controller]View[controller]
 */

class contenutiViewcontenuti extends JView
{

	protected $state;
	
	function __construct( $config = array()){
	 
		global $context;
	 	$context = 'contenuti.list.';
	 	parent::__construct( $config );
		
	}




	function display($tpl = null)
	{
		$option = JRequest::getCmd('option'); 
		$mainframe =& JFactory::getApplication(); 
		$context = JRequest::getCmd('context');
		
		$this->state		= $this->get('State');
	
		$document = & JFactory::getDocument();
		$document->setTitle( JText::_('contenuti') );
		$document->addStyleSheet ( 'components/com_djfacl/assets/css/icon.css' );
		$document->addStyleSheet ( 'components/com_djfacl/assets/css/general.css' );
		$document->addStyleSheet ( 'components/com_djfacl/assets/css/modal.css' );
		$document->addStyleSheet ( 'components/com_djfacl/assets/css/menu.css' );

		JToolBarHelper::title ( JText::_ ( 'Djf Acl - ').JText::_ ( 'GESTIONE_CONTENUTI' ), 'content' );
		JToolBarHelper::addNewX();
		JToolBarHelper::editListX();
		JToolBarHelper::deleteList();
		
		$uri	=& JFactory::getURI();
		
		$filter_order     = $mainframe->getUserStateFromRequest( $context.'filter_order',      'filter_order', 	  'ordering' );
		$filter_order_Dir = $mainframe->getUserStateFromRequest( $context.'filter_order_Dir',  'filter_order_Dir', '' );
		
		$lists['order'] = $filter_order;
		$lists['order_Dir'] = $filter_order_Dir;		
		
		$grupposearch = utility::getDjfVar('idgroup','0' );
		
		$grupposearchcopia="";
		$select_gruppi = NULL;
		
		$items	= & $this->get( 'Data');
		$total	= & $this->get( 'Total');
		
		$pagination = & $this->get( 'Pagination' );
		
		$db = JFactory::getDbo();
		$query_gruppi_associati = 'SELECT a.id AS value, a.title AS text, COUNT(DISTINCT b.id) AS level' .
				' FROM #__usergroups AS a' .
				' LEFT JOIN '.$db->quoteName('#__usergroups').' AS b ON a.lft > b.lft AND a.rgt < b.rgt' .
				' GROUP BY a.id, a.title, a.lft, a.rgt' .
				' ORDER BY a.lft ASC';
		
		
		$select2 = utility::addItemToSelect(NULL,'TUTTI','0');
		//$select2 = utility::addItemToSelect($select2,'NESSUNO','0');
		
		$lists ['gruppi_associati'] = utility::getSelectGroups($query_gruppi_associati
				,'idgroup',$grupposearch,
				'onchange="document.adminForm.submit();"',$select2,'CREARE_UN_GRUPPO','index.php?option=com_djfacl&controller=gruppi');
		
		
		
		$lists ['gruppi_associati_copia'] = utility::getSelectGroups($query_gruppi_associati,'grupposearchcopia',$grupposearchcopia,  '',null,'CREARE_UN_GRUPPO','index.php?option=com_djfacl&controller=gruppi');

		$this->assignRef('user',		JFactory::getUser());
		$this->assignRef('lists',		$lists);
		$this->assignRef('items',		$items);
		$this->assignRef('pagination',	$pagination);
		$this->assignRef('request_url',	$uri->toString());	
			
		//$this->assignRef('grupposearch',$grupposearch);
		$this->assignRef('search', JRequest::getVar('search'));
		$this->assignRef('grupposearch', JRequest::getVar('grupposearch'));
		$this->assignRef('pulsanti', djfaclHelperToolbar::getToolbar());
		
		parent::display($tpl);
	}
	
	
	
	
}
?>
