<?php

namespace Emipro\CodExtracharge\Model\Sales\Order\Pdf;

class Extracharge extends \Magento\Sales\Model\Order\Pdf\Total\DefaultTotal
{
    /**
     * [getTotalsForDisplay description]
     * @return [type] [description]
     */
    public function getTotalsForDisplay()
    {
        parent::getTotalsForDisplay();

        $amount = $this->getSource()->getCodchargeFee();
        $charge = $this->getOrder()->formatPriceTxt($amount);

        $label = __($this->getSource()->getCodchargeFeeName());
        $fontSize = $this->getFontSize() ? $this->getFontSize() : 7;
        $total = ['amount' => $charge, 'label' => $label . ":", 'font_size' => $fontSize];
        return [$total];
    }
}
