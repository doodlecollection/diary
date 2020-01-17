<?php
/**
 * Webkul Software
 *
 * @category Webkul
 * @package Webkul_Walletsystem
 * @author Webkul
 * @copyright Copyright (c) 2010-2017 Webkul Software Private Limited (https://webkul.com)
 * @license https://store.webkul.com/license.html
 */

namespace Webkul\Walletsystem\Helper;

class Mail extends Data
{
    /**
     * @var templateId
     */
    protected $_tempId;

    protected function generateTemplate($emailTemplateVariables, $senderInfo, $receiverInfo)
    {
        $template =  $this->_transportBuilder->setTemplateIdentifier($this->_tempId)
            ->setTemplateOptions(
                [
                    'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                    'store' => $this->_storeManager->getStore()->getId(),
                ]
            )
            ->setTemplateVars($emailTemplateVariables)
            ->setFrom($senderInfo)
            ->addTo($receiverInfo['email'], $receiverInfo['name']);
        return $this;
    }
    // calls when invoice generated for an order either on create invoice or by capture method
    public function checkAndUpdateWalletAmount($order)
    {
        if (count($order->getInvoiceCollection())) {
            $orderId = $order->getId();
            if ($orderId) {
                $totalAmount = 0;
                $remainingAmount = 0;
                $orderModel = $this->_orderModel
                    ->create()
                    ->load($orderId);
                $incrementId = $order->getIncrementId();
                $orderItem = $orderModel->getAllItems();
                $productIdArray = [];
                foreach ($orderItem as $value) {
                    $productIdArray[] = $value->getProductId();
                }
                $walletProductId = $this->getWalletProductId();
                if (in_array($walletProductId, $productIdArray)) {
                    $walletCollection = $this->_walletTransaction
                        ->create()
                        ->getCollection()
                        ->addFieldToFilter('order_id', ['eq' => $orderId])
                        ->addFieldToFilter('status', 0);
                    if (count($walletCollection)) {
                        foreach ($walletCollection as $record) {
                            $rowId = $record->getId();
                            $customerId = $record->getCustomerId();
                            $amount = $record->getAmount();
                            $action = $record->getAction();
                        }
                        $data = ['status' => 1];
                        $walletTansactionModel = $this->_walletTransaction
                            ->create()
                            ->load($rowId)
                            ->addData($data);
                        $walletTansactionModel->setId($rowId)->save();
                        $walletRecordCollection = $this->_walletRecordFactory
                            ->create()
                            ->getCollection()
                            ->addFieldToFilter(
                                'customer_id',
                                ['eq' => $customerId]
                            );
                        if ($action == 'credit') {
                            $this->updateWalletDataAmount($walletRecordCollection, $amount, $customerId, $incrementId);
                        }
                    }
                }
            }
        }
    }
    public function updateWalletDataAmount($walletRecordCollection, $amount, $customerId, $incrementId)
    {
        $remainingAmount = 0;
        $totalAmount = 0;
        if (count($walletRecordCollection)) {
            foreach ($walletRecordCollection as $record) {
                $totalAmount = $record->getTotalAmount();
                $remainingAmount = $record->getRemainingAmount();
                $recordId = $record->getId();
            }
            $data = [
                'total_amount' => $amount + $totalAmount,
                'remaining_amount' => $amount + $remainingAmount,
                'updated_at' => $this->_date->gmtDate(),
            ];
            $walletRecordModel = $this->_walletRecordFactory
                ->create()
                ->load($recordId)
                ->addData($data);
            $saved = $walletRecordModel->setId($recordId)->save();
        } else {
            $walletRecordModel = $this->_walletRecordFactory
                ->create();
            $walletRecordModel->setTotalAmount($amount + $totalAmount)
                ->setCustomerId($customerId)
                ->setRemainingAmount($amount + $remainingAmount)
                ->setUpdatedAt($this->_date->gmtDate());
            $saved = $walletRecordModel->save();
        }
        if ($saved->getId() != 0) {
            $date = $this->_localeDate->date(new \DateTime($this->_date->gmtDate()));
            $formattedDate = $date->format('g:ia \o\n l jS F Y');
            $finalAmount = $amount + $remainingAmount;
            $emailParams = [
                'walletamount' => $this->getformattedPrice($amount),
                'remainingamount' => $this->getformattedPrice($finalAmount),
                'type' => 0,
                'action' => 'credit',
                'increment_id' => $incrementId,
                'transaction_at' => $formattedDate
            ];
            $store = $this->getStore();
            $this->sendMailForTransaction(
                $customerId,
                $emailParams,
                $store,
                $amount
            );
        }
    }
    public function sendTransferCode($mailData)
    {
        try {
            $customer = $this->_customerModel
                ->create()
                ->load($mailData['customer_id']);
            $emailTempVariables = [];
            $emailTempVariables['customername'] = $customer->getName();
            $emailTempVariables['code'] = $mailData['code'];
            $emailTempVariables['duration'] = $mailData['duration'];
            $emailTempVariables['amount'] = $this->getformattedPrice($mailData['base_amount']);
            $adminEmail= $this->getDefaultTransEmailId();
            $adminUsername = 'Admin';
            $senderInfo = [];
            $receiverInfo = [];
            $receiverInfo = [
                'name' => $customer->getName(),
                'email' => $customer->getEmail(),
            ];
            $senderInfo = [
                'name' => $adminUsername,
                'email' => $adminEmail,
            ];
            $this->_tempId = $this->getCustomerAmountTransferOTPTemplateId();
            $this->_inlineTranslation->suspend();
            $this->generateTemplate(
                $emailTempVariables,
                $senderInfo,
                $receiverInfo
            );
            $transport = $this->_transportBuilder->getTransport();
            $transport->sendMessage();
            $this->_inlineTranslation->resume();
        } catch (\Exception $e) {
            $this->_messageManager->addError($e->getMessage());
        }
    }

