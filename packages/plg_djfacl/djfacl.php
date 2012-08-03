<?php
// no direct access
defined ( '_JEXEC' ) or die ( 'Restricted access' );

jimport ( 'joomla.plugin.plugin' );
jimport ( 'joomla.installer.installer' );

require_once (JPATH_PLUGINS . DS . 'system' . DS . 'djflibraries' . DS . 'utility.php');
require_once (JPATH_PLUGINS . DS . 'system' . DS . 'djflibraries' . DS . 'multimedia.php');

/**
 * Example system plugin
 */
class plgSystemDjfAcl extends JPlugin {
	//rrr
	var $globArrMultiGroups;
	/**
	 * Constructor
	 */
	public function plgSystemDjfAcl(&$subject, $config) {
		parent::__construct ( $subject, $config );
		$document = & JFactory::getDocument ();
		$document->addStyleSheet ( JURI::base () . 'plugins/content/djfappend.css', 'text/css', null, array () );
		$document->addScript ( JURI::root () . 'plugins/system/djflibraries/assets/players/flowplayer/flowplayer-3.2.6.min.js' );

	}

	function onPrepareContent() {

	}

	function blockHider($toSearch) {
		$output = JResponse::getBody ();
		$cssToHide = '<style type="text/css">.' . $toSearch . '{display:none;}</style>';
		$cssToHide .= '<style type="text/css">#' . $toSearch . '{display:none;}</style>';
		$output = str_replace ( "</body>", $cssToHide . "</body>", $output );
		$output = str_replace ( "<img alt=\"Nuovo\" ", "<img alt=\"Nuovo\" style=\"display:none\"", $output );

		JResponse::setBody ( $output );
	}

	function unblockHider($toSearch) {
		$output = JResponse::getBody ();
		$toSearch2 = trim ( $toSearch );
		$toSearch = '<style type="text/css">.' . $toSearch2 . '{display:none;}</style>';
		$toSearch .= '<style type="text/css">#' . $toSearch2 . '{display:none;}</style>';
		$output = str_replace ( $toSearch, '', $output );
		JResponse::setBody ( $output );
	}

