<?php 
use Magento\Framework\App\Bootstrap;
require __DIR__ . '/app/bootstrap.php';



//$orderData = getOrderCollection();
//echo '<pre>';print_r($orderData);die;       
//function getOrderCollection()
  //  {
        $bootstrap = Bootstrap::create(BP, $_SERVER);
        $objectManager = $bootstrap->getObjectManager();
		$fp = fopen("export.csv","w+");
        $order = $objectManager->create('Magento\Sales\Model\Order')->getCollection();
		//$productObject = $objectManager->create('Magento\Catalog\Model\Product');
		
		$orderData = array();
		$orderData[] = "Order ID";
		$orderData[] = "Customer First name";
		$orderData[] = "Customer Last name";
		$orderData[] = "Customer Email";
		$orderData[] = "Status";
		$orderData[] = "Order Date";
		$orderData[] = "SKU";
		$orderData[] = "Product name";
		$orderData[] = "Product selling price";
		$orderData[] = "Quantity Ordered";
		fputcsv($fp, $orderData);
        //echo '<pre>';print_r($order->getData());die;      
        foreach($order as $_order)
        {
			$orderItems = $_order->getAllItems();
            foreach($orderItems as $item)
            {
			$orderData = array();
			$orderData[] = $_order->getData('increment_id');
			
            $orderData[] = $_order->getData('customer_firstname');        
            $orderData[] = $_order->getData('customer_lastname');
            $orderData[] = $_order->getData('customer_email');
			$orderData[] = $_order->getData('status');
			$orderData[] = $_order->getData('created_at');
			//fputcsv($fp, $orderData);
            
				//$orderData = array();
                //echo '<pre>';print_r($item->getData());die;                       
                $orderData[] = $item->getData('sku');
				
				$orderData[] = $item->getName();
				$orderData[] = $item->getPrice();
				$orderData[] = $item->getData('qty_ordered');
				
				//$product = $item->getProduct();
				
				fputcsv($fp, $orderData);
            }
			

        }
		
		if ( ! file_exists('export.csv'))
		{
			echo 'file missing';
		}
		else
		{
			header('HTTP/1.1 200 OK');
			header('Cache-Control: no-cache, must-revalidate');
			header("Pragma: no-cache");
			header("Expires: 0");
			header("Content-type: text/csv");
			header("Content-Disposition: attachment; filename=export.csv");
			readfile('export.csv');
			exit;
		}

		fclose($fp);
        //return $orderData;

    //}
	?>