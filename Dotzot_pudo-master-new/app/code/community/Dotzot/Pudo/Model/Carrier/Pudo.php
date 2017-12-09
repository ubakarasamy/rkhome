<?php  
    class Dotzot_Pudo_Model_Carrier_Pudo     
		extends Mage_Shipping_Model_Carrier_Abstract
		implements Mage_Shipping_Model_Carrier_Interface
	{  
        protected $_code = 'pudo';  
     
       public function collectRates(Mage_Shipping_Model_Rate_Request $request)
    {
        if (Mage::getStoreConfig('carriers/pudo/status') == "off") {
            return false;
        } 
        $result = Mage::getModel('shipping/rate_result');
        $minAmount = "";
        $maxAmount = "";
        if(($request->getBaseSubtotalInclTax() >= $minAmount && $request->getBaseSubtotalInclTax() <= $maxAmount) || (!$minAmount && !$maxAmount) || (!$minAmount && $maxAmount && $request->getBaseSubtotalInclTax() <= $maxAmount) || ($minAmount && !$maxAmount && $request->getBaseSubtotalInclTax() >= $minAmount) )
        {
            $show = true;
        } else {
            $show = false;
        }

        if($show){ 

            if(Mage::getStoreConfig('carriers/'.$this->_code.'/three_day'))
            {
            $method2 = Mage::getModel('shipping/rate_result_method');
            $method2->setCarrier($this->_code);
            $method2->setMethod($this->_code.'_three');
            $method2->setCarrierTitle($this->getConfigData('title'));
            $method2->setMethodTitle($this->getConfigData('three_day_title'));
            $method2->setPrice(Mage::getStoreConfig('carriers/'.$this->_code.'/three_day_price'));
            $method2->setCost(Mage::getStoreConfig('carriers/'.$this->_code.'/three_day_price'));
            $result->append($method2);
            } 
            if(Mage::getStoreConfig('carriers/'.$this->_code.'/next_day'))
            {
                $method = Mage::getModel('shipping/rate_result_method');
                $method->setCarrier($this->_code);
                $method->setMethod($this->_code.'_next');
                $method->setCarrierTitle($this->getConfigData('title'));
                $method->setMethodTitle($this->getConfigData('next_day_title'));
                $method->setPrice(Mage::getStoreConfig('carriers/'.$this->_code.'/next_day_price'));
                $method->setCost(Mage::getStoreConfig('carriers/'.$this->_code.'/next_day_price'));
                $result->append($method);
            } 
            if(Mage::getStoreConfig('carriers/'.$this->_code.'/plus_service'))
            {
                $method = Mage::getModel('shipping/rate_result_method');
                $method->setCarrier($this->_code);
                $method->setMethod($this->_code.'_plus_service');
                $method->setCarrierTitle($this->getConfigData('title'));
                $method->setMethodTitle($this->getConfigData('plus_service_title'));
                $method->setPrice(Mage::getStoreConfig('carriers/'.$this->_code.'/plus_service_price'));
                $method->setCost(Mage::getStoreConfig('carriers/'.$this->_code.'/plus_service_price'));
                $result->append($method);
            } 
        }else{
            $error = Mage::getModel('shipping/rate_result_error');
            $error->setCarrier($this->_code);
            $error->setCarrierTitle($this->getConfigData('title'));
            $error->setErrorMessage($this->getConfigData('errormsg'));
            $result->append($error);

        }

        return $result;
    }
		/**
		 * Get allowed shipping methods
		 *
		 * @return array
		 */
		public function getAllowedMethods()
		{
			return array($this->_code=>$this->getConfigData('name'));
		}
		public function isTrackingAvailable(){
			return true;
		}
    }  
