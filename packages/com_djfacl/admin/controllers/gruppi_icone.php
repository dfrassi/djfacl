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

//DEVNOTE: import CONTROLLER object class
jimport ( 'joomla.application.component.controller' );
require_once (JPATH_COMPONENT_ADMINISTRATOR . DS . 'tables' . DS . 'gruppi_icone_detail.php');
require_once (JPATH_PLUGINS . DS . 'system' . DS . 'djflibraries' . DS . 'utility.php');

class gruppi_iconeController extends JController {
	
	function __construct($default = array()) {
		parent::__construct ( $default );
	}
	
	function add(){
		$this->setRedirect ( 'index.php?option=com_djfacl&controller=gruppi_icone_detail' );
	}
	
	function remove(){
		$model = $this->getModel ( 'gruppi_icone' );
		$model->delete();
		$this->setRedirect ( 'index.php?option=com_djfacl&controller=gruppi_icone' );
		
	}
	
	function cancel() {
		$this->setRedirect ( 'index.php' );
	}
	
	/**
	 * Method display
	 * 
	 * 1) crea il controller
	 * 2) passa il modello alla vista
	 * 3) carica il template e lo renderizza  	  	 	 
	 */
	
	function display() {
		
		$model = $this->getModel ( 'gruppi_icone' );
		parent::display ();
	
	}
	
	function copy() {
		$this->setRedirect ( 'index.php?option=com_djfacl' );
		$cid = JRequest::getVar ( 'cid', null, 'post', 'array' );
		$table = & JTable::getInstance ( 'gruppi_icone_detail', 'Table' );
		$n = count ( $cid );
		
		$id_group_copia = utility::getDjfVar ( 'idgroup_copia', '0' );
		
		if ($n > 0) {
			foreach ( $cid as $id ) {
				if ($table->load ( ( int ) $id )) {
					$table->id = 0;
					$table->idgroup = $id_group_copia;
					$table->typology = "djfacl";
					
					if (! $table->store ()) {
						exit ();
					}
				} else {
					return JError::raiseWarning ( 500, $table->getError () );
				}
			}
		} else {
			return JError::raiseWarning ( 500, JText::_ ( 'No items selected' ) );
		}
		$this->setMessage ( JText::sprintf ( 'Items copied', $n ) );
		
		$this->setRedirect ( 'index.php?option=com_djfacl&controller=gruppi_icone' );
	}
	
	function getTypology($idgroup){
		$tipo = "joomla";
		
			if ($idgroup > 8)
				$tipo = "djfacl";
			else
				$tipo = "joomla";
		
		echo("<br>".$tipo."<br>");
		return $tipo;
	}
	
	
	function move() {
		$this->setRedirect ( 'index.php?option=com_djfacl' );
		$cid = JRequest::getVar ( 'cid', null, 'post', 'array' );
		$table = & JTable::getInstance ( 'gruppi_icone_detail', 'Table' );
		$n = count ( $cid );
		$id_group_copia = utility::getDjfVar ( 'idgroup_sposta', '0' );
		
		if ($n > 0) {
			foreach ( $cid as $id ) {
				if ($table->load ( ( int ) $id )) {
					$table->id = $id;
					$idgroup_provenienza = $table->idgroup;
					$table->idgroup = $id_group_copia;
					echo($table->id."<br>");
					echo("idgroup_provenienza = ".$idgroup_provenienza."<br>");
					echo("idgroup_sposta = ".$id_group_copia."<br>");
					
					if ($this->getTypology($idgroup_provenienza) != $this->getTypology($id_group_copia)) {
						$this->setRedirect ( 'index.php?option=com_djfacl&controller=gruppi_icone', JText::_ ( 'TIPI_NON_COINCIDENTI' ) );
						return false;
					}
					
					//exit();
					if (! $table->store ()) {
						exit ();
					} else
					{
						$queryUpdate = 'update #__core_acl_groups_aro_map as aromap, #__core_acl_aro as aro set aromap.group_id = ' . $table->idgroup . ' where aromap.group_id = ' . $idgroup_provenienza . ' and aro.id = aromap.aro_id and aro.value = ' . $table->iduser;
						echo ($queryUpdate);
						utility::executeQuery ( $queryUpdate );
						$queryUpdate = 'update #__users set gid=' . $table->idgroup . ' where id = ' . $table->iduser;
						echo ($queryUpdate);
						utility::executeQuery ( $queryUpdate );
						
					//exit();						
					}
				} else {
					return JError::raiseWarning ( 500, $table->getError () );
				}
			}
		} else {
			return JError::raiseWarning ( 500, JText::_ ( 'No items selected' ) );
		}
		$this->setMessage ( JText::sprintf ( 'Items moved', $n ) );
		$this->setRedirect ( 'index.php?option=com_djfacl&controller=gruppi_icone' );
	}

}
?>
