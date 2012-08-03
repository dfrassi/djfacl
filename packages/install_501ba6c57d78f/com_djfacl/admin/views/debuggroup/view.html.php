<?php
/**
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');
jimport('joomla.application.component.modellist');


/**
 * View class for a list of users.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_users
 * @since		1.6
 */
class UsersViewDebugGroup extends JView
{
	protected $actions;
	protected $items;
	protected $pagination;
	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$document = & JFactory::getDocument();
		
		$this->actions		= $this->get('DebugActions');
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');
		$this->levels		= UsersHelperDebug::getLevelsOptions();
		$this->components	= UsersHelperDebug::getComponents();
		$this->groups	= UsersHelperDebug::getGroups();
		$this->group_id = $this->get('GroupId');
		$this->component_id = $this->get('ComponentId');
		//echo("<h1>component_id = ".$this->component_id."</h1>");
		//echo("<h1>group_id = ".$this->group_id."</h1>");
		$this->rule =  JAccess::getAssetRules($this->group_id,true);
		//echo($this->rule);
		//$esito = JAccess::checkGroup($this->group_id, "core.admin", $asset = "com_content");
		//echo("<h1>esito = ".$esito."</h1>");
		$document->addStyleSheet ( 'components/com_djfacl/assets/css/icon.css' );
		JToolBarHelper::title ( JText::_ ( 'Djf Acl - ').JText::_ ( 'GESTIONE_CONTENUTI_JOOMLA' ), 'jcontent' );
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$this->addToolbar();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addToolbar()
	{
		//JToolBarHelper::title(JText::sprintf('COM_USERS_VIEW_DEBUG_GROUP_TITLE', $this->group->id, $this->group->title), 'groups');

		JToolBarHelper::help('JHELP_USERS_DEBUG_GROUPS');
	}
}
