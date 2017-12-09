<?php
class Dotzot_Pudo_Adminhtml_PudobackendController extends Mage_Adminhtml_Controller_Action
{
	public function indexAction()
    {  
		$this->loadLayout();
		$block = $this->getLayout()->createBlock('dotzot_pudo/adminhtml_pudobackend','test_form');
		$this->getLayout()->getBlock('content')->append($block);
		$this->renderLayout();
    }

	public function newcsvAction()
    {  
		$post_data=$this->getRequest()->getPost();
		if($_FILES['csvfile']['name']){
		try {
		$uploader = new Varien_File_Uploader('csvfile');
		$uploader->setAllowedExtensions(array('csv','CSV')); // or pdf or anything
		$uploader->setAllowRenameFiles(false);
		$uploader->setFilesDispersion(false);
		$path = Mage::getBaseDir('var') . DS . 'csv';
		$uploader->save($path, $_FILES['csvfile']['name']);
		$data['csvfile'] = $_FILES['csvfile']['name'];
		$full_path = $path."/".$_FILES['csvfile']['name'];
		$csvObject = new Varien_File_Csv();
		$dataArray = $csvObject->getData($full_path);
		$model = Mage::getModel('docket/docket'); 
		foreach($dataArray as $data){   
			 $model->setData('docket_no',$data[0]);
			 $model->setData('payment_method',$data[1]);
			 $model->save(); 
			 $model->unsetData(); 
		} 
		$this->_redirect("*/*/");
		Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Docket Csv was successfully saved"));
						return;
		}catch(Exception $e) {
		Mage::getSingleton("adminhtml/session")->addError(Mage::helper("adminhtml")->__("Please Upload valid file"));
		$this->_redirect("*/*/");
		}
		}else{
		Mage::getSingleton("adminhtml/session")->addError(Mage::helper("adminhtml")->__("Please Upload valid file"));
		$this->_redirect("*/*/");
		} 
	
	
	
	
	
				
  }
     
}
