<?php
/**
 * @version $Id: component.php 5173 2006-09-25 18:12:39Z Jinx $
 * @package Joomla
 * @subpackage Config
 * @copyright Copyright (C) 2005 - 2006 Open Source Matters. All rights reserved.
 * @license GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and
 * details.
 *
 * php echo $lang->getName();
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );

//DEVNOTE: import html tooltips
JHTML::_ ( 'behavior.tooltip' );
JHTML::_ ('behavior.modal');
?>

<script language="javascript" type="text/javascript">
	function submitbutton(pressbutton) {

		
		
		var form = document.adminForm;

		if (pressbutton == 'cancel') {
			submitform( pressbutton );
			return;
		}
		if (pressbutton == 'save') {
			<?php echo(utility::getJSCheckForm('jtask', '==""', JText::_('CHECK_MSG_TASK'))); ?>
			
				submitform( pressbutton );


		}


	}
</script>



<style type="text/css">
table.paramlist td.paramlist_key {
	width: 92px;
	text-align: left;
	height: 30px;
}
</style>

<form action="<?php echo JRoute::_ ( $this->request_url )?>" method="post" name="adminForm" id="adminForm">
<div class="col50">
<fieldset class="adminform"><legend><?php echo JText::_ ( 'DETAIL' ); ?></legend>
<table class="admintable">
	<tr>
		<td width="100" align="right" class="key"><label for="title">
					<?php
					echo JText::_ ( 'Id' );
					?>:
				</label></td>
		<td>
		<input class="text_area" disabled="disabled" type="text" name="id"
			id="id" size="32" maxlength="250"
			value="<?php
			echo $this->detail->id;
			?>" /></td>
	</tr>
 	
<tr>
		<td valign="top" align="right" class="key"><label
			for="jtask">
					<?php
					echo JText::_ ( 'Name' );
					?>:
				</label></td>
		<td>
		<input class="text_area" type="text" name="name"
			id="name" size="32" maxlength="250"
			value="<?php echo $this->detail->name; ?>" /></td>
	</tr>

	<tr>
		<td valign="top" align="right" class="key"><label
			for="jtask">
					<?php
					echo JText::_ ( 'Task' );
					?>:
				</label></td>
		<td>
		<input class="text_area" type="text" name="jtask"
			id="jtask" size="32" maxlength="250"
			value="<?php echo $this->detail->jtask; ?>" /></td>
	</tr>


</table>
</fieldset>


</div>
<div class="col50"></div>
<div class="clr"></div>

<input type="hidden" name="cid[]" value="<?php echo $this->detail->id;	?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="controller" value="jtask_detail" />

</form>


