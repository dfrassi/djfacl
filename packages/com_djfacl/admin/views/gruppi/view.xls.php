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

class gruppiViewgruppi extends JView
{
	/**
	 * Custom Constructor
	 */
	function __construct( $config = array())
	{

		global $context;

	 parent::__construct( $config );
	}

	
	function display($tpl = null)
	{
		$option = JRequest::getCmd('option'); $mainframe =& JFactory::getApplication(); $context = JRequest::getCmd('context');

		//DEVNOTE: set document title
		$document = & JFactory::getDocument();
		$document->setTitle( JText::_('gruppi') );
		$document->setMimeEncoding('application/vnd.ms-excel');		
			
		//DEVNOTE: Set ToolBar title
		JToolBarHelper::title(   JText::_( 'djfacl - Gestione gruppi' ), 'generic.png' );
				
		//DEVNOTE:Get data from the model
		$items = utility::getArray("SELECT h.* from #__core_acl_aro_groups as h");

		//DEVNOTE:save a reference into view
		$this->assignRef('items',		$items);

		//DEVNOTE:call parent display
		parent::display("xls");
	}
}
?>
