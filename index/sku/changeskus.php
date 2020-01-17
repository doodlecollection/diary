<?php
class changeskus
    extends \Magento\Framework\App\Http
    implements \Magento\Framework\AppInterface {
    public function launch()
    {
        $this->_state->setAreaCode('frontend'); //Set area code 'frontend' or 'adminhtml
         
        $updates_file="sku2sku.csv";
        $sku_entry=array();
        $updates_handle=fopen($updates_file, 'r');
        if($updates_handle) {
            while($sku_entry=fgetcsv($updates_handle, 1000, ",")) {
                $old_sku=$sku_entry[0];
                $new_sku=$sku_entry[1];
                echo "<br>Updating ".$old_sku." to ".$new_sku." - ";
                $_product = $this->_objectManager->create('\Magento\Catalog\Model\Product')->loadByAttribute('sku', $old_sku);
                try {
                    if ($_product) {
                        $_product->setSku($new_sku)->save();
                        echo "successful";
                    } else {
                        echo "item not found";
                    }
                }
                catch (\Exception $e) {
                    echo "Cannot retrieve products from Magento: ".$e->getMessage()."<br>";
                    return;
                }
            }
        }
        fclose($updates_handle);
 
        return $this->_response;
    }
 
    public function catchException(\Magento\Framework\App\Bootstrap $bootstrap, \Exception $exception)
    {
        return false;
    }
 
}
?>