<?php
/**
 * @package		Joomla.Administrator
 * @subpackage	com_users
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');

// Load the tooltip behavior.
JHtml::_('behavior.tooltip');
JHtml::_('behavior.modal', 'a.modal');
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
?>

<form
	action="<?php echo JRoute::_('index.php?option=com_djfacl&controller=users&view=debuggroup&user_id='.(int) $this->state->get('filter.user_id'));?>"
	method="post" name="adminForm" id="adminForm">
	<fieldset id="filter-bar">
		<div class="filter-search fltlft">
			<label class="filter-search-lbl" for="filter_search"><?php echo JText::_('COM_USERS_SEARCH_ASSETS'); ?>
			</label> <input type="text" name="filter_search" id="filter_search"
				value="<?php echo $this->escape($this->state->get('filter.search')); ?>"
				title="<?php echo JText::_('COM_USERS_SEARCH_USERS'); ?>" />
			<button type="submit">
				<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>
			</button>
			<button type="button"
				onclick="document.id('filter_search').value='';this.form.submit();">
				<?php echo JText::_('JSEARCH_RESET'); ?>
			</button>
		</div>

		<div class="filter-select fltrt">
			<select name="filter_component" class="inputbox"
				onchange="this.form.submit()">
				<option value="">
					<?php echo JText::_('COM_USERS_OPTION_SELECT_COMPONENT');?>
				</option>
				<?php if (!empty($this->components)) {
					echo JHtml::_('select.options', $this->components, 'value', 'text', $this->state->get('filter.component'));
				}?>
			</select> <select name="filter_level_start" class="inputbox"
				onchange="this.form.submit()">
				<option value="">
					<?php echo JText::_('COM_USERS_OPTION_SELECT_LEVEL_START');?>
				</option>
				<?php echo JHtml::_('select.options', $this->levels, 'value', 'text', $this->state->get('filter.level_start'));?>
			</select> <select name="filter_level_end" class="inputbox"
				onchange="this.form.submit()">
				<option value="">
					<?php echo JText::_('COM_USERS_OPTION_SELECT_LEVEL_END');?>
				</option>
				<?php echo JHtml::_('select.options', $this->levels, 'value', 'text', $this->state->get('filter.level_end'));?>
			</select> <select name="group_id" class="inputbox"
				onchange="this.form.submit()">
				<!-- <option value="">
					<?php echo JText::_('COM_USERS_OPTION_SELECT_GROUP');?>
				</option>-->
				<?php echo JHtml::_('select.options', $this->groups, 'value', 'text', $this->state->get('filter.group_id'));?>
			</select>
		</div>

	</fieldset>
	<div class="clr"></div>

	<div>
		<?php echo JText::_('COM_USERS_DEBUG_LEGEND'); ?>
		<span class="swatch"><?php echo JText::sprintf('COM_USERS_DEBUG_NO_CHECK', '-');?>
		</span> <span class="check-0 swatch"><?php echo JText::sprintf('COM_USERS_DEBUG_IMPLICIT_DENY', '-');?>
		</span> <span class="check-a swatch"><?php echo JText::sprintf('COM_USERS_DEBUG_EXPLICIT_ALLOW', '&#10003;');?>
		</span> <span class="check-d swatch"><?php echo JText::sprintf('COM_USERS_DEBUG_EXPLICIT_DENY', '&#10007;');?>
		</span>
	</div>

	<table class="adminlist">
		<thead>
			<tr>
				<th class="left"><?php echo JHtml::_('grid.sort', 'COM_USERS_HEADING_ASSET_TITLE', 'a.title', $listDirn, $listOrder); ?>
				</th>
				<th class="left"><?php echo JHtml::_('grid.sort', 'COM_USERS_HEADING_ASSET_NAME', 'a.name', $listDirn, $listOrder); ?>
				</th>
				<?php foreach ($this->actions as $key => $action) : ?>
				<th width="5%"><span class="hasTip"
					title="<?php echo htmlspecialchars(JText::_($key).'::'.JText::_($action[1]), ENT_COMPAT, 'UTF-8'); ?>">
					<?php 
					//core.login.site 	core.login.admin 	core.login.offline
					if ($action[0]=="core.login.site" || $action[0]=="core.login.admin" || $action[0]=="core.login.offline"){
						$stringalink = '<a class="modal" rel="{handler: \'iframe\', size: {x: 875, y: 450}, onClose: function() {}}" target="config" href="index.php?option=com_config">'.JText::_($key).'</a>';
						echo($stringalink);
					}else
					echo JText::_($key); 
					
					?>
				</span>
				</th>
				<?php endforeach; ?>
				<!-- <th class="nowrap" width="5%">
					<?php echo JHtml::_('grid.sort', 'COM_USERS_HEADING_LFT', 'a.lft', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap" width="3%">
					<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
				</th>-->
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="15"><?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
			<?php foreach ($this->items as $i => $item) : ?>
			<tr class="row0">
				<td><?php echo $this->escape($item->title); ?>
				</td>
				<td class="nowrap"><?php echo str_repeat('<span class="gi">|&mdash;</span>', $item->level) ?>
					<?php 
					$namelink = $this->escape($item->name);

					$tokens = explode(".",$namelink);
					$dimtok = sizeof($tokens);
					if ($dimtok>0){
						$extension = $tokens[0];
						$catid = $tokens[$dimtok-1];
					}

					http://10.2.220.147/joomladev25/administrator/index.php?option=com_content&task=article.edit&id=3

					$path = JPATH_ADMINISTRATOR.'/components/'.$extension;
					$ok = true;
					
							
						if (utility::contains($namelink, ".category.")){
							$linkobj = "index.php?option=com_categories&task=category.edit&id=$catid&extension=$extension";
						}else if (utility::contains($namelink, ".article.")){
							$linkobj = "index.php?option=com_content&task=article.edit&id=$catid";
						}else {
							$linkobj = "index.php?option=com_djfacl&controller=component&view=component&tmpl=component&component=$namelink";
						}
							
						

						$stringalink = '<a class="modal" rel="{handler: \'iframe\', size: {x: 875, y: 450}, onClose: function() {}}" target="config" href='.$linkobj.'>'.$namelink.'</a>';
						if (
								utility::contains($namelink, "root.")
								|| utility::contains($namelink, "com_admin")
								|| utility::contains($namelink, "com_cache")
								|| utility::contains($namelink, "com_config")
								|| utility::contains($namelink, "com_cpanel")
								|| utility::contains($namelink, "com_languages")
								|| utility::contains($namelink, "com_login")
								|| utility::contains($namelink, "com_mailto")
								|| utility::contains($namelink, "com_massmail")
								|| utility::contains($namelink, "com_wrapper")
								
								) $stringalink = $namelink;
					
					echo  $stringalink; 
					if (utility::contains($namelink, ".article.")){
						//echo(' - <a class="modal" rel="{handler: \'iframe\', size: {x: 875, y: 450}, onClose: function() {}}" target="config" href=index.php?option=com_djfacl&controller=contenuti&tmpl=component&idgroup='.$this->state->get('filter.group_id').'&catid=>a</a>');
					}else{
						$imgsrc = utility::getBaseUrl()."administrator/components/com_djfacl/assets/images/header/value-16.png";
					$linkbutton = 'index.php?option=com_djfacl&controller=contenuti&catid='.$catid.'&extension='.$extension.'&idgroup='.$this->state->get('filter.group_id');
					$title = JText::_('Extended ACL');
					$javascript='" class="modal" rel="{handler: \'iframe\', size: {x: 875, y: 450}, onClose: function() {}}" target="config" ';
					$alternativo = "alternativo";
					$id = "id";
					$name = "name";
					
					echo(utility::getButton($imgsrc,$linkbutton,$javascript,$name,$title,$id,$alternativo));
					
					}
					
					
					
					?></td>
				<?php foreach ($this->actions as $action) : ?>
				<?php
				$name	= $action[0];
					
				$check = JAccess::checkGroup($this->group_id, $name, $asset = $item->name);
					
				//$check	= $item->checks[$name];
				//$check=true;
				if ($check === true) :
				$class	= 'check-a';
				$text	= '&#10003;';
				elseif ($check === false) :
				$class	= 'check-d';
				$text	= '&#10007;';
				elseif ($check === null) :
				$class	= 'check-0';
				$text	= '-';
				else :
				$class	= '';
				$text	= '&#160;';
				endif;
				?>
				<td class="center <?php echo $class;?>"><?php echo $text; ?>
				</td>
				<?php endforeach; ?>
				<!-- <td class="center">
					<?php echo (int) $item->lft; ?>
					- <?php echo (int) $item->rgt; ?>
				</td>
				<td class="center">
					<?php echo (int) $item->id; ?>
				</td>-->
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<div>
		<input type="hidden" name="task" value="" /> <input type="hidden"
			name="boxchecked" value="0" /> <input type="hidden"
			name="filter_order" value="<?php echo $listOrder; ?>" /> <input
			type="hidden" name="filter_order_Dir"
			value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
