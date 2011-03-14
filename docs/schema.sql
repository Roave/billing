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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `customer_products` (
  `product_id` int(10) unsigned NOT NULL,
  `customer_id` int(10) unsigned NOT NULL,
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
  UNIQUE KEY `customer_id_UNIQUE` (`customer_id`),
  KEY `fk_invoices_1` (`customer_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `invoice_details` (
  `detail_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `invoice_id` int(10) unsigned NOT NULL,
  `customer_product_id` int(10) unsigned NOT NULL,
  `name` varchar(120) DEFAULT NULL,
  `unit_price` float DEFAULT NULL,
  `quantity` float DEFAULT NULL,
  PRIMARY KEY (`detail_id`),
  KEY `fk_invoice_details_1` (`customer_product_id`),
  KEY `fk_invoice_details_2` (`invoice_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `invoice_subtotals` (
`invoice_id` int(10) unsigned
,`subtotal` double
);

CREATE TABLE IF NOT EXISTS `products` (
  `product_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(120) DEFAULT NULL,
  `description` text,
  `unit_price` float DEFAULT NULL,
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

ALTER TABLE `customer_products`
  ADD CONSTRAINT `fk_customer_products_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_customer_products_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `invoices`
  ADD CONSTRAINT `fk_invoices_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `invoice_details`
  ADD CONSTRAINT `fk_invoice_details_1` FOREIGN KEY (`customer_product_id`) REFERENCES `customer_products` (`product_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_invoice_details_2` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`invoice_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
  
CREATE VIEW VIEW `invoice_subtotals` AS
SELECT `invoice_details`.`invoice_id` AS 
  `invoice_id`,sum((`invoice_details`.`quantity` * `invoice_details`.`unit_price`)) AS `subtotal`
FROM `invoice_details`
GROUP BY `invoice_details`.`invoice_id`