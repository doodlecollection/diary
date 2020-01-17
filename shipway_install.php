<?php
//php bin/magento setup:upgrade
//echo exec('whoami');
echo "installation started... <br/>";
exec('php bin/magento setup:upgrade');
exec('php bin/magento setup:di:compile');

die("<br/> installation END");
?>
