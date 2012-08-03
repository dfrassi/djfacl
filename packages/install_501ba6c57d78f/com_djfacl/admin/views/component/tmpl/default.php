<?php
/**
 * @package		Joomla.Administrator
 * @subpackage	com_config
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

$template = JFactory::getApplication()->getTemplate();

// Load the tooltip behavior.
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
?>
<script type="text/javascript">


	Joomla.submitbutton = function(task)
	{
		if (document.formvalidator.isValid(document.id('component-form'))) {
			Joomla.submitform(task, document.getElementById('component-form'));
		}
	}
</script>
<form action="<?php echo JRoute::_('index.php?option=com_config');?>" id="component-form" method="post" name="adminForm" autocomplete="off" class="form-validate">
	<fieldset>
		<div class="fltrt">
			<!-- <button type="button" onclick="Joomla.submitform('component.apply', this.form);">
				<?php echo JText::_('JAPPLY');?></button>-->
			<button type="button" onclick="Joomla.submitform('component.save', this.form);">
				<?php echo JText::_('JSAVE');?></button>
			<button type="button" onclick="<?php echo JRequest::getBool('refresh', 0) ? 'window.parent.location.href=window.parent.location.href;' : '';?>  window.parent.SqueezeBox.close();">
				<?php echo JText::_('JCANCEL');?></button>
		</div>
		<div class="configuration" >
			<?php 
			if ($this->form == null) {
				echo JText::_($this->component->option.'_configuration');
				echo("<h1>Configurazione non presente</h1>");
				return false;
			}
			$fieldSets = $this->form->getFieldsets();
			echo JText::_($this->component->option.'_configuration');
				
			
			?>
		</div>
	</fieldset>

	
	<?php
	
	echo JHtml::_('tabs.start', 'config-tabs-'.$this->component->option.'_configuration', array('useCookie'=>1));
		
		$fatto=0;
		
		foreach ($fieldSets as $name => $fieldSet) :
			$label = empty($fieldSet->label) ? 'COM_CONFIG_'.$name.'_FIELDSET_LABEL' : $fieldSet->label;
			
			if (isset($fieldSet->description) && !empty($fieldSet->description)) :
				echo '<p class="tab-description">'.JText::_($fieldSet->description).'</p>';
			endif;
			
			if ($fieldSet->label == "JCONFIG_PERMISSIONS_LABEL"){
				echo JHtml::_('tabs.panel', JText::_($label), 'publishing-details');
				
				$fatto=1;
			}
			
			
			
	?>
			<ul class="config-option-list">
			<?php
			foreach ($this->form->getFieldset($name) as $field):
			?>
				<li>
				<?php if (!$field->hidden) : ?>
				<?php echo $field->label; ?>
				<?php endif; ?>
				<?php echo $field->input; ?>
				</li>
			<?php
			endforeach;

			?>
			</ul>


	<div class="clr"></div>
	<?php
		endforeach;
		
		
	echo JHtml::_('tabs.end');
	?>
	<?php if ($fatto==0){ ?><h1>Configurazione non presente</h1><?php }?>
	
	<div>
		<input type="hidden" name="id" value="<?php echo $this->component->id;?>" />
		<input type="hidden" name="component" value="<?php echo $this->component->option;?>" />
		<input type="hidden" name="task" value="" />
		<?php echo JHtml::_('form.token'); ?>
	
	</div>
	
</form>
