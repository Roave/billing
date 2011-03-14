SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


CREATE TABLE IF NOT EXISTS `customers` (
  `customer_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company` varchar(120) NOT NULL,
  `address` varchar(120) NOT NULL,
  `address_2` varchar(120) DEFAULT NULL,
  `city` varchar(120) NOT NULL,
  `state` varchar(80) NOT NULL,
  `zip` varchar(20) NOT NULL,
  `country` varchar(40) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `fax` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`customer_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

CREATE TABLE IF NOT EXISTS `customer_products` (
  `product_id` int(10) unsigned NOT NULL,
  `customer_id` int(10) unsigned NOT NULL,
  `sku` varchar(30) DEFAULT NULL,
  `name` varchar(120) DEFAULT NULL,
  `description` text,
  `unit_price` float DEFAULT NULL COMMENT '		',
  PRIMARY KEY (`product_id`,`customer_id`),
  KEY `fk_customer_products_1` (`customer_id`),
  KEY `fk_customer_products_2` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `invoices` (
  `invoice_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` int(10) unsigned NOT NULL,
  `invoice_date` date NOT NULL,
  `due_date` date NOT NULL,
  `notes` text,
  PRIMARY KEY (`invoice_id`),
  KEY `fk_invoices_1` (`customer_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

CREATE TABLE IF NOT EXISTS `invoice_details` (
  `detail_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `invoice_id` int(10) unsigned NOT NULL,
  `sku` varchar(30) DEFAULT NULL,
  `name` varchar(120) DEFAULT NULL,
  `unit_price` float DEFAULT NULL,
  `quantity` float DEFAULT NULL,
  PRIMARY KEY (`detail_id`),
  KEY `fk_invoice_details_1` (`invoice_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;
CREATE TABLE IF NOT EXISTS `invoice_subtotals` (
`invoice_id` int(10) unsigned
,`subtotal` double
);
CREATE TABLE IF NOT EXISTS `products` (
  `product_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sku` varchar(30) DEFAULT NULL,
  `name` varchar(120) DEFAULT NULL,
  `description` text,
  `unit_price` float DEFAULT NULL,
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;
DROP TABLE IF EXISTS `invoice_subtotals`;

CREATE VIEW `invoice_subtotals` AS select `invoice_details`.`invoice_id` AS `invoice_id`,sum((`invoice_details`.`quantity` * `invoice_details`.`unit_price`)) AS `subtotal` from `invoice_details` group by `invoice_details`.`invoice_id`;


ALTER TABLE `customer_products`
  ADD CONSTRAINT `fk_customer_products_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_customer_products_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `invoices`
  ADD CONSTRAINT `fk_invoices_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `invoice_details`
  ADD CONSTRAINT `fk_invoice_details_2` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`invoice_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;