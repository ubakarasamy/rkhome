<?php


class Dotzot_Docket_Block_Adminhtml_Docket extends Mage_Adminhtml_Block_Widget_Grid_Container{

	public function __construct()
	{

	$this->_controller = "adminhtml_docket";
	$this->_blockGroup = "docket";
	$this->_headerText = Mage::helper("docket")->__("Docket Manager");
	$this->_addButtonLabel = Mage::helper("docket")->__("Add New Item");
	$this->_addButton('sample', array(
			'label'   => Mage::helper('docket')->__('Download Sample CSV'),
			'onclick' => "setLocation('".Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB)."pudo/index/samplecsv')", 
			)); 
	parent::__construct();
	
	}

}
