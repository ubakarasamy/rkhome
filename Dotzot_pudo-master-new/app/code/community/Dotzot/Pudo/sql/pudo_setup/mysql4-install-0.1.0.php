<?php
$installer = $this;
$installer->startSetup();
$installer->run("-- DROP TABLE IF EXISTS {$this->getTable('pudo_shipping')};
CREATE TABLE {$this->getTable('pudo_shipping')} (
	  `id` int(11) unsigned NOT NULL auto_increment,
	  `pudo_shipping` varchar(255) NOT NULL default '', 
	  PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;
	");
	$installer->endSetup();
