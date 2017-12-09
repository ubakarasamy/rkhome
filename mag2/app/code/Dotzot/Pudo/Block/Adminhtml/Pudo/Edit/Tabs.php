<?php
class Dotzot_Pudo_Block_Adminhtml_Pudo_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
		public function __construct()
		{
				parent::__construct();
				$this->setId("pudo_tabs");
				$this->setDestElementId("edit_form");
				$this->setTitle(Mage::helper("pudo")->__("Item Information"));
		}
		protected function _beforeToHtml()
		{
				$this->addTab("form_section", array(
				"label" => Mage::helper("pudo")->__("Item Information"),
				"title" => Mage::helper("pudo")->__("Item Information"),
				"content" => $this->getLayout()->createBlock("pudo/adminhtml_pudo_edit_tab_form")->toHtml(),
				));
				return parent::_beforeToHtml();
		}

}
