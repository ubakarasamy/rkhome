<?php

class Dotzot_Docket_Adminhtml_DocketController extends Mage_Adminhtml_Controller_Action
{
		protected function _initAction()
		{
				$this->loadLayout()->_setActiveMenu("docket/docket")->_addBreadcrumb(Mage::helper("adminhtml")->__("Docket  Manager"),Mage::helper("adminhtml")->__("Docket Manager"));
				return $this;
		}
		public function indexAction() 
		{
			    $this->_title($this->__("Docket"));
			    $this->_title($this->__("Manager Docket"));

				$this->_initAction();
				$this->renderLayout();
		}
		public function editAction()
		{			    
			    $this->_title($this->__("Docket"));
				$this->_title($this->__("Docket"));
			    $this->_title($this->__("Edit Item"));
				
				$id = $this->getRequest()->getParam("id");
				$model = Mage::getModel("docket/docket")->load($id);
				if ($model->getId()) {
					Mage::register("docket_data", $model);
					$this->loadLayout();
					$this->_setActiveMenu("docket/docket");
					$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Docket Manager"), Mage::helper("adminhtml")->__("Docket Manager"));
					$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Docket Description"), Mage::helper("adminhtml")->__("Docket Description"));
					$this->getLayout()->getBlock("head")->setCanLoadExtJs(true);
					$this->_addContent($this->getLayout()->createBlock("docket/adminhtml_docket_edit"))->_addLeft($this->getLayout()->createBlock("docket/adminhtml_docket_edit_tabs"));
					$this->renderLayout();
				} 
				else {
			 Mage::getSingleton("adminhtml/session")->addError(Mage::helper("docket")->__("Item does not exist."));
					$this->_redirect("*/*/");
				}
		}

	public function newAction()
	{ 
		$this->_title($this->__("Docket"));
		$this->_title($this->__("Docket"));
		$this->_title($this->__("New Item")); 
        $id   = $this->getRequest()->getParam("id");
		$model  = Mage::getModel("docket/docket")->load($id); 
		$data = Mage::getSingleton("adminhtml/session")->getFormData(true);
		if (!empty($data)) {
			$model->setData($data);
		}

		Mage::register("docket_data", $model); 
		$this->loadLayout();
		$this->_setActiveMenu("docket/docket"); 
		$this->getLayout()->getBlock("head")->setCanLoadExtJs(true); 
		$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Docket Manager"), Mage::helper("adminhtml")->__("Docket Manager"));
		$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Docket Description"), Mage::helper("adminhtml")->__("Docket Description"));
		$this->_addContent($this->getLayout()->createBlock("docket/adminhtml_docket_edit"))->_addLeft($this->getLayout()->createBlock("docket/adminhtml_docket_edit_tabs"));

		$this->renderLayout();

	} 
	public function saveAction()
	{ 

			$post_data=$this->getRequest()->getPost(); 
			
			//$post_data_payment=$this->getRequest()->getPost();
				if ($post_data) { 
					try { 
				 //save image
		try{ 
if((bool)$post_data['pudo_docket']['delete']==1) { $post_data['pudo_docket']=''; }
     else { 
	 unset($post_data['pudo_docket']); 
	 if (isset($_FILES)){ 
		if ($_FILES['pudo_docket']['name']) { 
			if($this->getRequest()->getParam("id")){
				$model = Mage::getModel("docket/docket")->load($this->getRequest()->getParam("id"));
				if($model->getData('pudo_docket')){
						$io = new Varien_Io_File();
						$io->rm(Mage::getBaseDir('media').DS.implode(DS,explode('/',$model->getData('pudo_docket'))));	
				}
			}
						$path = Mage::getBaseDir('media') . DS . 'docket' . DS .'docket'.DS;
						$uploader = new Varien_File_Uploader('pudo_docket');
						$uploader->setAllowedExtensions(array('jpg','png','gif'));
						$uploader->setAllowRenameFiles(false);
						$uploader->setFilesDispersion(false);
						$destFile = $path.$_FILES['pudo_docket']['name'];
						$filename = $uploader->getNewFileName($destFile);
						$uploader->save($path, $filename);

						$post_data['pudo_docket']='docket/docket/'.$filename;
		}
    }
}

        } catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
				return;
        }
//save image
 
						$model = Mage::getModel("docket/docket")
						->addData($post_data)
						->setId($this->getRequest()->getParam("id"))
						->save();

						Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Docket was successfully saved"));
						Mage::getSingleton("adminhtml/session")->setDocketData(false);

						if ($this->getRequest()->getParam("back")) {
							$this->_redirect("*/*/edit", array("id" => $model->getId()));
							return;
						}
						$this->_redirect("*/*/");
						//Mage::getSingleton('adminhtml/session')->addSuccess("CSV is imported sucessfully.");
						return;
					} 
					catch (Exception $e) {
						Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
						Mage::getSingleton("adminhtml/session")->setDocketData($this->getRequest()->getPost());
						$this->_redirect("*/*/edit", array("id" => $this->getRequest()->getParam("id")));
					return;
					}

				}
				$this->_redirect("*/*/");
		}



		public function deleteAction()
		{
				if( $this->getRequest()->getParam("id") > 0 ) {
					try {
						$model = Mage::getModel("docket/docket");
						$model->setId($this->getRequest()->getParam("id"))->delete();
						Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Item was successfully deleted"));
						$this->_redirect("*/*/");
					} 
					catch (Exception $e) {
						Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
						$this->_redirect("*/*/edit", array("id" => $this->getRequest()->getParam("id")));
					}
				}
				$this->_redirect("*/*/");
		}

		
		public function massRemoveAction()
		{
			try {
				$ids = $this->getRequest()->getPost('ids', array());
				foreach ($ids as $id) {
                      $model = Mage::getModel("docket/docket");
					  $model->setId($id)->delete();
				}
				Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Item(s) was successfully removed"));
			}
			catch (Exception $e) {
				Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
			}
			$this->_redirect('*/*/');
		}
			
		/**
		 * Export order grid to CSV format
		 */
		public function exportCsvAction()
		{
			$fileName   = 'docket.csv';
			$grid       = $this->getLayout()->createBlock('docket/adminhtml_docket_grid');
			$this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
		} 
		/**
		 *  Export order grid to Excel XML format
		 */
		public function exportExcelAction()
		{
			$fileName   = 'docket.xml';
			$grid       = $this->getLayout()->createBlock('docket/adminhtml_docket_grid');
			$this->_prepareDownloadResponse($fileName, $grid->getExcelFile($fileName));
		}
}
