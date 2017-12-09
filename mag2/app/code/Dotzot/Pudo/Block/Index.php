<?php   
class Dotzot_Pudo_Block_Index extends Mage_Core_Block_Template{   


 public function indexAction()
    {
      $this->loadLayout();
      $this->renderLayout();
    }
    public function somethingAction()
    {
      echo 'test mamethode';
    }


}
