<?php
return  <<<EOT
CREATE TABLE IF NOT EXISTS `module_banking_conteyer` (
  `bid` int(11) NOT NULL AUTO_INCREMENT,
  `buid` int(11) NOT NULL,
  `utype` varchar(64) DEFAULT NULL,
  `b_datas` blob,
  `b_key` int(11) DEFAULT NULL,
  PRIMARY KEY (`bid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `module_cart` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `data` blob,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `module_order_addressing_bridge` (
  `b_id` int(11) NOT NULL AUTO_INCREMENT,
  `b_country` int(11) DEFAULT NULL,
  `b_state` int(11) DEFAULT NULL,
  `b_city` int(11) DEFAULT NULL,
  `b_community` int(11) DEFAULT NULL,
  `b_handles` blob,
  `b_addr` int(11) DEFAULT NULL,
  PRIMARY KEY (`b_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `module_order_billing` (
  `billing_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) DEFAULT NULL,
  `billing_datas` blob,
  `billing_bridge_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`billing_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `module_order_shipping` (
  `shipping_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) DEFAULT NULL,
  `shipping_datas` blob,
  `shipping_bridge_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`shipping_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `module_product_order_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `u_type` varchar(64) DEFAULT NULL,
  `u_token` varchar(128) DEFAULT NULL,
  `order_status` int(11) DEFAULT NULL,
  `banking_type` varchar(64) DEFAULT NULL,
  `shiping_id` blob,
  `order_data` datetime DEFAULT NULL,
  `order_price` decimal(9,2) DEFAULT NULL,
  `shipping_price` decimal(9,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `module_product_order_products` (
  `oid` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `product_type` varchar(128) DEFAULT NULL,
  `product_count` decimal(9,2) DEFAULT NULL,
  `product_price` decimal(9,2) DEFAULT NULL,
  PRIMARY KEY (`oid`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `module_shipping` (
  `shipping_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) DEFAULT NULL,
  `shipping_details` blob COMMENT 'Here stored answer array from shipping api like fedex or dhl',
  `shipping_status` int(11) DEFAULT NULL,
  `addr_from` int(11) DEFAULT NULL COMMENT 'ID linked with internall storage of address',
  `addr_to` int(11) DEFAULT NULL COMMENT 'ID linked with internall storage of address',
  `shipping_method` varchar(64) DEFAULT NULL COMMENT 'api name, which calculated current shipping',
  PRIMARY KEY (`shipping_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

EOT;