    public function sendMonthlyTransaction($mailData)
    {
        $currency = $this->getBaseCurrencyCode();
        try {
            $customer = $this->_customerModel
                ->create()
                ->load($mailData['customer_id']);
            $emailTempVariables = [];
            $emailTempVariables['customername'] = $customer->getName();
            $emailTempVariables['date'] = $mailData['month'].", ".$mailData['year'];
            $emailTempVariables['emailid'] = $customer->getEmail();
            $emailTempVariables['month'] = $mailData['month'];
            $emailTempVariables['year'] = $mailData['year'];
            $emailTempVariables['openingbalance'] = $this->getFormattedPriceAccToCurrency(
                $mailData['openingbalance'],
                2,
                $currency
            );
            $emailTempVariables['closingbalance'] = $this->getFormattedPriceAccToCurrency(
                $mailData['closingbalance'],
                2,
                $currency
            );
            $emailTempVariables['rechargewallet'] = $this->getFormattedPriceAccToCurrency(
                $mailData['rechargewallet'],
                2,
                $currency
            );
            $emailTempVariables['cashbackamount'] = $this->getFormattedPriceAccToCurrency(
                $mailData['cashbackamount'],
                2,
                $currency
            );
            $emailTempVariables['refundamount'] = $this->getFormattedPriceAccToCurrency(
                $mailData['refundamount'],
                2,
                $currency
            );
            $emailTempVariables['admincredit'] = $this->getFormattedPriceAccToCurrency(
                $mailData['admincredit'],
                2,
                $currency
            );
            $emailTempVariables['customercredits'] = $this->getFormattedPriceAccToCurrency(
                $mailData['customercredits'],
                2,
                $currency
            );
            $emailTempVariables['usedwallet'] = $this->getFormattedPriceAccToCurrency(
                $mailData['usedwallet'],
                2,
                $currency
            );
            $emailTempVariables['refundwalletorder'] = $this->getFormattedPriceAccToCurrency(
                $mailData['refundwalletorder'],
                2,
                $currency
            );
            $emailTempVariables['admindebit'] = $this->getFormattedPriceAccToCurrency(
                $mailData['admindebit'],
                2,
                $currency
            );
            $emailTempVariables['transfertocustomer'] = $this->getFormattedPriceAccToCurrency(
                $mailData['transfertocustomer'],
                2,
                $currency
            );

            $adminEmail= $this->getDefaultTransEmailId();
            $adminUsername = 'Admin';
            $senderInfo = [];
            $receiverInfo = [];

            $receiverInfo = [
                'name' => $customer->getName(),
                'email' => $customer->getEmail(),
            ];
            $senderInfo = [
                'name' => $adminUsername,
                'email' => $adminEmail,
            ];
            $this->_tempId = $this->getMonthlystatementTemplateId();
            $this->_inlineTranslation->suspend();
            $this->generateTemplate(
                $emailTempVariables,
                $senderInfo,
                $receiverInfo
            );
            $transport = $this->_transportBuilder->getTransport();
            $transport->sendMessage();
            $this->_inlineTranslation->resume();
        } catch (\Exception $e) {
            $this->logger->info($e->getMessage());
        }
    }
    public function sendMailForTransaction($customerId, $params, $store, $amount)
    {
        $type = $params['type'];
        $action = $params['action'];
        $customer = $this->_customerModel
            ->create()
            ->load($customerId);
        if (array_key_exists('sender_id', $params) && $params['sender_id']>0 && $params['type']==4) {
            $sender = $this->_customerModel
                ->create()
                ->load($params['sender_id']);
            $params['sender'] = $sender->getName();
        }
        $this->sendEmailToCustomer($customer, $params, $store, $type, $action);
        $this->sendEmailToAdmin($customer, $params, $store, $type, $action);


        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $customerFactory = $objectManager->get('\Magento\Customer\Model\CustomerFactory')->create();
        $customer = $customerFactory->load($customerId);
        $shippingAddressId = $customer->getDefaultShipping();
        $address = $objectManager->get('Magento\Customer\Model\AddressFactory')->create()->load($shippingAddressId);
        $mobilenumbers = $address->getTelephone();

        $url = 'https://control.msg91.com/api/sendhttp.php';
        $authkey = '291848AasSUPYxNFR5d68c505';
        $senderid = 'DOODLE';
        $message = 'Hey '.$customer->getName().', Congratulations! Rs.'.$amount.' credited to your Doodle Wallet. We would love to serve you again! Order now - www.doodlecollection.com';

        $ch = curl_init();
        if (!$ch)
        {
            return "Couldn't initialize a cURL handle";
        }
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt ($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt ($ch, CURLOPT_POSTFIELDS,"authkey=$authkey&mobiles=$mobilenumbers&message=$message&sender=$senderid&route=4&country=91");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $curlresponse = curl_exec($ch); // execute
        if(curl_errno($ch))
        {
            echo '<pre>';
            print_r('Error: '.curl_error($ch)); 
            echo '</pre>';
        }
        curl_close($ch);

        
    }
    public function sendEmailToAdmin($customer, $params, $store, $type, $action)
    {
        $emailTemplateId = $this->getMailTemplateForTransactionForAdmin($type, $action);
        try {
            $emailTempVariables = $params;
            $emailTempVariables['customername'] = $customer->getName();
            $emailTempVariables['store'] = $store;

            $adminEmail= $this->getDefaultTransEmailId();
            $adminUsername = 'Admin';
            $senderInfo = [];
            $receiverInfo = [];

            $receiverInfo = [
                'name' => $adminUsername,
                'email' => $adminEmail,
            ];
            $senderInfo = [
                'name' => $adminUsername,
                'email' => $adminEmail,
            ];
            $this->_tempId = $emailTemplateId;
            $this->_inlineTranslation->suspend();
            $this->generateTemplate($emailTempVariables, $senderInfo, $receiverInfo);
            $transport = $this->_transportBuilder->getTransport();
            $transport->sendMessage();
            $this->_inlineTranslation->resume();
        } catch (\Exception $e) {
            $this->_messageManager->addError($e->getMessage());
        }
    }
    public function sendEmailToCustomer($customer, $params, $store, $type, $action)
    {
        $emailTemplateId = $this->getMailTemplateForTransactionForCustomer($type, $action);
        try {
            $emailTempVariables = $params;
            $emailTempVariables['customername'] = $customer->getName();
            $emailTempVariables['store'] = $store;

            $adminEmail= $this->getDefaultTransEmailId();
            $adminUsername = 'Doodle Collection';
            $senderInfo = [];
            $receiverInfo = [];

            $receiverInfo = [
                'name' => $customer->getName(),
                'email' => $customer->getEmail(),
            ];
            $senderInfo = [
                'name' => $adminUsername,
                'email' => $adminEmail,
            ];
            $this->_tempId = $emailTemplateId;
            $this->_inlineTranslation->suspend();
            $this->generateTemplate(
                $emailTempVariables,
                $senderInfo,
                $receiverInfo
            );
            $transport = $this->_transportBuilder->getTransport();
            $transport->sendMessage();
            $this->_inlineTranslation->resume();



        } catch (\Exception $e) {
            $this->_messageManager->addError($e->getMessage());
        }
    }
    public function getMailTemplateForTransactionForCustomer($type, $action)
    {
        $walletTransaction = $this->_walletTransaction->create();
        if ($type == $walletTransaction::Order_PLACE_TYPE) {
            if ($action == 'credit') {
                return $this->getWalletRechargeTemplateIdForCustomer();
            } else {
                return $this->getWalletUsedTemplateIdForCustomer();
            }
        } elseif ($type == $walletTransaction::CASH_BACK_TYPE) {
            if ($action == 'credit') {
                return $this->getWalletCashbackTemplateIdForCustomer();
            } else {

            }
        } elseif ($type == $walletTransaction::REFUND_TYPE) {
            if ($action == 'credit') {
                return $this->getWalletOrderRefundTemplateIdForCustomer();
            } else {
                return $this->getWalletAmountRefundTemplateIdForCustomer();
            }
        } elseif ($type == $walletTransaction::ADMIN_TRANSFER_TYPE) {
            if ($action == 'credit') {
                return $this->getAdminCreditAmountTemplateIdForCustomer();
            } else {
                return $this->getAdminDebitAmountTemplateIdForCustomer();
            }
        } elseif ($type == $walletTransaction::CUSTOMER_TRANSFER_TYPE) {
            if ($action == 'credit') {
                return $this->getCustomerCreditAmountTemplateIdForCustomer();
            } else {
                return $this->getCustomerDebitAmountTemplateIdForCustomer();
            }
        }
    }
    public function getMailTemplateForTransactionForAdmin($type, $action)
    {
        $walletTransaction = $this->_walletTransaction->create();
        if ($type == $walletTransaction::Order_PLACE_TYPE) {
            if ($action == 'credit') {
                return $this->getWalletRechargeTemplateIdForAdmin();
            } else {
                return $this->getWalletUsedTemplateIdForAdmin();
            }
        } elseif ($type == $walletTransaction::CASH_BACK_TYPE) {
            if ($action == 'credit') {
                return $this->getWalletCashbackTemplateIdForAdmin();
            } else {

            }
        } elseif ($type == $walletTransaction::REFUND_TYPE) {
            if ($action == 'credit') {
                return $this->getWalletOrderRefundTemplateIdForAdmin();
            } else {
                return $this->getWalletAmountRefundTemplateIdForAdmin();
            }
        } elseif ($type == $walletTransaction::ADMIN_TRANSFER_TYPE) {
            if ($action == 'credit') {
                return $this->getAdminCreditAmountTemplateIdForAdmin();
            } else {
                return $this->getAdminDebitAmountTemplateIdForAdmin();
            }
        } elseif ($type == $walletTransaction::CUSTOMER_TRANSFER_TYPE) {
            if ($action == 'credit') {
                return $this->getCustomerCreditAmountTemplateIdForAdmin();
            } else {
                return $this->getCustomerDebitAmountTemplateIdForAdmin();
            }
        }
    }
}
