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

//DEVNOTE: import VIEW object class
jimport ( 'joomla.application.component.view' );
jimport ( 'joomla.application.component.helper' );
require_once (JPATH_COMPONENT_ADMINISTRATOR . DS . 'helpers' . DS . 'toolbar.php');

class contenuti_detailVIEWcontenuti_detail extends JView {

	protected $state;

	/**
	 * Display the view
	 */

	function getExtensionId($extension){
		if ($extension != ""){
			$query = 'select id from #__djfacl_components where `option` = "'.$extension.'"';
			$risultati = utility::getQueryArray($query);
			if (sizeof($risultati)>0)
			foreach ($risultati as $risultato){
				return $risultato->id;
			}
		}else return "";
	}

	function display($tpl = null) {

		$option = JRequest::getCmd('option');
		$mainframe =& JFactory::getApplication();
		$document = & JFactory::getDocument ();
		
		//echo("<br>idgroup = $grupposearch");
		//echo("<br>catid = $catid");
		//echo("<br>extension = $extension");
		//exit();

		$document->setTitle ( JText::_ ( 'contenuti' ) );
		$document->addStyleSheet ( 'components/com_djfacl/assets/css/icon.css' );
		$document->addStyleSheet ( 'components/com_djfacl/assets/css/general.css' );
		$document->addStyleSheet ( 'components/com_djfacl/assets/css/modal.css' );
		$document->addStyleSheet ( 'components/com_djfacl/assets/css/menu.css' );
		$document->addScriptDeclaration ( "var imageFolder =  'components/com_djfacl/assets/images/'" ); // Path to images
		$document->addScript ( 'components/com_djfacl/assets/js/drag-drop-folder-tree.js' );
		$document->addStyleSheet ( 'components/com_djfacl/assets/css/drag-drop-folder-tree.css' );
		$document->addStyleSheet ( 'components/com_djfacl/assets/css/djfacl.css' );

		JToolBarHelper::title ( JText::_ ( 'Djf Acl - ').JText::_ ( 'GESTIONE_CONTENUTI_DETTAGLIO' ), 'content' );
		$uri = & JFactory::getURI ();
		$user = & JFactory::getUser ();
		$model = & $this->getModel ();
		$this->setLayout ( 'form' );
		$lists = array ();
		$detail = & $this->get ( 'data' );
		$isNew = ($detail->id < 1);
		$text = $isNew ? JText::_ ( 'NEW' ) : JText::_ ( 'EDIT' );
		JToolBarHelper::save ();
		if ($isNew) {
			JToolBarHelper::cancel ();
			$grupposearch = Jrequest::getVar('idgroup');
			$catid = Jrequest::getVar('catid');
			$this->catid = $catid;
			$extension = Jrequest::getVar('extension');
			
		} else {
			$grupposearch = $detail->id_group;
			JToolBarHelper::cancel ( 'cancel', 'Close' );
		}
		if (! $isNew) {
			$model->checkout ( $user->get ( 'id' ) );
		}

		$parent_id = JRequest::getVar ( 'id_parent' );
		jimport( 'joomla.user.authorization' );
		$select = utility::addItemToSelect(NULL,'TUTTI','999999');
		$select = utility::addItemToSelect($select,'NESSUNO','0');
		$queryPerCategorie = "select
		cat.id as value,
		concat(cat.extension,'-',cat.title) as text
		from #__categories as cat
		order by (trim(cat.extension),trim(cat.title))";

		$options = "";
		$lists ['cssblock_associati'] = utility::getSelectExt ( "SELECT id AS value, css_block AS text FROM #__djfacl_cssblock order by css_block asc, trim(css_block)", 'css_block', 'css_block', $detail->css_block, 'onChange="checkDisabled();"' );
		$lists ['jtask_associati'] = utility::getSelectExt ( "SELECT id AS value, concat(name,' - ',jtask) AS text FROM #__djfacl_jtask order by name asc,jtask asc, trim(jtask)", 'jtask', 'jtask', $detail->jtask, 'onChange="checkDisabled();"' );
		$select_gruppi = NULL;
		$db = JFactory::getDbo();
		$query_gruppi_associati = 'SELECT a.id AS value, a.title AS text, COUNT(DISTINCT b.id) AS level' .
				' FROM #__usergroups AS a' .
				' LEFT JOIN '.$db->quoteName('#__usergroups').' AS b ON a.lft > b.lft AND a.rgt < b.rgt' .
				' GROUP BY a.id, a.title, a.lft, a.rgt' .
				' ORDER BY a.lft ASC';

		//$select2 = utility::addItemToSelect(NULL,'TUTTI','999999');
		$lists ['gruppi_associati'] = utility::getSelectGroups($query_gruppi_associati
				,'idgroup',$grupposearch,'',$select_gruppi,'CREARE_UN_GRUPPO','index.php?option=com_djfacl&controller=gruppi');
		jimport ( 'joomla.filter.filteroutput' );
		JFilterOutput::objectHTMLSafe ( $detail, ENT_QUOTES, 'description' );
		$this->assignRef ( 'lists', $lists );
		$this->assignRef ( 'idArticolo', $idArticolo );
		$this->assignRef ( 'titoloArticolo', $titoloArticolo );
		$this->assignRef ( 'detail', $detail );
		$this->assignRef ( 'request_url', $uri->toString () );
		parent::display ( $tpl );
	}



}
?>
