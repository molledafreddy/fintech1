<?php
header("Content-disposition: attachment; filename=Hipercubo.pdf");
header("Content-type: application/pdf");
readfile("/opt/lampp/htdocs/fintech/public/TransferOtherBanks_1517577448_1293822584.txt");
?>