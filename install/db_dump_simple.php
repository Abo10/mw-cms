<?php
return  <<<EOT
CREATE TABLE IF NOT EXISTS `media_gallery` (
  `gallery_id` int(11) NOT NULL AUTO_INCREMENT,
  `gallery_name` varchar(256) DEFAULT NULL,
  `g_lang` varchar(8) DEFAULT NULL,
  `attachments` blob,
  `gallery_descr` varchar(512) DEFAULT NULL,
  `gallery_date` datetime DEFAULT NULL,
  PRIMARY KEY (`gallery_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `std_doc_det` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `aid` int(11) DEFAULT NULL,
  `title` varchar(256) DEFAULT NULL,
  `descr` varchar(512) DEFAULT NULL,
  `lang` varchar(8) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `std_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(8) DEFAULT NULL,
  `url` varchar(512) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=234 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `std_docs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(8) DEFAULT NULL,
  `url` varchar(512) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `std_img_det` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `aid` int(11) DEFAULT NULL,
  `title` varchar(512) DEFAULT NULL,
  `descr` varchar(512) DEFAULT NULL,
  `lang` varchar(8) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=220 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `std_attachment` (
  `id_attachment` int(11) NOT NULL AUTO_INCREMENT,
  `attachment_type` varchar(16) DEFAULT NULL,
  `d_id` int(11) DEFAULT NULL,
  `attachment_descr` varchar(256) DEFAULT NULL,
  `attachment_lang` varchar(8) DEFAULT NULL,
  PRIMARY KEY (`id_attachment`)
) ENGINE=InnoDB AUTO_INCREMENT=217 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `std_menu_elements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_id` int(11) NOT NULL,
  `menu_name` varchar(45) DEFAULT NULL,
  `menu_text` varchar(512) DEFAULT NULL,
  `menu_url` varchar(255) DEFAULT NULL,
  `menu_pid` int(11) DEFAULT NULL,
  `m_group` int(11) DEFAULT NULL,
  `sub_group` int(11) DEFAULT NULL,
  `m_order` int(11) DEFAULT NULL,
  `m_tab` tinyint(4) DEFAULT '0',
  `m_attr` int(11) DEFAULT NULL,
  `m_type` varchar(64) DEFAULT NULL,
  `m_class` varchar(256) DEFAULT NULL,
  `lang` varchar(8) DEFAULT NULL,
  `m_elem_id` int(11) DEFAULT '0',
  `jq_handle` varchar(64) DEFAULT 'null',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=395 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `std_menus` (
  `menu_id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_orient` varchar(64) DEFAULT 'horizontal',
  `menu_name` varchar(64) DEFAULT NULL,
  `menu_css` varchar(64) DEFAULT 'styles.css',
  `menu_js` varchar(64) DEFAULT 'std_actions.js',
  PRIMARY KEY (`menu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `std_users` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `salt` varchar(128) DEFAULT NULL,
  `us_login` varchar(256) DEFAULT NULL,
  `us_password` varchar(256) DEFAULT NULL,
  `us_status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '0 - not activated\n1 - activated\n2 - blocked\n3 - deleted',
  `reg_hash` varchar(256) DEFAULT NULL,
  `is_activated` tinyint(4) DEFAULT '0',
  `register_date` datetime DEFAULT NULL,
  `login_date` datetime DEFAULT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `std_users_details` (
  `dit` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `utype` varchar(128) DEFAULT NULL,
  `u_datas` blob,
  `u_token` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`dit`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `user_ext` (
  `uid` int(11) NOT NULL,
  `user_mail` blob,
  `user_name` varchar(256) DEFAULT NULL,
  `userl_name` varchar(256) DEFAULT NULL,
  `tel_code` varchar(16) DEFAULT NULL,
  `user_tel` varchar(45) DEFAULT NULL,
  `user_avatar` int(11) DEFAULT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `user_links` (
  `lid` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `u_type` varchar(64) DEFAULT NULL,
  `obj_id` int(11) DEFAULT NULL,
  `obj_type` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`lid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `users` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `salt` varchar(128) DEFAULT NULL,
  `us_login` varchar(256) DEFAULT NULL,
  `us_password` varchar(256) DEFAULT NULL,
  `us_status` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `adm_users` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `salt` varchar(128) DEFAULT NULL,
  `us_login` varchar(256) DEFAULT NULL,
  `us_password` varchar(256) DEFAULT NULL,
  `us_status` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `std_pages` (
  `page_id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) DEFAULT NULL,
  `page_slug` varchar(256) DEFAULT NULL,
  `page_title` blob,
  `page_descr` blob,
  `page_seo` int(11) DEFAULT NULL,
  `page_gallery` blob,
  `page_content` blob,
  `page_lang` varchar(8) DEFAULT NULL,
  `page_isactive` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`page_id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `std_post` (
  `post_id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) DEFAULT NULL,
  `post_title` varchar(512) DEFAULT NULL,
  `post_content` blob,
  `post_descr` varchar(512) DEFAULT NULL,
  `post_img` int(11) DEFAULT NULL,
  `post_img_title` varchar(256) DEFAULT NULL,
  `post_files` blob,
  `post_covers` blob,
  `post_gallery` blob,
  `post_seo` int(11) DEFAULT NULL,
  `post_slug` varchar(512) DEFAULT NULL,
  `post_category` int(11) DEFAULT NULL,
  `post_i_date` int(11) DEFAULT NULL,
  `post_s_date` int(11) DEFAULT NULL,
  `post_status` int(11) DEFAULT '0',
  `post_lang` varchar(8) DEFAULT NULL,
  `is_active` int(11) DEFAULT '1',
  PRIMARY KEY (`post_id`)
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `post_to_postCategory_links` (
  `pl_id` int(11) NOT NULL AUTO_INCREMENT,
  `post_cat_id` int(11) DEFAULT NULL,
  `post_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`pl_id`)
) ENGINE=InnoDB AUTO_INCREMENT=315 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `std_categories` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `cid` int(11) DEFAULT NULL,
  `category_lang` varchar(8) DEFAULT NULL,
  `category_title` varchar(256) DEFAULT NULL,
  `category_content` blob,
  `category_img` int(11) DEFAULT NULL,
  `category_img_title` varchar(512) DEFAULT NULL,
  `category_cover_gallery` blob,
  `category_gallery` blob,
  `slugs` varchar(256) DEFAULT NULL,
  `category_seo` int(11) DEFAULT NULL,
  `category_order` int(11) DEFAULT NULL,
  `category_parent` int(11) DEFAULT NULL,
  `is_active` tinyint(4) DEFAULT '0',
  `is_complated` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `std_category_post` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `cid` int(11) DEFAULT NULL,
  `category_lang` varchar(8) DEFAULT NULL,
  `category_title` varchar(256) DEFAULT NULL,
  `category_content` blob,
  `category_img` int(11) DEFAULT NULL,
  `category_img_title` varchar(512) DEFAULT NULL,
  `category_cover_gallery` blob,
  `category_gallery` blob,
  `slugs` varchar(256) DEFAULT NULL,
  `category_seo` int(11) DEFAULT NULL,
  `category_order` int(11) DEFAULT NULL,
  `category_parent` int(11) DEFAULT NULL,
  `is_active` tinyint(4) DEFAULT '0',
  `is_complated` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `std_sliders` (
  `slider_id` int(11) NOT NULL AUTO_INCREMENT,
  `slider_name` varchar(128) DEFAULT NULL,
  `is_active` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`slider_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `pageprop_temp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `short_key` varchar(128) DEFAULT NULL,
  `content` blob,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `attr_link_languaged` (
  `l_id` int(11) NOT NULL AUTO_INCREMENT,
  `template_id` int(11) DEFAULT NULL,
  `l_group` int(11) DEFAULT NULL,
  `shortkey` varchar(45) DEFAULT NULL,
  `obj_id` int(11) DEFAULT NULL,
  `obj_type` varchar(128) DEFAULT NULL,
  `attr_values` blob,
  `attr_lang` varchar(8) DEFAULT NULL,
  PRIMARY KEY (`l_id`)
) ENGINE=InnoDB AUTO_INCREMENT=76 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `attr_links` (
  `l_id` int(11) NOT NULL AUTO_INCREMENT,
  `template_id` int(11) DEFAULT NULL,
  `shortkey` varchar(45) DEFAULT NULL,
  `obj_id` int(11) DEFAULT NULL,
  `obj_type` varchar(128) DEFAULT NULL,
  `attr_values` blob,
  PRIMARY KEY (`l_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `attr_templates` (
  `attr_id` int(11) NOT NULL AUTO_INCREMENT,
  `shortkey` varchar(45) DEFAULT NULL,
  `attr` blob,
  PRIMARY KEY (`attr_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `attr_templates_links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `obj_id` int(11) DEFAULT NULL,
  `obj_type` varchar(128) DEFAULT NULL,
  `s_link` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `map_link` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lat` double DEFAULT NULL,
  `lng` double DEFAULT NULL,
  `img_id` int(11) DEFAULT NULL,
  `map_title` blob,
  `obj_id` int(11) DEFAULT NULL,
  `obj_type` varchar(128) DEFAULT NULL,
  `args` blob,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `er_h` (
  `er_id` int(11) NOT NULL AUTO_INCREMENT,
  `er_file` varchar(512) DEFAULT NULL,
  `er_line` int(11) DEFAULT NULL,
  `er_class` varchar(256) DEFAULT NULL,
  `er_function` varchar(256) DEFAULT NULL,
  `er_argv` blob,
  `er_date` datetime DEFAULT NULL,
  `er_shortkey` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`er_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1258 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `std_redirects` (
  `redirect_id` int(11) NOT NULL AUTO_INCREMENT,
  `redirect_type` varchar(128) DEFAULT NULL,
  `redirect_type_id` int(11) DEFAULT NULL,
  `old_slug` blob,
  `new_slug` blob,
  PRIMARY KEY (`redirect_id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `std_seo` (
  `seo_id` int(11) NOT NULL AUTO_INCREMENT,
  `seo_title` varchar(256) DEFAULT NULL,
  `seo_descr` varchar(256) DEFAULT NULL,
  `seo_keywords` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`seo_id`)
) ENGINE=InnoDB AUTO_INCREMENT=870 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `std_tags` (
  `tag_id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) DEFAULT NULL,
  `tag_name` varchar(256) DEFAULT NULL,
  `tag_descr` varchar(512) DEFAULT NULL,
  `tag_slug` varchar(512) DEFAULT NULL,
  `is_active` int(11) DEFAULT '1',
  `lang` varchar(8) DEFAULT NULL,
  PRIMARY KEY (`tag_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `tags_to_post` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag_pid` int(11) DEFAULT NULL,
  `post_pid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `sliders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `s_group` int(11) DEFAULT NULL,
  `base_img` int(11) DEFAULT NULL,
  `ext_img` int(11) DEFAULT NULL,
  `s_url` blob,
  `s_order` int(11) DEFAULT NULL,
  `is_active` tinyint(4) DEFAULT '1',
  `s_lang` varchar(8) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=211 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `std_sitemap_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `last_update` int(11) DEFAULT NULL,
  `data` blob,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8;


EOT;
