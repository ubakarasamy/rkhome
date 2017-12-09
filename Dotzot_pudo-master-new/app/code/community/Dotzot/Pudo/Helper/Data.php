<?php
class Dotzot_Pudo_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function getPickupList($criteria,$searchType=0)
    {  
	  $maxResult = Mage::getStoreConfig('carriers/pudo/max_result');
	  //return $maxResult;
	 // debug_to_console( "Test" );
	  
        $soapUrl = "http://locator.paypoint.com:61001/AgentLocator.asmx?WSDL"; // asmx URL of WSDL

        $xml_post_string = '<?xml version="1.0" encoding="utf-8"?>
                            <soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
                              <soap:Body>
                                <GetNearestAgentsType1 xmlns="http://paypoint.co.uk/">
                                 <searchCriteria>'.$criteria.'</searchCriteria>
                                  <searchType>'.$searchType.'</searchType>
                                  <maxRecords>'.$maxResult.'</maxRecords>
                                </GetNearestAgentsType1>
                              </soap:Body>
                            </soap:Envelope>';

        $headers = array(
            "POST /AgentLocator.asmx HTTP/1.1",
            "Host: locator.paypoint.com",
            "Content-Type: text/xml",
            "Content-Length: ".strlen($xml_post_string),
            "SOAPAction: http://paypoint.co.uk/GetNearestAgentsType1"
        ); //SOAPAction: your op URL
        $url = $soapUrl;
        // PHP cURL  for https connection with auth
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); // the SOAP request
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // converting
        try {

            $response =  curl_exec($ch);
            curl_close($ch);

        } catch (Exception $e)
        {

            echo $e->getMessage();
        }


        $doc = new DOMDocument();
        $doc->loadXML( $response );

        //$LoginResults = $doc->getElementsByTagName( "Agent" )->items();
        //$LoginResults = $doc->getElementsByTagName( "Agents" )->item(0)->nodeValue;
        //echo $xml->properties->property['HotelName'];

        $returnCode = $doc->getElementsByTagName( "ReturnCode" )->item(0)->nodeValue;
        $returnMessage = $doc->getElementsByTagName( "ReturnMessage" )->item(0)->nodeValue;


        $soap     = simplexml_load_string($response);
        $agents = $soap->children('http://schemas.xmlsoap.org/soap/envelope/')
            ->Body->children()
            ->GetNearestAgentsType1Response
            ->GetNearestAgentsType1Result
            ->Agents
            ->Agent;

        $agentList = array();

        foreach($agents as $agent)
        {
            $array = array();
            foreach($agent as $item)
            {
                $array[$item->getName()] = (string)$item;
            }
            $agentList[] = $array;
        }

        $result = array();
        $result['return_code'] = $returnCode;
        $result['return_message'] = $returnMessage;
        $result['agent_lists'] = $agentList;

        return $result;
        	
	}
}
	 
