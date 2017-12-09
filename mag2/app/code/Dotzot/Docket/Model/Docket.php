<?php

class Dotzot_Docket_Model_Docket extends Mage_Core_Model_Abstract
{
    protected function _construct(){

       $this->_init("docket/docket");

    }
    public function getPaymentMethod() {
//print_r($this->getCollection());
        //~ $statesArray = array();
        //~ foreach($this->getCollection() as $state){
            //~ $statesArray[$state->getId()] = $state->getPaymentMethod();
//~ 
        //~ }
        //~ return $statesArray;

    } 

}
	 
