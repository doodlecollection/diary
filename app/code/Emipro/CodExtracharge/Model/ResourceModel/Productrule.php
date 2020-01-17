<?php

namespace Emipro\CodExtracharge\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Productrule extends AbstractDb
{
    /**
     * [_construct description]
     * @return [type] [description]
     */
    protected function _construct()
    {
        $this->_init('emipro_codextracharge_products', 'excharge_id');
    }
}
