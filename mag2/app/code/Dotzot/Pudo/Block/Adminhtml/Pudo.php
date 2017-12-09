<?php


class Dotzot_Pudo_Block_Adminhtml_Pudo extends Mage_Adminhtml_Block_Widget_Grid_Container{

	public function __construct()
	{

	$this->_controller = "adminhtml_pudo";
	$this->_blockGroup = "pudo";
	$this->_headerText = Mage::helper("pudo")->__("Pudo Manager");
	$this->_addButtonLabel = Mage::helper("pudo")->__("Add New Item");
	parent::__construct();
	
	}

}