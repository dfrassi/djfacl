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
?>


<style type="text/css">
table.paramlist td.paramlist_key {
	width: 92px;
	text-align: left;
	height: 30px;
}
</style>

<form action="<?php
echo JRoute::_ ( $this->request_url )?>"
	method="post" name="adminForm" id="adminForm">
	<input type="hidden" id="task" name="task" value="" /> 
	<input type="hidden" name="cid[]" value="<?php echo $this->detail->id; ?>" /> 
	<input type="hidden" name="controller" value="contenuti_detail" />


<div class="col50">
<fieldset class="adminform"><legend><?php
echo JText::_ ( 'GESTIONE_CONTENUTI_DETTAGLIO' );
?></legend>
<table width="100%">
	<tr style="vertical-align: top;">
		<td>
<?php

		echo ('<input type="hidden" id="id_article" name="id_article" />');
		echo ('<input type="hidden" id="id_modules" name="id_modules" />');
	
	?>
	<div  style="width: 100%; margin-left: 0px;">
	
		<div  style="background-color: white;">
		
		<table class="admintable">
			<tr>
				<td width="100" align="right" class="key"><label for="title"><?php
	echo JText::_ ( 'Id' );
	?>: </label></td>
				<td><input class="text_area" disabled="disabled" type="text"
					name="id" id="id" size="32" maxlength="450"
					value="<?php
	echo $this->detail->id;
	?>" /></td>
			</tr>
				<tr>
				<td valign="top" align="right" class="key"><label
					for="id_components"><?php
	echo JText::_ ( 'Gruppo' );
	?>:	</label></td>
				<td><?php
	echo $this->lists ['gruppi_associati'];
	?> </td>
			</tr>
			

			<tr>
				<td valign="top" align="right" class="key"><label for="id_modules">
					<?php
	echo JText::_ ( 'Site/Admin' );
	?>:
				</label></td>
				<td >
		
<?php
	
	$checked_1 = 0;
	$checked_0 = 0; 
	
	if ($this->detail->site_admin == 1)
		$checked_1 = "checked";
	if ($this->detail->site_admin == 0)
		$checked_0 = "checked";
	?>


<?php
	utility::onBodyLoad ( 'disableOtherField()' );
	?>
<input id="site_admin" type="radio" name="site_admin" <?php
	echo $checked_1;
	?>
					value="1" onClick="disableOtherField();">Site<br>
				<input type="radio" name="site_admin" <?php
	echo $checked_0;
	?>
					value="0" onClick="disableOtherField();">Administrator<br>

				</td>
			</tr>
			<tr>
				<td valign="top" align="right" class="key"><label for="id_modules">
					<?php
	echo JText::_ ( 'Consenti/Nega' );
	?>:
				</label></td>
				<td>
		
<?php
	
	$published_1 = 0;
	$published_0 = 0;
	
	if ($this->detail->published == 1) $published_1 = "checked";
	if ($this->detail->published == 0) $published_0 = "checked";
	?>


<?php utility::onBodyLoad ( 'disableOtherField()' ); ?>
<input id="published" type="radio" name="published" <?php echo $published_1; ?>
					value="1" onClick="disableOtherField();">Consenti<br>
				<input type="radio" name="published" <?php  echo $published_0; ?>
					value="0" onClick="disableOtherField();">Nega<br>

				</td>
			</tr>
			<tr>
				<td valign="top" align="right" class="key"><label for="id_modules">
					<?php echo JText::_ ( 'Task' ); ?>:
				</label></td>
				<td><input type="text" name="jtask" value="<?php echo ($this->detail->jtask);?>"/></td>
			</tr>
			<tr>
				<td valign="top" align="right" class="key"><label for="css_block">
					<?php echo JText::_ ( 'Css Block' ); ?>:
				</label></td>
				<td><input type="text" name="css_block" value="<?php echo ($this->detail->css_block);?>"/>
			</td>
			</tr>
		</table>
		</div>
		</div>



</td>
	</tr>
</table>
</fieldset>
</div>



</form>


<b>Nota:</b>
<?php echo JText::_ ( 'CONTENUTI_DETTAGLIO_DESCRIZIONE' ); ?>

<input type="hidden" id="extension" name="extension" value="<?php echo(Jrequest::getVar("extension"));?>" />
<input type="hidden" id="idgroup" name="idgroup" value="<?php echo(Jrequest::getVar("idgroup"));?>" />
