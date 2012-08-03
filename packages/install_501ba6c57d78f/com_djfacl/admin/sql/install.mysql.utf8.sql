CREATE TABLE  `#__djfacl_contenuti`(
  `id` int(10) unsigned  NOT NULL auto_increment,
  `id_group` int(10) unsigned NOT NULL,  
  `id_modules` int(10) unsigned default NULL,
  `site_admin` int(1) unsigned default 1,
  `jtask` varchar(255) default NULL,
  `css_block` varchar(255) default NULL,
  `published` tinyint(1) NOT NULL default '0',  
  `checked_out` int(11) NOT NULL default '0',
  `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `ordering` int(11) NOT NULL default '0',
  

  PRIMARY KEY  (`id`),
  KEY `contenuti_utenti_FKIndex1` (`id_group`)
) ENGINE=MyISAM;


CREATE TABLE IF NOT EXISTS `#__djfacl_quickicon` (
`id` int(11) NOT NULL auto_increment,
`text` varchar(64) NOT NULL default '',
`target` varchar(255) ,
`icon` varchar(255) ,
`ordering` int(10) unsigned ,
`published` tinyint(1) unsigned ,
`title` varchar(64) ,
`checked_out` int(11) ,
`checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
PRIMARY KEY (`id`)
) ENGINE=MyISAM;

INSERT INTO `#__djfacl_quickicon` (`id`,`text`,`target`,`icon`,`ordering`,`published`,`title`,`checked_out`,`checked_out_time`) VALUES 
 (1,'Djf ACL','index.php?option=com_djfacl','/administrator/components/com_djfacl/assets/images/header/icon-48-djfacl.png',1,1,'',0,'0000-00-00 00:00:00'),
 (2,'New Article','index.php?option=com_content&task=add','/administrator/templates/bluestork/images/header/icon-48-article-add.png',2,0,'',0,'0000-00-00 00:00:00'),
 (3,'Article','index.php?option=com_content','/administrator/templates/bluestork/images/header/icon-48-article.png',3,0,'',0,'0000-00-00 00:00:00'),
 (4,'Media','index.php?option=com_media','/administrator/templates/bluestork/images/header/icon-48-media.png',8,0,'',0,'0000-00-00 00:00:00'),
 (5,'Categorie','index.php?option=com_categories&section=com_content','/administrator/templates/bluestork/images/header/icon-48-category.png',6,0,'',0,'0000-00-00 00:00:00'),
 (6,'Voci di menu','index.php?option=com_menus','/administrator/templates/bluestork/images/header/icon-48-menumgr.png',7,0,'',0,'0000-00-00 00:00:00'),
 (7,'Lingue','index.php?option=com_languages&client=0','/administrator/templates/bluestork/images/header/icon-48-language.png',9,0,'',0,'0000-00-00 00:00:00'),
 (8,'Users','index.php?option=com_users','/administrator/templates/bluestork/images/header/icon-48-user.png',10,0,'',0,'0000-00-00 00:00:00'),
 (9,'Configurazione globale','index.php?option=com_config','/administrator/templates/bluestork/images/header/icon-48-config.png',11,0,'',0,'0000-00-00 00:00:00');


CREATE TABLE IF NOT EXISTS `#__djfacl_components` (
`id` int(11) NOT NULL auto_increment,
`option` varchar(64) NOT NULL default '',
PRIMARY KEY (`id`)
) ENGINE=MyISAM;


 
CREATE TABLE  `#__djfacl_cssblock`(
  `id` int(10) unsigned  NOT NULL auto_increment,
  `css_block` varchar(255) default NULL,
  `published` tinyint(1) NOT NULL default '0',  
  `checked_out` int(11) NOT NULL default '0',
  `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `ordering` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM;

CREATE TABLE  `#__djfacl_jtask`(
  `id` int(10) unsigned  NOT NULL auto_increment,
  `name` varchar(255) default NULL,
  `jtask` varchar(255) default NULL,
  `published` tinyint(1) NOT NULL default '0',  
  `checked_out` int(11) NOT NULL default '0',
  `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `ordering` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM;

 CREATE TABLE  `#__djfacl_gruppi_utenti`(
  `id` int(10) unsigned  NOT NULL auto_increment,
  `idgroup` int(10) unsigned NOT NULL,
  `iduser` int(10) unsigned NOT NULL,  
  `typology` varchar(255) default NULL,
  `checked_out` int(11) NOT NULL default '0',
  `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `ordering` int(11) NOT NULL default '0',

  PRIMARY KEY  (`id`),
  KEY `componenti_utenti_FKIndex1` (`iduser`,`idgroup`,`typology`)
) ENGINE=MyISAM;
 

 CREATE TABLE  `#__djfacl_gruppi_icone`(
  `id` int(10) unsigned  NOT NULL auto_increment,
  `idgroup` int(10) unsigned NOT NULL,
  `idicon` int(10) unsigned NOT NULL,  
  `checked_out` int(11) NOT NULL default '0',
  `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `ordering` int(11) NOT NULL default '0',

  PRIMARY KEY  (`id`),
  KEY `componenti_utenti_FKIndex1` (`idgroup`)
) ENGINE=MyISAM;
 

 INSERT INTO #__djfacl_gruppi_utenti (id, idgroup, iduser, typology)
 
 select 0, group_id, user_id, "joomla" from #__user_usergroup_map c  where !exists 	(select 1 FROM #__users a, #__djfacl_gruppi_utenti b where a.id = b.iduser  and c.user_id = a.id and b.typology="joomla");
 
 
 
   
 INSERT INTO `#__djfacl_cssblock` (`id`,`css_block`,`published`,`checked_out`,`checked_out_time`,`ordering`) VALUES 
 (1,'hasTip',0,0,'0000-00-00 00:00:00',0),
 (2,'toolbar-new',0,0,'0000-00-00 00:00:00',0),
 (3,'toolbar-publish',0,0,'0000-00-00 00:00:00',0),
 (4,'toolbar-unpublish',0,0,'0000-00-00 00:00:00',0),
 (1000,'hasTipDjf',0,0,'0000-00-00 00:00:00',0);
 
 INSERT INTO `#__djfacl_jtask` (`id`,`name`,`jtask`,`published`,`checked_out`,`checked_out_time`,`ordering`) VALUES 
 (1,'task','edit',0,0,'0000-00-00 00:00:00',0),
 (2,'task','save',0,0,'0000-00-00 00:00:00',0),
 (3,'task','new',0,0,'0000-00-00 00:00:00',0),
 (4,'task','cancel',0,0,'0000-00-00 00:00:00',0);	

 update `#__extensions` set enabled = 0 where name = "mod_quickicon";
 update `#__extensions` set enabled = 1 where name = "mod_djfacl_quickicon";
 update `#__modules` set position = "icon", published=1, access=3 where module = "mod_djfacl_quickicon";
 update `#__modules` set published=0 where module = "mod_quickicon";
 