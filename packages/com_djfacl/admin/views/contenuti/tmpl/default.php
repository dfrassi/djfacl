<?php defined('_JEXEC') or die('Restricted access'); 

//Ordering allowed ?
$ordering = ($this->lists['order'] == 'ordering');

//onsubmit="return submitform();"

//DEVNOTE: import html tooltips

	global $mainframe;
	jimport('joomla.application.module.helper');
    // TODO: allowAllClose should default true in J!1.6, so remove the array when it does.
?>

<script language="javascript" type="text/javascript">
/**
* Submit the admin form
* 
* small hack: let task desides where it comes
*/
function submitform(pressbutton){
var form = document.adminForm;
   if (pressbutton)
    {form.task.value=pressbutton;}
     
	 if ((pressbutton=='add')||(pressbutton=='edit')||(pressbutton=='publish')||(pressbutton=='unpublish')||(pressbutton=='approve')||(pressbutton=='unapprove')
	 ||(pressbutton=='orderdown')||(pressbutton=='orderup')||(pressbutton=='saveorder')||(pressbutton=='remove') )
	 {
	  form.controller.value="contenuti_detail";
	 }
	try {
		form.onsubmit();
		}
	catch(e){}
	
	form.submit();
}


</script>

<form action="<?php echo $this->request_url; ?>" method="post" name="adminForm" >

<div id="editcell" >

<!-- SEARCH -->
		
	<div class="panel" style="width:100%;margin-left:0px;">
		<div class="jpane-slider content" style="background-color:white;">
<fieldset class="adminform" style="border:0px;margin-top:10px;">
<div style="float:left;margin-left:10px;margin-top:5px;"><?php echo JText::_( 'FILTER' ); ?>:&nbsp;</div>
<div style="float:left;">
		<input type="text" name="search" id="search" value="<?php echo $this->search;?>" class="text_area" style="width:150px;" onchange="document.adminForm.submit();" />
		<button onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
		<button onclick="document.getElementById('search').value='';this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>
</div>

<div style="float:left;margin-left:50px;margin-top:5px;"><?php echo JText::_( 'GRUPPO' ); ?>:&nbsp;</div>
<div style="margin-left:5px;">
<?php echo $this->lists ['gruppi_associati']; ?>
</div>

<div style="float:left;margin-left:100px;margin-top:5px;"><?php echo JText::_( 'COPIAGRUPPO' ); ?>:&nbsp;</div>
<div style="float:left;"><?php echo $this->lists ['gruppi_associati_copia']; ?>&nbsp;</div>

	<?php 
	$imgsrc = utility::getBaseUrl()."administrator/components/com_djfacl/assets/images/header/value-16.png";
	$linkbutton = '#';
	$title = JText::_('COPY');
	$javascript="javascript:if(document.adminForm.boxchecked.value==0){alert('Seleziona un elemento dalla lista');}else{ submitbutton('copy')}";
	$alternativo = "alternativo";
	$id = "id";
	$name = "name";
		?>
	<div style="margin-left:">&nbsp;<?php echo(utility::getButton($imgsrc,$linkbutton,$javascript,$name,$title,$id,$alternativo));?></div>		

</fieldset>
	</div>	</div>

