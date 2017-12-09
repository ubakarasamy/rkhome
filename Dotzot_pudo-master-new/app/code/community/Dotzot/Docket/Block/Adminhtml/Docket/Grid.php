<?php

class Dotzot_Docket_Block_Adminhtml_Docket_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

		public function __construct()
		{     
				parent::__construct();
				$this->setId("docketGrid");
				$this->setDefaultSort("id");
				$this->setDefaultDir("DESC");
				$this->setSaveParametersInSession(true);
		}
		 
		protected function _prepareCollection()
		{
				$collection = Mage::getModel("docket/docket")->getCollection();
				$this->setCollection($collection);
				return parent::_prepareCollection();
		}
		 protected function _getAttributeOptions($attribute_code)
        {
            $options = array('cashondelivery'=>'Cash On Delivery','other'=>'Other');
            return $options;
        }
		protected function _prepareColumns()
		{
				$this->addColumn("id", array(
				"header" => Mage::helper("docket")->__("ID"),
				"align" =>"right",
				"width" => "50px",
			    "type" => "number",
				"index" => "id",
				));
				$this->addColumn("docket_no", array(
				"header" => Mage::helper("docket")->__("Docket No"),
				"align" =>"right",
				"width" => "50px", 
				"index" => "docket_no",
				));
				$this->addColumn("payment_method", array(
				"header" => Mage::helper("docket")->__("Payment Method"),
				"align" =>"right",
				"width" => "50px", 
				"index" => "payment_method",
            
				));
                
			$this->addExportType('*/*/exportCsv', Mage::helper('sales')->__('CSV')); 
			$this->addExportType('*/*/exportExcel', Mage::helper('sales')->__('Excel'));

				return parent::_prepareColumns();
		}

		public function getRowUrl($row)
		{
			   return $this->getUrl("*/*/edit", array("id" => $row->getId()));
		}


		
		protected function _prepareMassaction()
		{
			$this->setMassactionIdField('id');
			$this->getMassactionBlock()->setFormFieldName('ids');
			$this->getMassactionBlock()->setUseSelectAll(true);
			$this->getMassactionBlock()->addItem('remove_docket', array(
					 'label'=> Mage::helper('docket')->__('Remove Docket'),
					 'url'  => $this->getUrl('*/adminhtml_docket/massRemove'),
					 'confirm' => Mage::helper('docket')->__('Are you sure?')
				));
			return $this;
		}
			

}
