<?php

echo '
<div id="sidebar" >
      <h1>Show by Cap</h1>
      <ul class="sidemenu">
        <li><a href="#" onClick="trade_newTrade('.$stock->getId().',\'stock_info.php?s='.$stock->getTicker().'\')">Purchase Stock</a></li>
      </ul>
    </div>
    ';

?>