<div style="clear:both;"/>
<div id="editcell">
	<table class="adminlist" >
	<thead>
		<tr >
			<th width="1%" style="text-align:left;">
				<?php echo JText::_( 'NUM' ); ?>
			</th>
			<th width="1%"  style="text-align:left;">
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->items ); ?>);" />
			</th>
			<th width="1%" nowrap="nowrap" class="title"  style="text-align:left;">
				<?php echo JHTML::_('grid.sort', 'Chiave', 'h.id', $this->lists['order_Dir'], $this->lists['order'] ); ?>
				
			</th>
			
			<th width="10%" nowrap="nowrap" class="title"  style="text-align:left;">
				<?php echo JHTML::_('grid.sort', 'Gruppo', 'g.title', $this->lists['order_Dir'], $this->lists['order'] ); ?>
				
			</th>
			
			<th width="10%" nowrap="nowrap" class="title"  style="text-align:left;">
				<?php echo JHTML::_('grid.sort', 'Modulo', 'modu.title', $this->lists['order_Dir'], $this->lists['order'] ); ?>
				
			</th>
			
			
			
			<th width="10%" nowrap="nowrap" class="title"  style="text-align:left;">
				<?php echo JHTML::_('grid.sort', 'Site_Admin', 'h.site_admin', $this->lists['order_Dir'], $this->lists['order'] ); ?>
				
			</th>
					<th width="10%" nowrap="nowrap" class="title"  style="text-align:left;">
				<?php echo JHTML::_('grid.sort', 'Consenti_Nega', 'h.published', $this->lists['order_Dir'], $this->lists['order'] ); ?>
				
			</th>
				<th width="10%" nowrap="nowrap" class="title"  style="text-align:left;">
				<?php echo JHTML::_('grid.sort', 'Task', 'h.jtask', $this->lists['order_Dir'], $this->lists['order'] ); ?>				
			</th>
					<th width="10%" nowrap="nowrap" class="title"  style="text-align:left;">
				<?php echo JHTML::_('grid.sort', 'Css Block', 'h.css_block', $this->lists['order_Dir'], $this->lists['order'] ); ?>				
			</th>
								
		</tr>
	</thead>	
	
	
	<?php 
	//echo $this->pulsanti; 
	$iconUnPublish = " <img border=\"0\" src=\"images/publish_x.png\" alt=\"add new hello world link\" />";
	$iconPublish = " <img border=\"0\" src=\"images/tick.png\" alt=\"add new hello world link\" />";		
	
	$k = 0;
	for ($i=0, $n=count( $this->items ); $i < $n; $i++)
	{
		$row = &$this->items[$i];
		$row->checked_out="";
		
		$tab="1";
		
		$link 	= JRoute::_( 'index.php?option=com_djfacl&controller=contenuti_detail&task=edit&tab='.$tab.'&cid[]='. $row->id );

		$checked 	= JHTML::_('grid.checkedout',$row, $i );

		?>
		
		<?php
					$script_ceccato = "";
					$ceccato = false;
				
		?>
		
		<tr class="<?php echo "row$k"; ?>">
			<td>
				<?php echo $this->pagination->getRowOffset( $i ); ?>
			</td>
			<td>
				<?php echo $checked; ?>
			</td>
			<td>
			
					<a <?php echo $script_ceccato; ?> href="<?php echo $link; ?>" title="<?php echo JText::_( 'Edit contenuti' ); ?>">
						<?php echo $row->id; ?></a>
			
			</td>
			
			
				<td>
				
					<a <?php echo $script_ceccato; ?> href="<?php echo $link; ?>" title="<?php echo JText::_( 'Edit grouppo' ); ?>">
						<?php echo $row->gruppo; ?></a>
				
			</td>
						
						<td>
				
					<a <?php echo $script_ceccato; ?> href="<?php echo $link; ?>" title="<?php echo JText::_( 'Edit grouppo' ); ?>">
						<?php //echo $row->modulo; ?></a>
				
			</td>
			
			
				
			
				<td>
			
					<a <?php echo $script_ceccato; ?> href="<?php echo $link; ?>" title="<?php echo JText::_( 'Edit contenuti' ); ?>">
						<?php 
						if ($row->site_admin == 1)
						echo "Site";
						else echo "Administrator";
							
						?></a>
				
			</td>
			<td>
			
					<a <?php echo $script_ceccato; ?> href="<?php echo $link; ?>" title="<?php echo JText::_( 'Edit contenuti' ); ?>">
						<?php 
						if ($row->published == 1)
						echo "Consenti";
						else echo "Nega";
							
						?></a>
				
			</td>
			<td>
				
					<a <?php echo $script_ceccato; ?> href="<?php echo $link; ?>" title="<?php echo JText::_( 'Edit task' ); ?>">
						<?php echo $row->jtask; ?></a>
				
			</td>
			<td>
				
					<a <?php echo $script_ceccato; ?> href="<?php echo $link; ?>" title="<?php echo JText::_( 'Edit task' ); ?>">
						<?php echo $row->css_block; ?></a>
				
			</td>

		</tr>
		<?php
		$k = 1 - $k;
	}
	
	
	
	?>
	
	
	
<tfoot>
		<td colspan="12">
			<?php echo $this->pagination->getListFooter(); ?> 
		</td>
	</tfoot>
	</table>
</div>
<div>
<p><b>Note:</b> <?php echo(JText::_ ( 'CONTENUTI_DESCRIZIONE' )); ?></p>
</div>
</div>
</div>
</div>
<input type="hidden" name="controller" value="contenuti" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />

</form>

