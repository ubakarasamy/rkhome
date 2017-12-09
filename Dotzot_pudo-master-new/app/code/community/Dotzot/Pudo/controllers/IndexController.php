<?php
class Dotzot_Pudo_IndexController extends Mage_Core_Controller_Front_Action{

public function IndexAction() { 
	$this->loadLayout();   
	$this->getLayout()->getBlock("head")->setTitle($this->__("Pudo"));
	$breadcrumbs = $this->getLayout()->getBlock("breadcrumbs");
	$breadcrumbs->addCrumb("home", array(
	"label" => $this->__("Home Page"),
	"title" => $this->__("Home Page"),
	"link"  => Mage::getBaseUrl()
	));

	$breadcrumbs->addCrumb("pudo", array(
	"label" => $this->__("Pudo"),
	"title" => $this->__("Pudo")
	));
	$this->renderLayout(); 
}
 
public function getlistAction() {  
	$address = $this->getRequest()->getParam('query');
	$latitude = $this->getRequest()->getParam('latitude');
	$longitude = $this->getRequest()->getParam('longitude');
	$enable_demo = Mage::getStoreConfig('carriers/pudo/enable_demo');
	$api_username = Mage::getStoreConfig('carriers/pudo/api_username');
	$api_password = Mage::getStoreConfig('carriers/pudo/api_password');
	
	$url = 'http://pickup.dtdc.com/pudosug/getDTDCPudoLocationslstmap/MUMBAI/110007/Mumbai/IND/0011/'.$latitude.'/'.$longitude.'/list';
	
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_TIMEOUT, 5);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
	try { 
	$response =  curl_exec($ch);
	curl_close($ch);

	} catch (Exception $e)
	{

		echo $e->getMessage();
	}
	$arr = json_decode($response, TRUE); 
	$itemsArray = $arr['RESPONSE']['PUDO_ITEMS'];
	//echo "<pre>";print_r($itemsArray['PUDO_ITEM']);die;
	if(!empty($itemsArray['PUDO_ITEM'])){
	$i=0;  
	$address = trim($location["ADDRESS1"]).','.$location["CITY"].' '.$location["ZIPCODE"].', india'; 
	$html .='<div id="googleMap"></div>';
	$html .='<div style="float: right"><ul class="pickup_locations" style="hight:50px">'; 
	foreach($itemsArray['PUDO_ITEM'] as $location):
	$latlong = $location["LATITUDE"].",".$location["LONGITUDE"];
	$html .='<li><div class="agent_item" id="agent_'.$location["PUDO_ID"].'">'; 		
	$html .='<input type="radio"   name="agent_checkbox" class="agent_checkbox"  value="agent_'.$location["NAME"].'">&nbsp;<strong>'.$location["NAME"].'</strong>'; 
	$html .='<div class="address">'.$location["ADDRESS1"].', '.$location["ADDRESS2"].'</div>';
	$html .='<div class="agent_address" style="display: none">'.$location["ADDRESS1"].', '.$location["ADDRESS2"].','.$location["REGION"].'</div>
	<div class="agent_name" style="display: none">'.$location["NAME"].'</div>
	<div class="agent_state" style="display: none">'.$location["REGION"].'</div>
	<div class="agent_city" style="display: none">'.$location["CITY"].'</div>
	<div class="agent_postcode" style="display: none">'.$location["ZIPCODE"].'</div>
	<div class="agent_latlong" style="display: none">'.$latlong.'</div>
	<div class="agent_id" style="display: none">'.$location["PUDO_ID"].'</div>
	<div class="agent_timing" style="display: none">
	<div id="agent_detail_tbl" style="display:none;">
	<table cellspacing="3" cellpadding="3" border="1" width="440" style="font-weight: normal;font-size:14px">
	<tbody><tr><td width="90" rowspan="3" style="text-align:center">
	<img alt="DTDC Pickup"
	 style="margin: 7px auto 0;width: 65px;" src="'.Mage::getStoreConfig(Mage_Core_Model_Url::XML_PATH_SECURE_URL).'skin/frontend/base/default/pudo/images/location.png" class="parcelshoplogo bubble">
	</td>
	<td><strong><span>'.$location["NAME"].'</span></strong></td></tr><tr><td><span>'.$location["ADDRESS1"].'</span></td>
	</tr><tr><td><span>'.$location["ZIPCODE"].','.$location["CITY"].'</span></td></tr></tbody></table></div>';
	$html .='<table width="440" border="1" style="text-align:center">'; 
	
	
	$location_in_out = $location["OPENING_HOURS_ITEMS"]["OPENING_HOURS_ITEM"];
	$j=0;
	$html .='<tr><td><span>Monday-Saturday</span></td>';
	foreach($location_in_out as $A_time){
	if($j == "2"){
		$html .='<td id="secTD"><span>'.$A_time["START_TM"]." - ".$A_time["END_TM"].'</span></td>';
	}
	if($j=="1" ){ 
		$html .='<td id="firstTD"><span>'.$A_time["START_TM"]." - ".$A_time["END_TM"].'</span></td>';
	}
	$j++; 
	} 
	$html .='</tr>';
	$html .='<tr><td><span>Sunday</span></td><td><span>Closed</span></td><td><span>Closed</span></td>
	</tr></table>';
	$html .='</li>';
	endforeach; 
	$html .='</ul></div>';
	
	}else{
	echo "<p style='min-height:400px;color:red'>We are unable to provide service for this search criteria</p>";
	}
	echo $html;
	} 
	
	
	public function pickuplistAction() {
		$this->loadLayout();
		$this->renderLayout();
	}
	public function getmodeAction() { 
		$mode = $this->getRequest()->getParam('mode');
		if($mode == "dpd"){  
		$method  = Mage::getModel('shipping/rate_result_method');
		} 
	}
	public function ajaxAction() {
		$this->loadLayout();
		$this->renderLayout();
	}
	
	
	public function trackingAction(){  
		$this->loadLayout();    
        $block = $this->getLayout()->createBlock('Mage_Core_Block_Template','pudo',array('template' => 'pudo/customer/track.phtml'));
 		$this->getLayout()->getBlock('root')->setTemplate('page/1column.phtml');
        $this->getLayout()->getBlock('content')->append($block);
        $this->_initLayoutMessages('core/session'); 
        $this->renderLayout(); 
	}
	
	
	public function trackAction()
    {   
		  $enable_demo = Mage::getStoreConfig('carriers/pudo/enable_demo');
		  $api_username = Mage::getStoreConfig('carriers/pudo/tracking_api_username');
		  $api_password = Mage::getStoreConfig('carriers/pudo/tracking_api_password');
		 
		$docno = $this->getRequest()->getParam('docno');
		if($docno){
			if($enable_demo == "1"){
			$url ='http://dotzot-test.azurewebsites.net/services/Cust_WS_Ver2.asmx/ConsignmentTrackEvents_Details_New?userName=dztuser&password=dotzot@2013&clientId=DOTZOT&DOCNO='.$docno;
			}else{
			$url = 'http://instacom.dotzot.in/services/Cust_WS_Ver2.asmx/ConsignmentTrackEvents_Details_New?userName='.$api_username.'&password='.$api_password.'&clientId=DOTZOT&DOCNO='.$docno;
			} 
			$xml = simplexml_load_file($url); 
			if(!empty($xml)){
			$dt = array();
			$dtR = array();
			    
			$html = '';
			$html .="<br><table width='700' border='1'><tr><td><span>Date</span></td><td><span>ACTIVITY</span></td></tr>";
			foreach($xml as $dtdc){ 
				$html .="<tr><td><span>".$dtdc->EVENTDATE."</span></td><td><span>".$dtdc->ACTIVITY."</span></td></tr>";
				if($dtdc->TRACKING_CODE == 'D'){
					$dt[] = $dtdc[0];
				}
				elseif($dtdc->TRACKING_CODE == 'R'){
					$dtR[] = $dtdc[0];
				} 		
			} 
			$html .="</table>";
			echo $html; 
		}else{
			echo "Docket no is not found.";
		}
	
	}
	}
	public function samplecsvAction(){   
			header('Content-Type: application/excel');
			header('Content-Disposition: attachment; filename="sample.csv"');
			$data = array('700004671723', 'cashondelivery'); 
			$data = array('I30005981595', 'other'); 
			$fp = fopen('php://output', 'w'); 
			fputcsv($fp, $data); 
			fclose($fp);
	}
	
}
