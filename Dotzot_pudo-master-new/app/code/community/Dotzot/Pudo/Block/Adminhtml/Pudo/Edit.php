<?php
	
class Dotzot_Pudo_Block_Adminhtml_Pudo_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
		public function __construct()
		{

				parent::__construct();
				$this->_objectId = "id";
				$this->_blockGroup = "pudo";
				$this->_controller = "adminhtml_pudo";
				$this->_updateButton("save", "label", Mage::helper("pudo")->__("Save Item"));
				$this->_updateButton("delete", "label", Mage::helper("pudo")->__("Delete Item"));

				$this->_addButton("saveandcontinue", array(
					"label"     => Mage::helper("pudo")->__("Save And Continue Edit"),
					"onclick"   => "saveAndContinueEdit()",
					"class"     => "save",
				), -100);



				$this->_formScripts[] = "

							function saveAndContinueEdit(){
								editForm.submit($('edit_form').action+'back/edit/');
							}
						";
		}

		public function getHeaderText()
		{
				if( Mage::registry("pudo_data") && Mage::registry("pudo_data")->getId() ){

				    return Mage::helper("pudo")->__("Edit Item '%s'", $this->htmlEscape(Mage::registry("pudo_data")->getId()));

				} 
				else{

				     return Mage::helper("pudo")->__("Add Item");

				}
		}
}