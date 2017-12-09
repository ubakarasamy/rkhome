<?php
class Dotzot_Pudo_Block_Adminhtml_Pudo_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
		protected function _prepareForm()
		{

				$form = new Varien_Data_Form();
				$this->setForm($form);
				$fieldset = $form->addFieldset("pudo_form", array("legend"=>Mage::helper("pudo")->__("Item information")));

				
						$fieldset->addField("shipping_method", "text", array(
						"label" => Mage::helper("pudo")->__("shipping_method"),
						"name" => "shipping_method",
						));
						
						
						 
					

				if (Mage::getSingleton("adminhtml/session")->getPudoData())
				{
					$form->setValues(Mage::getSingleton("adminhtml/session")->getPudoData());
					Mage::getSingleton("adminhtml/session")->setPudoData(null);
				} 
				elseif(Mage::registry("pudo_data")) {
				    $form->setValues(Mage::registry("pudo_data")->getData());
				}
				return parent::_prepareForm();
		}
}
