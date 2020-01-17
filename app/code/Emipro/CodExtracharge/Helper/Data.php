<?php

namespace Emipro\CodExtracharge\Helper;

use Emipro\CodExtracharge\Model\Productrule as ProductruleFactory;
use Emipro\CodExtracharge\Model\ResourceModel\Productrule\Collection as ProductruleCollection;
use Emipro\CodExtracharge\Model\ResourceModel\Rule\CollectionFactory as RuleCollectionFactory;
use Emipro\CodExtracharge\Model\RuleFactory;
use Emipro\CodExtracharge\Model\Sales\ExtrachargeFactory;
use Magento\Catalog\Model\Product as ProductModel;
use Magento\Checkout\Model\Cart as CheckoutModel;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable as ConfigurableModel;
use Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable as ConfigurableResourceModel;
use Magento\Customer\Model\Session\Proxy as CustomerSession;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\ProductMetadataInterface;
use Magento\Framework\App\ResourceConnection;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{

    protected $scopeConfig;
    protected $storeManager;
    protected $messageManager;

    /**
     * [__construct description]
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig               [description]
     * @param \Magento\Store\Model\StoreManagerInterface         $storeManager              [description]
     * @param \Magento\Framework\Message\ManagerInterface        $messageManager            [description]
     * @param \Magento\Framework\UrlInterface                    $url                       [description]
     * @param ProductMetadataInterface                           $productMetadataInterface  [description]
     * @param RuleFactory                                        $ruleFactory               [description]
     * @param ProductruleFactory                                 $productruleFactory        [description]
     * @param ResourceConnection                                 $resourceConnection        [description]
     * @param CustomerSession                                    $customerSession           [description]
     * @param ProductModel                                       $productModel              [description]
     * @param ExtrachargeFactory                                 $extrachargeFactory        [description]
     * @param CheckoutModel                                      $checkoutModel             [description]
     * @param ConfigurableResourceModel                          $configurableResourceModel [description]
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        ProductMetadataInterface $productMetadataInterface,
        RuleFactory $ruleFactory,
        ProductruleFactory $productruleFactory,
        ResourceConnection $resourceConnection,
        CustomerSession $customerSession,
        ProductModel $productModel,
        ExtrachargeFactory $extrachargeFactory,
        CheckoutModel $checkoutModel,
        ConfigurableResourceModel $configurableResourceModel,
        ConfigurableModel $configurableModel,
        ProductruleCollection $productRuleCollection,
        RuleCollectionFactory $ruleCollectionFactory,
        \Magento\Framework\ObjectManagerInterface $objmanager,
        \Magento\Directory\Helper\Data $directoryHelper

    ) {
        $this->scopeConfig = $scopeConfig;
        $this->_storeManager = $storeManager;
        $this->messageManager = $messageManager;
        $this->productMetadataInterface = $productMetadataInterface;
        $this->ruleFactory = $ruleFactory;
        $this->productruleFactory = $productruleFactory;
        $this->resourceConnection = $resourceConnection;
        $this->customerSession = $customerSession;
        $this->productModel = $productModel;
        $this->extrachargeFactory = $extrachargeFactory;
        $this->checkoutModel = $checkoutModel;
        $this->configurableResourceModel = $configurableResourceModel;
        $this->configurableModel = $configurableModel;
        $this->productRuleCollection = $productRuleCollection;
        $this->ruleCollectionFactory = $ruleCollectionFactory;
        $this->objmanager = $objmanager;
        $this->directoryHelper = $directoryHelper;
    }

    /**
     * [checkVersion description]
     * @param  [type] $ua_regexp [description]
     * @return [type]            [description]
     */
    public function checkVersion($ua_regexp)
    {
        if (!empty($ua_regexp)) {
            $version = $this->productMetadataInterface->getVersion();
            if ($version < '2.2.0') {
                $table_data = unserialize($ua_regexp);
                return $table_data;
            } else {
                $serialize = $this->objmanager->create('Magento\Framework\Serialize\Serializer\Json');
                $table_data = $serialize->unserialize($ua_regexp);
                return $table_data;
            }
        }

    }
    public function serializeData($app_rule_pro)
    {
        $version = $this->productMetadataInterface->getVersion();
        if ($version < '2.2.0') {
            $table_data = serialize($app_rule_pro);
            return $table_data;
        } else {
            $serialize = $this->objmanager->create('Magento\Framework\Serialize\Serializer\Json');
            $table_data = $serialize->serialize($app_rule_pro);
            return $table_data;
        }
    }

    /**
     * [getConfig description]
     * @param  [type] $config_path [description]
     * @return [type]              [description]
     */
    public function getConfig($config_path)
    {
        return $this->scopeConfig->getValue(
            $config_path,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * [getProductIds description]
     * @return [type] [description]
     */
    public function getProductIds()
    {
        $rulesFactory = $this->ruleFactory->create();
        $ids = $rulesFactory->getCollection()->getAllIds();
        foreach ($ids as $id) {
            $data = $this->getProductRule($id);
            if ($data->getData("is_active") == 1) {
                $pids = $data->getMatchingCodProductIds($data->getWebsiteIds());
                $cod_id = $id;
                $productRuleCollections = $this->productRuleCollection->addFieldToFilter('rules_id', $cod_id);
                foreach ($productRuleCollections as $productRuleCollection) {
                    $this->deleteProductRule($productRuleCollection);
                }
                $this->insertIds($cod_id, $pids);
            } else {
                $this->messageManager->addError('This Rule is Disable.');
            }
        }
    }

    /**
     * [applyrule description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function applyrule($id)
    {
        $data = $this->getProductRule($id);
        if ($data->getData("is_active") == 1) {
            $pids = $data->getMatchingCodProductIds($data->getWebsiteId());
            $cod_id = $id;
            $productRuleCollections = $this->productRuleCollection->addFieldToFilter('rules_id', $cod_id);
            foreach ($productRuleCollections as $productRuleCollection) {
                $this->deleteProductRule($productRuleCollection);
            }
            $this->insertIds($cod_id, $pids);
        }
    }

    /**
     * [getProductRule description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    private function getProductRule($id)
    {
        $rulesFactory = $this->ruleFactory->create();
        return $rulesFactory->load($id);
    }

    /**
     * [deleteProductRule description]
     * @param  [type] $productRuleCollection [description]
     * @return [type]                        [description]
     */
    private function deleteProductRule($productRuleCollection)
    {
        $productRuleCollection->delete();
    }

    /**
     * [insertIds description]
     * @param  [type] $cod_id [description]
     * @param  [type] $pids   [description]
     * @return [type]         [description]
     */
    public function insertIds($cod_id, $pids)
    {
        $writeConnection = $this->resourceConnection->getConnection('default');
        $table = $writeConnection->getTableName('emipro_codextracharge_products');
        $fields = [];

        try {
            foreach ($pids as $key => $value) {
                if ($value[1] == 1 && $value[0] == 1) {
                    $fields[] = ["entity_id" => $key, "rules_id" => $cod_id];
                }
            }
            if (!empty($fields)) {
                $writeConnection->insertMultiple($table, $fields);
            }
        } catch (\Magento\Framework\Validator\Exception $e) {
            $logger->addDebug($e->getMessage(), true);
        }
    }

    /**
     * [perproductCodcharges description]
     * @param  [type] $product_id [description]
     * @return [type]             [description]
     */
    public function perproductCodcharges($product_id)
    {
        $proId = [];
        $costArr = [];
        $option = "";
        $priority = 0;
        $final_charges = 0;
        $withoutoption = "";
        $confAttrText = '';
        $id = $product_id;
        $customer_group_id = $this->customerSession->getCustomerGroupId();
        $_product = $this->productModel->load($id);
        $proId[] = $id;
        $pType = $_product->getTypeId();
        $childProductsId = $this->getConfigurableProductChildrenIds($id);
        foreach ($childProductsId["0"] as $childId) {
            $proId[] = $childId;
        }
        $collection = $this->productruleFactory->getCollection();
        $collection->availableCODExtrachargesPerProduct($proId);
        $applyed_rules = 0;
        if ($collection->getSize() > 0) {
            foreach ($collection as $val) {
                $customer = explode(",", $val["customer_group_id"]);
                if ($val["is_active"] == 1 && in_array($customer_group_id, $customer) &&
                    $applyed_rules == 0) {
                    if ((($priority == 0) || ($val['sort_order'] != $priority)) && $withoutoption == "") {
                        $priority = $val['sort_order'];
                        $entId = $val["entity_id"];
                        $rulId = $val["rules_id"];
                        $optTxt = ($pType == "configurable") ? $this->getConfigurableOption($_product, $entId, $rulId) : '';
                        $attrCode = ($optTxt != "") ? explode(" ", trim($optTxt, "with ")) : [];
                        if ($val["cod_charges_type"] == "fixed") {
                            $cost = $val["cod_charges"];
                        } else {
                            $fnlPrc = $_product->getFinalPrice();
                            $cost = ($fnlPrc * $val["cod_charges"]) / 100;
                        }
                        $final_charges = (!empty($costArr) ? ($final_charges - $costArr[$option]) : $final_charges);
                        $option = (!empty($attrCode)) ? $attrCode[1] : "";
                        $final_charges = $cost + $final_charges;
                        if ($option != "" && $option == $attrCode[1]) {
                            $costArr[$option] = $cost;
                        } else {
                            $withoutoption = $priority + 1;
                            $costArr = [];
                        }
                        $final_charge = number_format((float) $final_charges, 1, '.', '');
                        $arr[] = ["cost" => $final_charge, "status" => 1, "optionText" => $optTxt];
                    }
                }
            }
            return $arr;
        }

    }

    /**
     * [getRules description]
     * @param  [type] $quote [description]
     * @return [type]        [description]
     */
    public function getRules($quote = null)
    {
        $cart = ($quote) ? $quote : $this->checkoutModel->getQuote();
        $final_charges = 0;
        $name = "";
        $rules_pro_id = [];
        $app_rule_prod_arr = [];
        $cart_pro = [];
        $price = 0;
        $status = 0;
        foreach ($cart->getAllItems() as $item) {
            $temp_rules_pro_id = [];
            $proId = [];
            $cost = 0;
            $id = $item->getProductId();
            $proId[] = $id;
            $_product = $this->getProductModel($id);
            $price = ($item->getPrice() != 0) ? $item->getPrice() * $item->getQty() : $price;
            $temp_rules_pro_id = ($item->getPrice() != 0) ? $temp_rules_pro_id : [];
            $collection = $this->productruleFactory->getCollection();
            $collection->availableCODExtracharges($id);
            $applyed_rules = 0;
            $cart_pro[] = ($item->getProductType() == "simple") ? $id : '';
            foreach ($collection as $val) {
                $customer = explode(",", $val["customer_group_id"]);
                if ($val["is_active"] == 1
                    && in_array($cart["customer_group_id"], $customer) && $applyed_rules == 0) {
                    if ($item->getProductType() == "configurable") {
                        $childProductsId = $this->getConfigurableProductChildrenIds($item->getProductId());
                        foreach ($childProductsId["0"] as $childId) {
                            $temp_rules_pro_id[] = $childId;
                        }
                    }
                    $condition = (empty($temp_rules_pro_id));
                    $charges = $val["cod_charges"];
                    $chargesType = $val["cod_charges_type"];
                    $cost = $condition ? $this->getCharges($charges, $chargesType, $price, $cart) : $cost;
                    $app_rule_prod_arr[] = ['pid' => $id, 'charge' => $cost];
                    $status = 1;
                    $final_charges = $cost + $final_charges;
                }
            }
        }
        $app_rule_prod = $this->serializeData($app_rule_prod_arr);
        $final_charges_name = [
            "final_charges" => $final_charges,
            "name" => $name,
            "status" => $status,
            "applyPro" => $app_rule_prod,
        ];
        return $final_charges_name;
    }

    public function getCodAppliedProdIds($items)
    {
        $proId = [];
        $qty = 0;
        foreach ($items->getAllItems() as $item) {
            $temp_rules_pro_id = [];
            $id = $item->getProductId();
            $_product = $this->getProductModel($id);
            if ($_product->getTypeId() == "configurable" && $item->getQty() > 0) {
                $childProductsId = $this->getConfigurableProductChildrenIds($item->getProductId());
                foreach ($childProductsId["0"] as $childId) {
                    $temp_rules_pro_id[] = $childId;
                }
            } else if ($_product->getTypeId() == "simple" && $item->getQty() > 0) {
                $product = $this->LoadParent($id);
                if (isset($product[0])) {
                    $id = $id;
                }
                $temp_rules_pro_id[] = $id;
            }
            if ($condition = (empty($temp_rules_pro_id) && $qty != 0)) {
                $proId[] = $condition ? $id : '';
            } else {
                $proId[] = $id;
            }
            $qty = $item->getQty();
        }
        return $proId;
    }
    public function LoadParent($id)
    {
        $product = ObjectManager::getInstance()->create(ConfigurableModel::class)->getParentIdsByChild($id);
        return $product;
    }
    /**
     * [getConfigurableProductChildrenIds description]
     * @param  [type] $item [description]
     * @return [type]       [description]
     */
    public function getConfigurableProductChildrenIds($productId)
    {
        return $this->configurableResourceModel->getChildrenIds($productId);
    }

    /**
     * [getConfigurableOption description]
     * @param  [type] $product [description]
     * @return [type]          [description]
     */
    public function getConfigurableOption($product, $entity_id, $rule_id)
    {
        $confAttrText = "";
        $productId = [];
        $productAttributes = $this->configurableModel->getConfigurableOptions($product);
        foreach ($productAttributes as $productAttribute) {
            foreach ($productAttribute as $proAttr) {
                $proAttrId = $this->getProductIdBySKU($proAttr['sku']);
                $attrCode = $proAttr['attribute_code'];
                $proAttrCollection = $this->ruleCollectionFactory->create();
                $proAttrCollection->addFieldToFilter('conditions_serialized', ['like' => "%" . $attrCode . "%"]);
                foreach ($proAttrCollection as $rule) {
                    if ($rule->getRulesId() == $rule_id) {
                        $productId[$proAttrId][$attrCode] = $proAttr['option_title'] . " " . $attrCode;
                    }
                }
            }
        }
        foreach ($productId as $proAttrId => $proAttr) {
            if ($proAttrId == $entity_id) {
                $confAttrText = " with " . implode(' & ', $proAttr);
            }
        }
        return $confAttrText;
    }

    public function getSpecificCountryCod()
    {
        $specificText = "";
        $enable = $this->getConfig('payment/cashondelivery/activebaseonrule', true);
        $specificCount = $this->getConfig('payment/cashondelivery/specificcountry', true);
        if ($specificCount != "") {
            $countArr = explode(",", $specificCount);
            foreach ($countArr as $countryCode) {
                $country = $this->_countryFactory->create()->loadByCode($countryCode);
                $specificTextArr[] = $country->getName();
            }
            $specificText = " In " . implode(" & ", $specificTextArr);
        }
        return $specificText;
    }

    /**
     * [getConfigurableOption description]
     * @param  [type] $product [description]
     * @return [type]          [description]
     */
    public function getConfigurableFinalPrice($product)
    {
        $productAttributes = $this->configurableModel->getConfigurableOptions($product);
        foreach ($productAttributes as $productAttribute) {
            foreach ($productAttribute as $proAttr) {
                $proAttrId = $this->getProductIdBySKU($proAttr['sku']);
                $prod = $this->getProductModel($proAttrId);
                return $prod->getFinalPrice();
            }
        }
    }

    /**
     * [getProductIdBySKU description]
     * @param  [type] $sku [description]
     * @return [type]      [description]
     */
    private function getProductIdBySKU($sku)
    {
        return $this->productModel->getIdBySku($sku);
    }

    /**
     * [getProductModel description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    private function getProductModel($id)
    {
        return $this->productModel->load($id);
    }

    /**
     * [getCharges description]
     * @param  [type] $fees         [description]
     * @param  [type] $id           [description]
     * @param  [type] $charges_type [description]
     * @param  [type] $quote        [description]
     * @return [type]               [description]
     */
    public function getCharges($fees, $charges_type, $pro_price, $quote = null)
    {
        $total = 0;
        $price = 0;
        $cart_price = 0;
        $cartinfo = ($quote) ? $quote : $this->checkoutModel->getQuote();

        if ($pro_price != 0) {
            if ($charges_type == "fixed") {
                $charge = $fees;
            } else {
                $charge = $pro_price * $fees / 100;
            }
            $total = $total + $charge;
        }
        return $total;
    }

    public function foo($key, $val)
    {
        return [$key => $val];
    }

    /**
     * [codCharges description]
     * @param  [type] $quote [description]
     * @return [type]        [description]
     */
    public function codCharges($quote = null)
    {
        $enable = $this->getConfig('payment/cashondelivery/activebaseonrule', true);
        $ua_regexp = $this->getConfig('payment/cashondelivery/active2', true);
        $table_data = $this->checkVersion($ua_regexp);
        $fees = $this->getRules($quote);
        if ($enable != 1) {
            $condition = [];
            $cartinfo = ($quote) ? $quote : $this->checkoutModel->getQuote();
            $fees['final_charges'] = 0;
            $fees['name'] = '';
            $fees['status'] = 0;
            $fees['applyPro'] = "";

            $extrachargeFactory = $this->extrachargeFactory->create();
            if (!empty($table_data)) {
                foreach ($table_data as $data) {

                    $condition_amount = array_column($table_data, 'amount');
                    $payment_condition = array_column($table_data, 'payment_condition');
                    $max_payment_condition = array_combine($condition_amount, $payment_condition);
                    $custgrp = ($cartinfo["customer_group_id"] == $data["customer_group"]
                        || $data["customer_group"] == 32000);
                    $condition = ($custgrp) ? $extrachargeFactory->getPaymentCondition(
                        $cartinfo->getBaseSubtotal(),
                        $data['payment_condition'],
                        $data['amount'],
                        $condition_amount,
                        $max_payment_condition
                    ) : false;
                    if ($condition && $custgrp) {

                        $fees['status'] = 1;
                        if ($data['extra_charge_type'] == 'fixed') {
                            $fees['final_charges'] = $data['extra_charge_value'];
                            $balance = $this->convertPrice($fees['final_charges'], 0);
                        }
                        if ($data['extra_charge_type'] == 'percentage') {

                            $totalamount = $cartinfo->getBaseSubtotal();
                            $fees['final_charges'] = $totalamount * $data['extra_charge_value'] / 100;
                            $balance = $this->convertPrice($fees['final_charges'], 1);
                        }
                    }
                }
            }

            $fees['final_charges'] = $fees['final_charges'];
        }
        if (isset($balance)) {
            $balance = $balance;
        } else {
            $balance = $this->convertPrice($fees['final_charges'], 1);
        }
        if (empty($fees["name"]) && $fees["status"] == 1) {
            $arr = ["fees" => $fees["final_charges"], "base_balance" => $fees['final_charges'], "balance" => $balance, "status" => "1", "message" => "", "applyPro" => $fees['applyPro']];
        } else {
            $arr = [
                "fees" => 0,
                "status" => "",
                "message" => "",
                "applyPro" => "",
                "base_balance" => "",
                "balance" => "",
            ];
        }
        return $arr;
    }
    public function convertPrice($amountValue, $chargetype)
    {
        $currentCurrency = $this->_storeManager->getStore()->getCurrentCurrency()->getCode();
        $baseCurrency = $this->_storeManager->getStore()->getBaseCurrency()->getCode();
        if ($currentCurrency != $baseCurrency) {
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $amountValue = $this->directoryHelper->currencyConvert($amountValue, $baseCurrency, $currentCurrency);
            $amountValue = number_format($amountValue, 1, '.', '');
        }
        return $amountValue;
    }
}
