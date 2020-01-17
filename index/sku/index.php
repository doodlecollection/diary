<?php
ini_set('memory_limit', '512M');
set_time_limit(0);
require __DIR__ . '../../app/bootstrap.php';
$bootstrap = \Magento\Framework\App\Bootstrap::create(BP, $_SERVER);
$app = $bootstrap->createApplication('changeskus');
$bootstrap->run($app);
?>