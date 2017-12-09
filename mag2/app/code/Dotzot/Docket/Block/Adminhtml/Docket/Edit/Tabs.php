<?php
class Dotzot_Docket_Block_Adminhtml_Docket_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
		public function __construct()
		{
				parent::__construct();
				$this->setId("docket_tabs");
				$this->setDestElementId("edit_form");
				$this->setTitle(Mage::helper("docket")->__("Item Information"));
		}
		protected function _beforeToHtml()
		{
				$this->addTab("form_section", array(
				"label" => Mage::helper("docket")->__("Item Information"),
				"title" => Mage::helper("docket")->__("Item Information"),
				"content" => $this->getLayout()->createBlock("docket/adminhtml_docket_edit_tab_form")->toHtml(),
				));
				return parent::_beforeToHtml();
		}

}
