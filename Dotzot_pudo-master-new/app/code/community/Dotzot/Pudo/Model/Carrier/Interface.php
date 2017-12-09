<?php  
    interface Mage_Shipping_Model_Carrier_Interface
		{

		/**
		 * Check if carrier has shipping tracking option available
		 *
		 * @return boolean
		 */
		public function isTrackingAvailable(){
			return true;
			}

		  
		}
