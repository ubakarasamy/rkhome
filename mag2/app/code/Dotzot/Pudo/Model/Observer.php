<?php 
class Dotzot_Pudo_Model_Observer
{
    public function sendAwb($observer){
        $shipmentData = Mage::registry('current_shipment')->getData();
       
		$postData=Mage::app()->getRequest()->getPost();
		if(isset($postData['tracking']) && count($postData['tracking'])>0){
				foreach($postData['tracking'] as $tracking){
						if($tracking['carrier_code']=='pudo'){
								if(isset($tracking['number']) && $tracking['number']!='')
										$this->updateToDotzot($shipmentData['order_id'],$tracking['number']);
						}
				}
		}	
	}
 public function updateToDotzot($order,$awb,$shipment){
	 

				$data = array();
				$_order = Mage::getModel('sales/order')->load($order);  
				$data['shipping_description']='';
				if($_order->getShippingDescription() == "DTDC Pickup - Plus Pickup"){
					$data['shipping_description']='Plus';
				}else if($_order->getShippingDescription() == "DTDC Pickup - Express Pickup"){
					$data['shipping_description']='Express';
				}else{
					$data['shipping_description']='Economy';
				} 
				$data['cutomer_id'] =  $_order->getCustomerId();  
				$data['cutomer_email'] =  $_order->getCustomerEmail();   
				$data['Customer_Name'] = $_order->getCustomerFirstname()." ".$_order->getCustomerLastname();
				$shippingAddress = $_order->getShippingAddress();
				$data['Shipping_Add1'] = $shippingAddress->getStreetFull();
				$data['Shipping_City'] = $shippingAddress->getCity();
				$data['Shipping_State'] = $shippingAddress->getRegion();
				$data['Shipping_Zip'] = $shippingAddress->getPostcode();
				$data['VendorName'] = $company = $shippingAddress->getCompany();
				$data['Shipping_EmailId'] = $shippingAddress->getEmail();
		if(!isset($data['Shipping_EmailId']) || $data['Shipping_EmailId']=='' || $data['Shipping_EmailId']==NULL)
					$data['Shipping_EmailId']=$data['cutomer_email'];
				$data['Shipping_TeleNo'] = $shippingAddress->getTelephone();
				$data['Total_Amt'] = 0;
				$data['Weight']=0;
				$skuArray=array();
				$nameArray=array();
				$toalPieces=0;
                $totalAmount=0;
				$payMode='P';
				if($_order->getPayment()->getMethodInstance()->getCode()=='cashondelivery'){
					$payMode='C';
					$data['Total_Amt'] = $_order->getGrandTotal();
				}
				$payMode='C';
				$data['Total_Amt'] = $_order->getGrandTotal();
				foreach($_order->getAllItems() as $ship){
					$shipmentData=$ship->getData();
					$data['Weight'] +=$shipmentData['weight'];
					$toalPieces+= $shipmentData['qty'];
					$totalAmount+=$shipmentData['price'];
					$nameArray[]=$shipmentData['name'];
					$skuArray[]=$shipmentData['sku'];
				}
				$data['sku'] = implode(',',$skuArray); 
				$data['name'] =implode(',',$nameArray);
				 
				  
			if($_order->getPayment()->getMethodInstance()->getCode() == 'cashondelivery' && $awb[0] == 'I'){
			$data['mode'] ='C';
			$data['total_amount'] = $_order->getGrandTotal(); 
			}else{
			$data['mode'] = 'P';
			$data['total_amount'] = 0; 
			}
			$data['total_amt'] = $_order->getGrandTotal();
			$explod_company = explode('-',$company);
	 	$C = substr($company,0,2);
		if($C =='IN' && $C!=''){
			$data['isPudo']='Y'; 
			$data['TypeOfDelivery']='COLLECT';
			$data['vendor_name'] = $company;
		}else{
			$data['isPudo']='N';
			$data['TypeOfDelivery']='HOME DELIVERY';
			$data['vendor_name'] = $_order->getCustomerFirstname()." ".$_order->getCustomerLastname();
			$company == '';
		}
		
	$enable_demo = Mage::getStoreConfig('carriers/pudo/enable_demo');  
	if($enable_demo == "1"){ 
	$client = new SoapClient("http://dotzot-test.azurewebsites.net/services/InstacomCustomerServices.asmx?WSDL",array('cache_wsdl' => WSDL_CACHE_NONE,'trace'=>1));
	$dotzot_customerId ="CC000100132";
		$params['UserName']=Mage::getStoreConfig('carriers/pudo/dotzot_demo_api_username');
		$params['Password']=Mage::getStoreConfig('carriers/pudo/dotzot_demo_api_password');
		$params['ClientId']='DOTZOT'; 
	}else{ 
	$client = new SoapClient("http://instacom.dotzot.in/services/InstacomCustomerServices.asmx?WSDL",array('cache_wsdl' => WSDL_CACHE_NONE,'trace'=>1));
	 $dotzot_customerId = Mage::getStoreConfig('carriers/pudo/customer_id'); 
		$params['UserName']=Mage::getStoreConfig('carriers/pudo/dotzot_live_api_username');
		$params['Password']=Mage::getStoreConfig('carriers/pudo/dotzot_live_api_password');
		$params['ClientId']='INSTACOM';
	 
	} 
    $params['xmlBatch'] = '<NewDataSet>
    <Customer>
        <CUSTCD>'.$dotzot_customerId.'</CUSTCD>
    </Customer>
    <Docket>
        <Order_No>'.$order.'</Order_No>
        <AGENT_ID></AGENT_ID>
        <Product_Code>'.$data["sku"].'</Product_Code>
        <Item_Name>'.$data["name"].'</Item_Name>
        <AWB_No>'.$awb.'</AWB_No>
        <N0_of_Pieces>'.$toalPieces.'</N0_of_Pieces>
        <Customer_Name>'.$data["Customer_Name"].'</Customer_Name>
        <Shipping_Add1>'.$data["Shipping_Add1"].'</Shipping_Add1>
        <Shipping_Add2>'.$data["Shipping_City"].'</Shipping_Add2>
        <Shipping_City>'.$data["Shipping_City"].'</Shipping_City>
        <Shipping_State>'.$data["Shipping_State"].'</Shipping_State>
        <Shipping_Zip>'.$data["Shipping_Zip"].'</Shipping_Zip>
        <Shipping_TeleNo>'.$data["Shipping_TeleNo"].'</Shipping_TeleNo>
        <Shipping_MobileNo>'.$data["Shipping_TeleNo"].'</Shipping_MobileNo>
        <Shipping_EmailId>'.$data["cutomer_email"].'</Shipping_EmailId>
		<Total_Amt>'.$data["total_amt"].'</Total_Amt>
        <Mode>'.$data["mode"].'</Mode>
        <Collectable_amount>'.$data["total_amount"].'</Collectable_amount>
        <Weight>'.$data["Weight"].'</Weight>
        <UOM>Per KG</UOM>
        <Type_of_Service>'.$data["shipping_description"].'</Type_of_Service>
        <VendorName>'.$data["vendor_name"].'</VendorName>
        <VendorAddress1>'.$data["Shipping_Add1"].'</VendorAddress1>
        <VendorAddress2></VendorAddress2>
        <VendorPincode>'.$data["Shipping_Zip"].'</VendorPincode>
        <VendorTeleNo>'.$data["Shipping_TeleNo"].'</VendorTeleNo>
        <IsPUDO>'.$data["isPudo"].'</IsPUDO>
        <TypeOfDelivery>'.$data["TypeOfDelivery"].'</TypeOfDelivery>
        <PUDO_Id>'.$company.'</PUDO_Id>
    </Docket>
</NewDataSet>'; 
				 
				$error=0;
				try{
						$result = $client->__call('PushOrderData_PUDO', array($params));
						$doc = new DOMDocument();    
						$xml = $client->__getLastResponse();
				}
				catch (SoapFault $fault){
						$error = 1;
				}
				if($error==1){
						$xml=$fault->faultstring;
				}else{
						$xml = $result;
				}
				$xml1 = simplexml_load_string($xml->PushOrderData_PUDOResult);
				$json = json_encode($xml1);
				$array = json_decode($json,TRUE);
                $errorMessage='';
                $successMessage='';
                if(isset($array['Docket']['1'])){
                    foreach($array['Docket'] as $docket){
                        if($docket['Succeed']=='No')
                            $errorMessage.=$docket['Reason'].'<br>';
                        if($docket['Succeed']=='Yes')
                            $successMessage.='AWB '.$awb.' '.$docket['Reason'].'<br>';
                    }
                }
                else{
                    if($array['Docket']['Succeed']=='No')
                        $errorMessage.=$array['Docket']['Reason'].'<br>';
                    if($array['Docket']['Succeed']=='Yes')
                        $successMessage.='AWB '.$awb.' '.$array['Docket']['Reason'].'<br>';
                }
                if($errorMessage!='')
                    Mage::getSingleton('adminhtml/session')->addError($errorMessage);
                if($successMessage!='')
                    Mage::getSingleton('adminhtml/session')->addSuccess($successMessage);
		//}		
		 }
}