	public function blockAllBlock($debug = false) {

		$esito = false;
		$mainframe =& JFactory::getApplication();
		$db = & JFactory::getDBO ();
		$app = & JFactory::getApplication ();
		$option = JRequest::getCMD ( 'option' );
		$applicationName = $app->getName ();
		$user = Jfactory::getUSER ();
		$uid = $user->id;
		$articleQuery = "";
		$gid = $user->getAuthorisedGroups();
		$id = JRequest::getVar ( 'id' );
		if ($id == '' || $id == null)
			$id = '0';
		$view = JRequest::getVar ( 'view' );
		$task = JRequest::getCmd ( 'task' );
		$catid = JRequest::getVar ( 'catid' );

		$layout = JRequest::getVar ( 'layout' );

		$categoryQuery = "";

		$arid = explode ( ":", $id );
		$id = $arid [0];

		if ($view == "category") {
			$categoryQuery = " and (jc.id_category = 999999 or jc.id_category = " . $id . ") ";

		} elseif ($view == "article" && ! empty ( $id )) {
			$arid = explode ( ":", $id );
			$id = $arid [0];

			$artQuery = "select id, catid from #__content where id = " . $id;
			$db->setQuery ( $artQuery );
			$arrayArt = $db->loadObjectList ();
			$artid = "";
			if (sizeOf ( $arrayArt ) > 0)
				foreach ( $arrayArt as $questoGid ) {
				$catid = $questoGid->catid;
			}

			$articleQuery = " and (jc.id_article = $id or jc.id_category = 9999999 or jc.id_category = $catid) ";

		}

		$orGroupQuery = plgSystemDjfAcl::getGroupIdQueryExtension ();

		$queryGid = "select css_block from #__djfacl_cssblock";
		$db->setQuery ( $queryGid );

		$arrayGid = $db->loadObjectList ();
		$allgidquery = "";
		if (sizeof ( $arrayGid ) > 0)
			foreach ( $arrayGid as $questoGid ) {
			if ($applicationName == "site") {
				plgSystemDjfAcl::blockHider ( $questoGid->css_block );
			}

		}

		$queryyGid = "";

		$css_block_frontend = $this->params->get ( 'css_block_frontend' );

		if ($debug)
			echo ("<h1>$css_block_frontend</h1>");

		if ($view == "article" && $applicationName == "site") {

			$queryyGid = "select a.css_block as css_block from #__djfacl_cssblock a where 0 < (
			select count(*)
			from #__djfacl_contenuti jc,  #__djfacl_components c
			where (jc.id_components = c.id or jc.id_components = '999999')
			and (c.`option` = '" . $option . "' ) and
			(jc.css_block = a.id or jc.css_block = '999999') and
			jc.site_admin = 1 and

			(jc.id_users=$uid $orGroupQuery) $articleQuery  $categoryQuery )";
		} elseif ($view == "category" && $applicationName == "site") {
			$queryyGid = "select a.css_block as css_block from #__djfacl_cssblock a where

			0 < (
			select count(*)
			from #__djfacl_contenuti jc,  #__djfacl_components c
			where (jc.id_components = c.id or jc.id_components = '999999')
			and (c.`option` = '" . $option . "') and
			(jc.css_block = a.id or jc.css_block = '999999') and
			jc.site_admin = 1 and
			(jc.id_users=$uid $orGroupQuery)   $categoryQuery) ";

		} elseif ($view == "field" && $option == "com_djfacl" && $applicationName == "site") {
			$params = &$mainframe->getParams ();

			if (empty ( $catid ))
				$catid = $params->get ( 'catid' );


			$queryyGid = "select a.css_block as css_block from #__djfacl_cssblock a where 0 < (
			select count(*)
			from #__djfacl_contenuti jc,  #__djfacl_components c
			where (jc.id_components = c.id or jc.id_components = '999999')
			and (c.`option` = '" . $option . "') and
			(jc.css_block = a.id or jc.css_block = '999999') and
			(jc.id_users=$uid $orGroupQuery) and
			jc.site_admin = 1 and
			(false or jc.id_category = $catid or jc.id_category = 999999)
			)";

		}
		elseif ($css_block_frontend == 1 && $applicationName == "site") {
			$queryyGid = "select a.css_block as css_block from #__djfacl_cssblock a where 0 < (
			select count(*)
			from #__djfacl_contenuti jc,  #__djfacl_components c
			where (jc.id_components = c.id or jc.id_components = '999999')
			and (c.`option` = '" . $option . "') and
			(jc.css_block = a.id or jc.css_block = '999999') and
			(jc.id_users=$uid $orGroupQuery) and
			jc.site_admin = 1 )";

		}

		if ($debug)
			echo ("<br><br><p style=\"color:black\">$queryyGid</p><br><br>");

		if ($debug)
			echo ("<br>-----------------------------------------------------------------------------------------><br>" . $queryyGid . "-----------------------------------------------------------------------------------------><br>");

		if ($debug) echo("sono qui = ".$queryyGid);
			
		if (! empty ( $queryyGid )) {
			$arrayyGid = utility::getQueryArray ( $queryyGid );
			if (! empty ( $arrayyGid ))
				foreach ( $arrayyGid as $questoyGid ) {
				if ($applicationName == "site") {

					plgSystemDjfAcl::unblockHider ( $questoyGid->css_block );


				}
					
					
			}

		}

		if ($view == "article") {
			$ce = utility::getField ( "select id as value from #__djfacl_contenuti where id_article = " . $id );
			if (! empty ( $ce )) {
				$querycss = "select a.css_block as css_block from #__djfacl_cssblock a";
				$arraycss = utility::getQueryArray ( $querycss );
				if (! empty ( $arraycss )) {
					foreach ( $arraycss as $questocss ) {
						if ($applicationName == "site") {
							plgSystemDjfAcl::unblockHider ( $questocss->css_block );
						} else if ($applicationName == "administrator" && $gid != "25") {
							plgSystemDjfAcl::unblockHider ( $questoGid->css_block );
						}
							
					}
				}
					
			}

		}


	}

	function onAfterRender() {
		$mainframe =& JFactory::getApplication();

		if (! plgSystemDjfAcl::check () == true) {

			plgSystemDjfAcl::blockAllBlock ( false );
		}
		$app = JFactory::getApplication ();
		if ($app->isAdmin ()) {
			return;
		}

		$category_check = $this->params->get ( 'category_check' );
		if ($category_check == "1") {
			$output = JResponse::getBody ();

			$output = str_replace ( "new.png\"", "#\" style=\"display:none;\"", $output );
			JResponse::setBody ( $output );
		}

		$back_link = $this->params->get ( 'back_link' );
		if ($back_link == "1") {
			$output = JResponse::getBody ();
			$base_url = 'components/com_djfacl/assets/images/back-arrow.png';

			if (JRequest::getVar ( 'return' ) != "") {
				$link = "<a  href=\"" . base64_decode ( JRequest::getVar ( 'return' ) ) . "\" title=\"back\" >";
			} else {
				$link = "<a  href=\"javascript:history.back();\" title=\"Back\" >";
			}
			$output = str_replace ( "breadcrumbs pathway\">", "breadcrumbs pathway\">" . $link . "<img style='margin-right:4px;margin-bottom:-2px;' src=\"" . $base_url . "\"/></a>   ", $output );
			JResponse::setBody ( $output );
		}

		return true;
	}

	public function checkBlock($debug = false) {

		$esito = false;
		$mainframe =& JFactory::getApplication();
		$db = & JFactory::getDBO ();
		$app = & JFactory::getApplication ();
		$option = JRequest::getCMD ( 'option' );
		$applicationName = $app->getName ();
		$user = Jfactory::getUSER ();
		$uid = $user->id;
		$gid = $user->getAuthorisedGroups();
		$id = JRequest::getVar ( 'id' );

		$arid = explode ( ":", $id );
		$id = $arid [0];

		$view = JRequest::getVar ( 'view' );
		$task = JRequest::getCmd ( 'task' );
		$catid = JRequest::getVar ( 'catid' );


		$esito = true;

		if ($debug)
			echo ("<br>checkBlock -> esito =  " . $esito);
		return $esito;

	}


	public function checkCategoryFromDjfContent($debug = false, $idCategory = '') {

		$esito = false;
		$mainframe =& JFactory::getApplication();

		$db = & JFactory::getDBO ();
		$app = & JFactory::getApplication ();
		$option = JRequest::getCMD ( 'option' );
		$applicationName = $app->getName ();
		$user = Jfactory::getUSER ();
		$uid = $user->id;
		$gid = $user->getAuthorisedGroups();
		$id = JRequest::getVar ( 'id' );

		$arid = explode ( ":", $id );
		$id = $arid [0];
		if ($id == null || $id == "")
			$id = 0;

		$view = JRequest::getVar ( 'view' );
		$task = JRequest::getCmd ( 'task' );
		$catid = JRequest::getVar ( 'catid' );

		if ($idCategory != "")
			$id = $idCategory;

		if ($view == "category" || $idCategory != "") {

			$querySpecifica = "
			select
			*
			from #__djfacl_contenuti jc, #__djfacl_components compo
			where (jc.id_category = 999999 or jc.id_category = " . $id .  ") " . plgSystemDjfAcl::getTaskQueryExtension () . plgSystemDjfAcl::getSiteAdminQueryExtension () . "
			and (jc.id_group = 0 " . plgSystemDjfAcl::getGroupIdQueryExtension () . " or jc.id_users = " . $uid . ") and
			(jc.id_components = 999999 or (compo.id=jc.id_components and compo.`option`='$option'))";
			if ($debug)
				echo ("<br>checkCategory -> " . $querySpecifica);
			$db->setQuery ( $querySpecifica );
			$arrayRisultati = $db->loadObjectList ();
			if (sizeof ( $arrayRisultati ) > 0)
				$esito = true;
		} else
			$esito = true;

		if ($debug)
			echo ("<br>checkCategory -> esito =  " . $esito);
		return $esito;
	}

	public function checkCategory($debug = false, $idCategory = '') {
		$esito = false;
		$mainframe =& JFactory::getApplication();
		$db = & JFactory::getDBO ();
		$app = & JFactory::getApplication ();
		$option = JRequest::getCMD ( 'option' );
		$applicationName = $app->getName ();
		$user = Jfactory::getUSER ();
		$uid = $user->id;
		$gid = $user->getAuthorisedGroups();
		$id = JRequest::getVar ( 'id' );
		$arid = explode ( ":", $id );
		$id = $arid [0];

		if (empty ( $id ))
			$id = 0;

		$view = JRequest::getVar ( 'view' );

		$task = JRequest::getCmd ( 'task' );
		$catid = JRequest::getVar ( 'catid' );

		if (! empty ( $idCategory ))
			$id = $idCategory;
		else if (! empty ( $catid ))
			$id = $catid;

		if ($applicationName == "administrator" && $id == - 1)
			$id = 0;

		if (($view == "category" || $idCategory != "") && ($task != "edit" && $task != "save") && $applicationName == "site") {

			if ($task == "edit" && $view == "category") {
				$view = "article";
			}




			$querySpecifica = "
			select
			*
			from #__djfacl_contenuti jc, #__djfacl_components c
			where (jc.id_components = c.id or jc.id_components = '999999')
			and (c.`option` = '" . $option . "')
			and  (jc.id_category = 999999 or jc.id_category = " . $id . ") " . plgSystemDjfAcl::getTaskQueryExtension () . plgSystemDjfAcl::getSiteAdminQueryExtension () . "
			and (jc.id_group = 0 " . plgSystemDjfAcl::getGroupIdQueryExtension () . " or jc.id_users = " . $uid . ")";
			if ($debug)
				echo ("<br>checkCategory -> " . $querySpecifica);
			$db->setQuery ( $querySpecifica );
			$arrayRisultati = $db->loadObjectList ();
			if (sizeof ( $arrayRisultati ) > 0)
				$esito = true;
		} else

			if ($applicationName == "administrator" && ! empty ( $id ) && $task != "cancel") {

			$querySpecifica = "
			select
			*
			from #__djfacl_contenuti jc, #__djfacl_components c
			where (jc.id_components = c.id or jc.id_components = '999999')
			and (c.`option` = '" . $option . "')
			and  (jc.id_category = 999999 or jc.id_category = " . $id .  ") " . plgSystemDjfAcl::getTaskQueryExtension () . plgSystemDjfAcl::getSiteAdminQueryExtension () . "
			and (jc.id_group = 0 " . plgSystemDjfAcl::getGroupIdQueryExtension () . " or jc.id_users = " . $uid . ")";
			if ($debug)
				echo ("<br>checkCategory -> " . $querySpecifica);
			$db->setQuery ( $querySpecifica );
			$arrayRisultati = $db->loadObjectList ();
			if (sizeof ( $arrayRisultati ) > 0)
				$esito = true;
		} else
			$esito = true;

		if ($debug)
			echo ("<br>checkCategory -> esito =  " . $esito);
		return $esito;
	}

	public function checkArticle($debug = false) {
		$esito = false;
		$mainframe =& JFactory::getApplication();
		$db = & JFactory::getDBO ();
		$app = & JFactory::getApplication ();
		$option = JRequest::getCMD ( 'option' );
		$applicationName = $app->getName ();
		$user = Jfactory::getUSER ();
		$uid = $user->id;
		$gid = $user->getAuthorisedGroups();
		$id = JRequest::getVar ( 'id' );
		if (empty ( $id )) {
			$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
			$id = $cid [0];
			if (empty ( $id ))
				$id = '0';
		}
		$arid = explode ( ":", $id );
		$id = $arid [0];

		$view = JRequest::getVar ( 'view' );
		$task = JRequest::getCmd ( 'task' );

		$catid = JRequest::getVar ( 'catid' );
		$arraId = explode ( ":", $id );
		$id = $arraId [0];

		if (($task == "article.edit" || $task == "article.save") && $option=="com_content" && $view == "category" && $applicationName == "site") {
			$view = "article";

			$querySpecifica = "

			select
			*
			from #__djfacl_contenuti jc, #__djfacl_components c
			where (jc.id_components = c.id or jc.id_components = '0')  and (c.`option` = '" . $option . "')
			and (jc.id_group = 0 " . plgSystemDjfAcl::getGroupIdQueryExtension () . " or jc.id_users = " . $uid . ")";
			if ($debug)
				echo ("<br>checkArticle -> " . $querySpecifica);

			$db->setQuery ( $querySpecifica );
			$arrayRisultati = $db->loadObjectList ();
			if (sizeof ( $arrayRisultati ) > 0)
				$esito = true;


		} else

			if (($task == "article.edit" || $task == "article.save") && $option == "com_content" && ($view == "article"||$view=="") && $applicationName == "site") {


			$querySpecifica = "

			select
			*
			from #__djfacl_contenuti jc, #__djfacl_components c
			where (jc.id_components = c.id or jc.id_components = '999999')  " . plgSystemDjfAcl::getTaskQueryExtension () . plgSystemDjfAcl::getSiteAdminQueryExtension () . "
			and (c.`option` = '" . $option . "')
			and (jc.id_group = 0 " . plgSystemDjfAcl::getGroupIdQueryExtension () . " or jc.id_users = " . $uid . ")";
			if ($debug)
				echo ("<br>checkArticle -> " . $querySpecifica);

			$db->setQuery ( $querySpecifica );
			$arrayRisultati = $db->loadObjectList ();
			if (sizeof ( $arrayRisultati ) > 0)
				$esito = true;

		} else

			if ($applicationName == "administrator" && $option=="com_content" && ! empty ( $id )) {


			$querySpecifica = "

			select
			*
			from #__djfacl_contenuti jc, #__djfacl_components c
			where (jc.id_components = c.id or jc.id_components = '999999')  " . plgSystemDjfAcl::getTaskQueryExtension () . plgSystemDjfAcl::getSiteAdminQueryExtension () . "
			and (c.`option` = '" . $option . "')
			and (jc.id_group = 0 " . plgSystemDjfAcl::getGroupIdQueryExtension () . " or jc.id_users = " . $uid . ")";
			if ($debug)
				echo ("<br>checkArticle -> " . $querySpecifica);

			$db->setQuery ( $querySpecifica );
			$arrayRisultati = $db->loadObjectList ();
			if (sizeof ( $arrayRisultati ) > 0)
				$esito = true;
		} else

			if ($applicationName == "administrator" && $option=="com_content" && empty ( $id ) && empty ( $catid ) && $task == "save") {
			$esito = false;
		}

		else
			$esito = true;

		if ($debug)
			echo ("<br>checkArticle -> esito =  " . $esito);
		return $esito;
	}

	public function checkComponents($debug = false, $optionExt = '') {
		$esito = false;
		$mainframe =& JFactory::getApplication();
		$db = & JFactory::getDBO ();
		$app = & JFactory::getApplication ();
		$option = JRequest::getCMD ( 'option' );
		$applicationName = $app->getName ();
		$user = Jfactory::getUSER ();
		$uid = $user->id;
		$gid = $user->getAuthorisedGroups();
		$id = JRequest::getVar ( 'id' );

		$arid = explode ( ":", $id );
		$id = $arid [0];

		$view = JRequest::getVar ( 'view' );
		$task = JRequest::getCmd ( 'task' );
		$catid = JRequest::getVar ( 'catid' );
		if ($optionExt != "")
			$option = $optionExt;

		$querySpecifica = "
		select distinct
		jc.*
		from #__djfacl_contenuti jc, #__djfacl_components c
		where
		(jc.id_components = c.id or jc.id_components = '999999') " . plgSystemDjfAcl::getTaskQueryExtension () . plgSystemDjfAcl::getSiteAdminQueryExtension () . "
		and (c.`option` = '" . $option . "')
		and
		(jc.id_group = 0 " . plgSystemDjfAcl::getGroupIdQueryExtension () . "
		or jc.id_users = " . $uid . ")";

		if ($debug)
			echo ("<br>checkComponents -> " . $querySpecifica);

		$db->setQuery ( $querySpecifica );
		$arrayRisultati = $db->loadObjectList ();

		if (sizeof ( $arrayRisultati ) > 0) {
			$esito = true;
		}
		if ($option == "" || $option == "com_login" || $option == "com_user" || $gid == 25)
			$esito = true;

		if ($esito == false && $option == "com_cpanel" && $applicationName == "administrator")
			$esito = true;

		if (($task == "edit" || $task == "save") && $option == "com_content" && $view == "category" && $applicationName == "site") {
			$view = "article";
		}

		if (($task == "edit" || $task == "save") && $option == "com_content" && $view == "article" && $applicationName == "site") {
			$queryPerEditArticolo = "select jc.id as value from #__djfacl_contenuti jc, #__users ju where ju.id = " . $uid . " and jc.id_article = " . $id . " and (ju.gid=jc.id_group " . plgSystemDjfAcl::getGroupIdQueryExtension () . ")";
			if ($debug) {
				echo ($queryPerEditArticolo);
			}
			$ce = utility::getField ( $queryPerEditArticolo );

			if (! empty ( $ce ))
				$esito = true;

		}

		if ($debug)
			echo ("<br>checkComponents -> esito =  " . $esito);

		return $esito;
	}

	function check_if_table_exists($table) {
		$db = & JFactory::getDBO ();
		$query = 'select * from ' . $table;
		$db->setQuery ( $query );
		if (! $db->query ()) {
			$esito = false;
		} else {
			$esito = true;
		}
		return $esito;
	}

	public function scanAllParam() {
		$mainframe =& JFactory::getApplication();
		$stringona = "";
		$requestArray = JRequest::get ();

		$db = & JFactory::getDBO ();

		if (plgSystemDjfAcl::check_if_table_exists ( "#__djfacl_jtask" )) {

			$db->setQuery ( 'select distinct name from #__djfacl_jtask' );

			$nomi = $db->loadObjectList ();
			if (sizeof ( $nomi ) > 0)
				foreach ( $nomi as $questonome ) {
					
				foreach ( $requestArray as $key => $valore ) {

					if ($key == $questonome->name)
							
						$stringona .= plgSystemDjfAcl::getTaskQueryExtensionParam ( $questonome->name );
				}
			}
			if ($stringona != "")
				$stringona = "and (false " . $stringona . " or jc.jtask='999999')";

		}
		return $stringona;

	}
	public function getTaskQueryExtension() {
		return plgSystemDjfAcl::scanAllParam ();

	}
	public function getTaskQueryExtensionParam($param_name) {
		$mainframe =& JFactory::getApplication();

		$db = & JFactory::getDBO ();
		$app = & JFactory::getApplication ();
		$option = JRequest::getCMD ( 'option' );
		$applicationName = $app->getName ();
		$user = Jfactory::getUSER ();
		$uid = $user->id;
		$gid = $user->getAuthorisedGroups();

		$id = JRequest::getVar ( 'id' );

		$arid = explode ( ":", $id );
		$id = $arid [0];

		$view = JRequest::getVar ( 'view' );
		$task = JRequest::getVar ( $param_name );
		$catid = JRequest::getVar ( 'catid' );

		if ($gid != 25 && $task != "" && $task != null) {
			$queryTask = " or jc.jtask in (select id from #__djfacl_jtask where jtask = '$task' and name = '$param_name')  ";
		} else
			$queryTask = "";

		return $queryTask;
	}

	public function getSiteAdminQueryExtension() {
		$mainframe =& JFactory::getApplication();
		$db = & JFactory::getDBO ();
		$app = & JFactory::getApplication ();
		$option = JRequest::getCMD ( 'option' );
		$applicationName = $app->getName ();
		$user = Jfactory::getUSER ();
		$uid = $user->id;
		$gid = $user->getAuthorisedGroups();

		$id = JRequest::getVar ( 'id' );

		$arid = explode ( ":", $id );
		$id = $arid [0];

		$view = JRequest::getVar ( 'view' );
		$task = JRequest::getCmd ( 'task' );
		$catid = JRequest::getVar ( 'catid' );

		if ($applicationName == "site")
			$site_admin_reale = 1;
		else
			$site_admin_reale = 0;
		if ($gid != 25) {
			$queryTask = " and (jc.site_admin =$site_admin_reale) ";
		} else
			$queryTask = "";

		return $queryTask;
	}

	public function getGroupIdQueryExtension() {
		$mainframe =& JFactory::getApplication();
		$db = & JFactory::getDBO ();
		$app = & JFactory::getApplication ();
		$option = JRequest::getCMD ( 'option' );
		$applicationName = $app->getName ();
		$user = Jfactory::getUSER ();
		$uid = $user->id;
		$gid = $user->getAuthorisedGroups();

		$id = JRequest::getVar ( 'id' );

		$arid = explode ( ":", $id );
		$id = $arid [0];

		$view = JRequest::getVar ( 'view' );
		$task = JRequest::getCmd ( 'task' );
		$catid = JRequest::getVar ( 'catid' );

		$allgidquery = "";
		foreach ( $gid as $questoGid ) {
			$allgidquery .= " or jc.id_group = '$questoGid' ";
		}
		return $allgidquery;
	}

	/**
	 * Do load rulles and start checking function
	 */

	public function onAfterRoute() {
		$mainframe =& JFactory::getApplication();

		if (plgSystemDjfAcl::check ()) {
			return true;
		} else {
			$mainframe->redirect ( "index.php", JText::_ ( 'JERROR_ALERTNOAUTHOR' ) );
		}
	}

	public function checkTask($debug = false){

		$risultato = false;
		$db = & JFactory::getDBO ();
		$app = & JFactory::getApplication ();
		$option = JRequest::getCMD ( 'option' );
		$applicationName = $app->getName ();
		$user = Jfactory::getUSER ();
		$uid = $user->id;
		$gid = $user->getAuthorisedGroups();
		$stringaPerGruppi = plgSystemDjfAcl::getGroupIdQueryExtension ();

		$stringa = null;
		$querySpecifica = "select * from #__djfacl_contenuti jc 
		where jc.id = jc.id and (jc.id_group = 0 ".$stringaPerGruppi.")";
		if ($debug)
			echo ("<br>checkTask -> " . $querySpecifica."<br>");
		$db->setQuery ( $querySpecifica );
		$arrayRisultati = $db->loadObjectList ();
		foreach($arrayRisultati as $questoRis){
			if ($debug) echo("<hr>");
			$stringa=$questoRis->jtask;
			$primo = explode("&",$stringa);
			$params = null;
			$esito=true;
			if (sizeof($stringa)>0 && sizeof($primo)>0){
				foreach ($primo as $questo){
					$parametro = explode ("=",$questo);
					$params[$parametro[0]] = $parametro[1];
			
				}
				foreach ($params as $key => $value){
			
					$reale = Jrequest::getWord($key);
					if ($reale == $value || $value=="*"){
						$esito=$esito && true;
						if ($debug){
							echo($key." = ".$value." -> Verificato<br>");
						}
					} else{
						$esito=$esito && false;
						if ($debug) echo($key." = ".$value." -> Non Verificato<br>");
					}
					
				}
				if ($debug && $esito){
					echo("consenti -> ".$questoRis->published."<br>");
				}
			
			}
			if ($esito) $risultato = $risultato || $questoRis->published;
			if (!(($questoRis->site_admin ==1 && $applicationName == "site") ||
				($questoRis->site_admin ==0 && $applicationName == "administrator"))
				)return true;
		}
		if ($debug) echo("risultato finale = $risultato<br>");
		return $risultato;

	}

	public function check() {

		$debug = 0; // use true to debug the code
		$risultato = false;

		$db = & JFactory::getDBO ();
		$app = & JFactory::getApplication ();
		$applicationName = $app->getName ();

		$user = Jfactory::getUSER ();
		$uid = $user->id;
		$gid = $user->getAuthorisedGroups();

		$id = JRequest::getVar ( 'id' );
		$arid = explode ( ":", $id );
		$id = $arid [0];

		if ($applicationName == "administrator") {
			$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
			$id = $cid [0];
		}

		plgSystemDjfAcl::blockAllBlock ( false );

		$risultato = plgSystemDjfAcl::checkTask($debug);
		
			
		$path = JRequest::get();

		// ECCEZIONI
		if ( Jrequest::getWord("option") == "com_login" &&  (Jrequest::getWord("task")=="login"||Jrequest::getWord("task")=="logout")) $risultato=true;
		if ( Jrequest::getWord("option") == "com_content" &&  Jrequest::getWord("view")=="featured") $risultato=true;
		if ( Jrequest::getWord("option") == "com_users" && 	(Jrequest::getWord("task")=="userlogin" || Jrequest::getWord("task")=="userlogout")) $risultato=true;
		if (utility::containsGid($gid,8)) $risultato = true; // per gli utenti figli di Super Administrator
		if (utility::containsGid($gid,1) && $uid==0) $risultato = true; // per gli utenti figli di Public
		if ($applicationName == "administrator" && Jrequest::getWord("option") == "" && Jrequest::getWord("view") == "" && Jrequest::getWord("task") == "") $risultato=true;
		

		if ($debug) {
			echo ('<h1>');
			foreach ($path as $questo){
				echo("$questo</br>");
			}
			echo ('option = ' . Jrequest::getWord("option") . '<br>');
			echo ('applicationName = ' . $applicationName . '<br>');
			echo ('gruppo utente = ' . $gid[0] . '<br>');
			echo ('uid = ' . $uid . '<br>');
			echo ('view = ' . Jrequest::getWord("view"). '<br>');
			echo ('task = ' . Jrequest::getWord("task") . '<br>');
			echo ('catid = ' . Jrequest::getWord("catid") . '<br>');
			echo ('id = ' . Jrequest::getWord("id") . '</br>');
			echo ('a_id = ' . Jrequest::getWord("a_id") . '</br>');
			echo ('risultato = ' . $risultato);
			echo ('</h1>');
			exit();
		}
			
		return $risultato;
	}

}
