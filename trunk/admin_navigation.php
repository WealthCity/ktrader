<?php

echo '
<div id="sidebar" >
      <h1>Admin Links</h1>
      <ul class="sidemenu">
        <li><a href="" onClick="admin_updateStockDetails(0); return false;">Update Stock Details</a></li>
        <li><a href="" onClick="admin_updateStockDailyData(0); return false;">Update Stock Daily Data</a></li>
        <li><a href="" onClick="deactivateStocks(); return false;">Find Inactive Stocks</a></li>
      </ul>
    </div>
';

?>