<?php
class Dotzot_Docket_Model_Mysql4_Docket extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init("docket/docket", "id");
    }
}