<?php
return  <<<EOT
CREATE TABLE IF NOT EXISTS `attributika_links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `attr_group` int(11) DEFAULT NULL,
  `unit_group` int(11) DEFAULT NULL,
  `val_group` int(11) DEFAULT NULL,
  `lang` varchar(8) DEFAULT NULL,
  `unit_value` blob,
  `unit_image` blob,
  `order` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `attributika_subject_links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `obj_id` int(11) DEFAULT NULL,
  `obj_type` varchar(256) DEFAULT NULL,
  `attr_group` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `attributika_subjects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `attr_group` int(11) DEFAULT NULL,
  `attr_val_group` int(11) DEFAULT NULL,
  `obj_id` int(11) DEFAULT NULL,
  `obj_type` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `attributika_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `attr_group` int(11) DEFAULT NULL,
  `attr_name` varchar(128) DEFAULT NULL,
  `template_order` int(11) DEFAULT '0',
  `attr_image` blob,
  `lang` varchar(8) DEFAULT NULL,
  `is_active` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `attributika_units` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `unit_group` int(11) DEFAULT NULL,
  `t_link` int(11) DEFAULT NULL,
  `unit` varchar(256) DEFAULT NULL,
  `unit_lang` varchar(8) DEFAULT NULL,
  `unit_img` blob,
  `u_order` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `module_addressing` (
  `addr_id` int(11) NOT NULL AUTO_INCREMENT,
  `addr_type` varchar(64) DEFAULT NULL,
  `addr_shortkey` varchar(64) DEFAULT NULL,
  `addr_parent` int(11) DEFAULT '0',
  `addr_datas` blob,
  `addr_tax` decimal(9,2) DEFAULT NULL,
  `addr_price` decimal(9,2) DEFAULT NULL,
  `addr_currency` varchar(8) DEFAULT NULL,
  `addr_time` int(11) DEFAULT NULL,
  `addr_order` int(11) DEFAULT NULL,
  `addr_tel_code` varchar(16) DEFAULT NULL,
  `addr_zip` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`addr_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2941 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `std_brands` (
  `brand_id` int(11) NOT NULL AUTO_INCREMENT,
  `brand_group` int(11) DEFAULT NULL,
  `brand_title` blob,
  `brand_descr` blob,
  `brand_img` int(11) DEFAULT NULL,
  `brand_img_title` varchar(256) DEFAULT NULL,
  `brand_covers` blob,
  `brand_gallery` blob,
  `brand_attaches` blob,
  `brand_seo` int(11) DEFAULT NULL,
  `brand_slug` varchar(255) DEFAULT NULL,
  `brand_lang` varchar(8) DEFAULT NULL,
  PRIMARY KEY (`brand_id`)
) ENGINE=InnoDB AUTO_INCREMENT=84 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `std_category_product` (
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
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `std_products` (
  `product_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_group` int(11) DEFAULT NULL,
  `product_title` varchar(512) DEFAULT NULL,
  `product_descr` blob,
  `product_short_descr` blob,
  `product_image` int(11) DEFAULT NULL,
  `product_img_title` varchar(256) DEFAULT NULL,
  `product_covers` blob,
  `product_gallery` blob,
  `product_attaches` blob,
  `product_slug` varchar(256) DEFAULT NULL,
  `product_seo` int(11) DEFAULT NULL,
  `product_code` varchar(256) DEFAULT NULL,
  `product_count` decimal(16,2) DEFAULT '0.00',
  `product_instock` tinyint(4) DEFAULT '1',
  `product_price` decimal(16,2) DEFAULT NULL,
  `product_old_price` decimal(16,2) DEFAULT NULL,
  `product_lang` varchar(45) DEFAULT NULL,
  `product_isactive` tinyint(4) DEFAULT '1',
  `product_order` int(11) DEFAULT '0',
  `product_length` decimal(7,2) DEFAULT NULL,
  `product_length_unit` int(11) DEFAULT NULL,
  `product_width` decimal(7,2) DEFAULT NULL,
  `product_width_unit` int(11) DEFAULT NULL,
  `product_height` decimal(7,2) DEFAULT NULL,
  `product_height_unit` int(11) DEFAULT NULL,
  `product_weight` decimal(7,2) DEFAULT NULL,
  `product_weight_unit` int(11) DEFAULT NULL,
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=408 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `multyprice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `obj_id` int(11) DEFAULT NULL,
  `obj_type` varchar(256) DEFAULT NULL,
  `attr_group1` int(11) DEFAULT NULL,
  `attr_value1` int(11) DEFAULT NULL,
  `attr_group2` int(11) DEFAULT NULL,
  `attr_value2` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `o_count` decimal(10,2) DEFAULT NULL,
  `o_img` int(11) DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  `instock` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `module_discount` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `obj_id` int(11) DEFAULT NULL,
  `obj_type` varchar(64) DEFAULT NULL,
  `discount_type` varchar(64) DEFAULT NULL,
  `discount_value` blob,
  `discount_argument2` blob,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `product_brand_links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `obj_id` int(11) DEFAULT NULL,
  `obj_type` varchar(128) DEFAULT NULL,
  `s_link` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=182 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `product_category_links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `obj_id` int(11) DEFAULT NULL,
  `obj_type` varchar(128) DEFAULT NULL,
  `s_link` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=187 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `product_tag_links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `obj_id` int(11) DEFAULT NULL,
  `obj_type` varchar(128) DEFAULT NULL,
  `s_link` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `product_tags` (
  `tag_id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) DEFAULT NULL,
  `tag_name` varchar(256) DEFAULT NULL,
  `tag_descr` varchar(512) DEFAULT NULL,
  `tag_slug` varchar(512) DEFAULT NULL,
  `is_active` int(11) DEFAULT '1',
  `lang` varchar(8) DEFAULT NULL,
  PRIMARY KEY (`tag_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `products_to_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag_pid` int(11) DEFAULT NULL,
  `pid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `std_wishlist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `wishlist` blob,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

EOT;
