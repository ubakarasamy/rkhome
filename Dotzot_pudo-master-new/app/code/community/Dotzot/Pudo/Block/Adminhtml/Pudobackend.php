<?php  

class Dotzot_Pudo_Block_Adminhtml_Pudobackend extends Mage_Adminhtml_Block_Template {

public function __construct() {
    parent::__construct();
    $this->setTemplate('pudo/pudobackend.phtml');
    $this->setFormAction(Mage::getUrl('*/*/newcsv'));
  }

}
