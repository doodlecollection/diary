<?php
/**
 * Created by Sublime.
 * User: Fateh
 * Date: 08/7/2018
 * Time: 1:30 PM
 */

namespace Vikas\ShipwayTracking\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Model\Order;
use Magento\Shipping\Model\Order\Track;


class ShipmentTrackSaveAfterObserver implements ObserverInterface {


    private $_scopeConfig = NULL;
    private $_messageManager  = NULL;

    public function __construct( 
    \Magento\Framework\App\Action\Context $context,
    \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
    \Magento\Framework\Message\ManagerInterface $messageManager
        ){

    $this->_scopeConfig = $scopeConfig;
    $this->_messageManager  = $messageManager;

    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // Instance of object manager

$scopeConfig = $objectManager->get('\Magento\Framework\App\Config\ScopeConfigInterface');

$resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
$connection = $resource->getConnection();

//print_r($observer->getEvent()->getData());  
        $magentoTrack = $observer->getEvent()->getTrack();
        $track_code = $magentoTrack->getCarrierCode();
        $track_title= $magentoTrack->getTitle();
        $track_number= $magentoTrack->getTrackNumber();

        $magentoOrder = $magentoTrack->getShipment()->getOrder();

        $shipway_username = $this->_scopeConfig->getValue('shipway_tracking_section/general/text_shipway_username',\Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $shipway_licence_key = $this->_scopeConfig->getValue('shipway_tracking_section/general/text_shipway_licence_key',\Magento\Store\Model\ScopeInterface::SCOPE_STORE);

     /*   echo $magentoTrack->getTitle();  echo "<br>";
        echo $magentoTrack->getCarrierCode();  echo "<br>";

        echo $magentoTrack->getTrackNumber();  echo "<br>";
*/

 $deploymentConfig = $objectManager->get('Magento\Framework\App\DeploymentConfig');
    $table_prefix = $deploymentConfig->get('db/table_prefix');
    $table_name =  $table_prefix.'shipway_couriermapping';

$courierDetail= $connection->fetchAll("select * from ".$table_name."  where 1");
        
        $carriers_array = array();

        foreach ($courierDetail as $key => $value) {
            $carriers_array[$value['courier_type']][$value['default_courier']]['name']= $value['default_courier'];
            $carriers_array[$value['courier_type']][$value['default_courier']]['id']= $value['shipway_courierid'];
            }

 
            $shipway_carrier_id = '';
            if($track_code!='custom'){
                
                if(array_key_exists($track_code, $carriers_array['default'])){
                    $shipway_carrier_id =  $carriers_array['default'][$track_code]['id'];
                }
            }else{
                if(array_key_exists($track_title, $carriers_array['custom'])){
                    $shipway_carrier_id =  $carriers_array['custom'][$track_title]['id'];
                }
            }



        $data = array(
            'carrier_id' => $shipway_carrier_id,
            'order_id' => $magentoOrder->getIncrementId(),
            'awb' => $track_number,
            'username' => $shipway_username,
            'password' => $shipway_licence_key
        );


        $ordered_items = $magentoOrder->getAllItems(); 
    
        $itms='';
        $products=array();
        $orderdetails=array();
        foreach($ordered_items as $item){
            $itms .= $item->getName().' ';
        }

        $itms=substr($itms,0,35);
        $shipping_address = $magentoOrder->getShippingAddress();
       
        $company_name= $_SERVER['SERVER_NAME'];
        $data['first_name']     = $shipping_address->getFirstname();
        $data['last_name']      = $shipping_address->getLastname();
        $data['email']          = $shipping_address->getEmail();
        $data['phone']          = $shipping_address->getTelephone();
        $data['products']       = $itms;
        $data['company']     = $company_name;
       
        $url = "https://shipway.in/api/pushOrderData";

        $data_string = json_encode($data);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        'Content-Type:application/json'
        ));
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $output = curl_exec($curl);
        $output = json_decode($output);
        curl_close($curl); 
        $result=(array)$output;

        if ($result['status'] == 'Failed ') {
            $this->_messageManager->addError($result['message']);
        } else {
            $this->_messageManager->addSuccess($result['message']);
        }
       // return;
    }
}