<?php
/**
 * @package		Joomla.Administrator
 * @subpackage	mod_djfacl_quickicon
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

/**
 * @package		Joomla.Administrator
 * @subpackage	mod_djfacl_quickicon
 * @since		1.6
 */
abstract class modDjfaclQuickIconHelper
{
	/**
	 * Stack to hold buttons
	 *
	 * @since	1.6
	 */
	protected static $buttons = array();

	/**
	 * Helper method to return button list.
	 *
	 * This method returns the array by reference so it can be
	 * used to add custom buttons or remove default ones.
	 *
	 * @param	JRegistry	The module parameters.
	 *
	 * @return	array	An array of buttons
	 * @since	1.6
	 */
	public static function &getButtons($params)
	{
		
		require_once (JPATH_PLUGINS . DS . 'system' . DS . 'djflibraries' . DS . 'utility.php');
		$key = (string)$params;
		if (!isset(self::$buttons[$key])) {
			$context = $params->get('context', 'mod_djfacl_quickicon');
			
			$allgidquery = utility::getGroupIdQueryExtension ();
			
			if ($context == 'mod_djfacl_quickicon')
			{
				// Load mod_djfacl_quickicon language file in case this method is called before rendering the module
			
				JFactory::getLanguage()->load('mod_quickicon');
				$i = 0;
				$orstring="";
				$adminrights=0;
			foreach ( $allgidquery as $questoGruppo ) {
				if ($questoGruppo == 8) $adminrights=1;
				if ($i == 0)
					$orstring .= 'and (idgroup = ' . $questoGruppo . " or ";
				if ($i == sizeof ( $allgidquery ) - 1)
					$orstring .= 'idgroup = ' . $questoGruppo . ')';
				else{
			
					$orstring .= 'idgroup = ' . $questoGruppo . " or ";
				}
				$i++;
			}
			
			if ($adminrights){
				
				$iconArray[]=	array(
						'link' => JRoute::_('index.php?option=com_content&task=article.add'),
						'image' => 'header/icon-48-article-add.png',
						'text' => JText::_('mod_quickicon_ADD_NEW_ARTICLE'),
						'access' => array('core.manage', 'com_content', 'core.create', 'com_content', )
					);
					$iconArray[]=	array(
						'link' => JRoute::_('index.php?option=com_content'),
						'image' => 'header/icon-48-article.png',
						'text' => JText::_('mod_quickicon_ARTICLE_MANAGER'),
						'access' => array('core.manage', 'com_content')
					);$iconArray[]=	array(
					
						'link' => JRoute::_('index.php?option=com_categories&extension=com_content'),
						'image' => 'header/icon-48-category.png',
						'text' => JText::_('mod_quickicon_CATEGORY_MANAGER'),
						'access' => array('core.manage', 'com_content')
					);$iconArray[]=	array(
						'link' => JRoute::_('index.php?option=com_media'),
						'image' => 'header/icon-48-media.png',
						'text' => JText::_('mod_quickicon_MEDIA_MANAGER'),
						'access' => array('core.manage', 'com_media')
					);$iconArray[]=	array(
						'link' => JRoute::_('index.php?option=com_menus'),
						'image' => 'header/icon-48-menumgr.png',
						'text' => JText::_('mod_quickicon_MENU_MANAGER'),
						'access' => array('core.manage', 'com_menus')
					);
					
					$iconArray[]=	array(
						'link' => JRoute::_('index.php?option=com_users'),
						'image' => 'header/icon-48-user.png',
						'text' => JText::_('mod_quickicon_USER_MANAGER'),
						'access' => array('core.manage', 'com_users')
					);$iconArray[]=	array(
						'link' => JRoute::_('index.php?option=com_modules'),
						'image' => 'header/icon-48-module.png',
						'text' => JText::_('mod_quickicon_MODULE_MANAGER'),
						'access' => array('core.manage', 'com_modules')
					);$iconArray[]=	array(
						'link' => JRoute::_('index.php?option=com_installer'),
						'image' => 'header/icon-48-extension.png',
						'text' => JText::_('mod_quickicon_EXTENSION_MANAGER'),
						'access' => array('core.manage', 'com_installer')
					);$iconArray[]=	array(
						'link' => JRoute::_('index.php?option=com_languages'),
						'image' => 'header/icon-48-language.png',
						'text' => JText::_('mod_quickicon_LANGUAGE_MANAGER'),
						'access' => array('core.manage', 'com_languages')
					);$iconArray[]=	array(
						'link' => JRoute::_('index.php?option=com_config'),
						'image' => 'header/icon-48-config.png',
						'text' => JText::_('mod_quickicon_GLOBAL_CONFIGURATION'),
						'access' => array('core.manage', 'com_config', 'core.admin', 'com_config')
					);$iconArray[]=	array(
						'link' => JRoute::_('index.php?option=com_templates'),
						'image' => 'header/icon-48-themes.png',
						'text' => JText::_('mod_quickicon_TEMPLATE_MANAGER'),
						'access' => array('core.manage', 'com_templates')
					);$iconArray[]=	array(
						'link' => JRoute::_('index.php?option=com_admin&task=profile.edit&id='.JFactory::getUser()->id),
						'image' => 'header/icon-48-user-profile.png',
						'text' => JText::_('mod_quickicon_PROFILE'),
						'access' => true
					);$iconArray[]=	array(
						'link' => JRoute::_('index.php?option=com_rubriestav'),
						'image' => 'header/icon-48-user-profile.png',
						'text' => JText::_('RUBRIESTAV'),
						'access' => array('core.manage', 'com_rubriestav')
					);
				
				}
					
					$altrowhere  = ', #__djfacl_gruppi_icone as gi where gi.idicon = qi.id ' . $orstring;
					
					if ($adminrights) $altrowhere="";
					
					$querylistaicone = 'select * from #__djfacl_quickicon as qi'.$altrowhere;
					//echo($querylistaicone);
					$lista_icone = utility::getQueryArray ( $querylistaicone );
					//self::$buttons[$key] = null;
					if (sizeof($lista_icone)>0) {
						foreach ( $lista_icone as $questicona ) {
							$link = $questicona->target;
							$src = utility::getBaseUrl () . $questicona->icon;
							$iconArray[]= array('link' => JRoute::_($link),
								'image' => $src,
								'text' => JText::_( $questicona->text),
								'access' => true);
							
						
						}
						self::$buttons[$key] = $iconArray;
					}
					
					
				
			
			}
			
			else
			{
				self::$buttons[$key] = array();
			}

			// Include buttons defined by published quickicon plugins
			JPluginHelper::importPlugin('quickicon');
			$app = JFactory::getApplication();
			$arrays = (array) $app->triggerEvent('onGetIcons', array($context));

			foreach ($arrays as $response) {
				foreach ($response as $icon) {
					$default = array(
						'link' => null,
						'image' => 'header/icon-48-config.png',
						'text' => null,
						'access' => true
					);
					$icon = array_merge($default, $icon);
					if (!is_null($icon['link']) && !is_null($icon['text'])) {
						self::$buttons[$key][] = $icon;
					}
				}
			}
		}

		return self::$buttons[$key];
	}

	/**
	 * Get the alternate title for the module
	 *
	 * @param	JRegistry	The module parameters.
	 * @param	object		The module.
	 *
	 * @return	string	The alternate title for the module.
	 */
	public static function getTitle($params, $module)
	{
		$key = $params->get('context', 'mod_djfacl_quickicon') . '_title';
		if (JFactory::getLanguage()->hasKey($key))
		{
			return JText::_($key);
		}
		else
		{
			return $module->title;
		}
	}
}
