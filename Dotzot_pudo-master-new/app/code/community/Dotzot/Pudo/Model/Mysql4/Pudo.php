<?php
class Dotzot_Pudo_Model_Mysql4_Pudo extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init("pudo/pudo", "id");
    }
}