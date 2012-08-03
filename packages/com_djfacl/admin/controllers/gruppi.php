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

//DEVNOTE: import CONTROLLER object class
jimport( 'joomla.application.component.controller' );

class gruppiController extends JController
{

	function __construct( $default = array())
	{
		parent::__construct( $default );
	}

		function cancel()
	{
		$this->setRedirect( 'index.php' );
	}

	/**
	 * Method display
	 * 
	 * 1) crea il controller
	 * 2) passa il modello alla vista
	 * 3) carica il template e lo renderizza  	  	 	 
	 */

	function display() {
		$model = $this->getModel ( 'gruppi' );
		//$model->importJUser();
		parent::display();
	}	
	function purge(){
		$model = $this->getModel ('gruppi');
		echo($model->purge());
		$this->setRedirect( 'index.php?option=com_djfacl&controller=gruppi','Pulizia della tabelle eseguita con successo!' );
	}	
	
	function export(){
		$model = $this->getModel ('gruppi');
		echo($model->export());
		//$this->setRedirect( 'index.php?option=com_djfacl&controller=gruppi','Pulizia della tabelle eseguita con successo!' );
	}	
}	
?>
