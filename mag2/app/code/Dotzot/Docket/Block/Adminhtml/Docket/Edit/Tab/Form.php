<?php
class Dotzot_Docket_Block_Adminhtml_Docket_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
		protected function _prepareForm()
		{

				$form = new Varien_Data_Form();
				$this->setForm($form);
				$fieldset = $form->addFieldset("docket_form", array("legend"=>Mage::helper("docket")->__("Item information")));

								
				$fieldset->addField('docket_no', 'text', array(
				'label' => Mage::helper('docket')->__('Docket No'),
				'name' => 'docket_no', 
				));
				
				$fieldset->addField(
                'payment_method', 'select', array(
                    'label'              => 'Payment Method',
                    'name'               => 'payment_method', 
                    'values'=> array('cashondelivery'=>'Cash on delivery', 'other'=>'Other'),
                    'value'=> array(2)
                )
            );
				
						

				if (Mage::getSingleton("adminhtml/session")->getDocketData())
				{
					$form->setValues(Mage::getSingleton("adminhtml/session")->getDocketData());
					Mage::getSingleton("adminhtml/session")->setDocketData(null);
				} 
				elseif(Mage::registry("docket_data")) {
				    $form->setValues(Mage::registry("docket_data")->getData());
				}
				return parent::_prepareForm();
		}
}
