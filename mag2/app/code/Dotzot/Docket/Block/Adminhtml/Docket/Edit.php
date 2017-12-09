<?php
	
class Dotzot_Docket_Block_Adminhtml_Docket_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
		public function __construct()
		{

				parent::__construct();
				$this->_objectId = "id";
				$this->_blockGroup = "docket";
				$this->_controller = "adminhtml_docket";
				$this->_updateButton("save", "label", Mage::helper("docket")->__("Save Item"));
				$this->_updateButton("delete", "label", Mage::helper("docket")->__("Delete Item"));

				$this->_addButton("saveandcontinue", array(
					"label"     => Mage::helper("docket")->__("Save And Continue Edit"),
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
				if( Mage::registry("docket_data") && Mage::registry("docket_data")->getId() ){

				    return Mage::helper("docket")->__("Edit Item '%s'", $this->htmlEscape(Mage::registry("docket_data")->getId()));

				} 
				else{

				     return Mage::helper("docket")->__("Add Item");

				}
		}
}