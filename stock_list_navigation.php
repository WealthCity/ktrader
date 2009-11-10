<?php

echo '
<div id="sidebar" >
      <h1>Show by Cap</h1>
      <ul class="sidemenu">
        <li><a href="stock_list.php?l='.$letter.'&cap=&price='.$price.'&eps='.$eps.'&ppe='.$ppe.'">All Caps</a></li>
        <li><a href="stock_list.php?l='.$letter.'&cap='.NANO_CAP.'&price='.$price.'&eps='.$eps.'&ppe='.$ppe.'">Nano Cap (<$50m)</a></li>
        <li><a href="stock_list.php?l='.$letter.'&cap='.MICRO_CAP.'&price='.$price.'&eps='.$eps.'&ppe='.$ppe.'">Micro Cap($50m - $300m)</a></li>
        <li><a href="stock_list.php?l='.$letter.'&cap='.SMALL_CAP.'&price='.$price.'&eps='.$eps.'&ppe='.$ppe.'">Small Cap ($300m - $2b)</a></li>
        <li><a href="stock_list.php?l='.$letter.'&cap='.MID_CAP.'&price='.$price.'&eps='.$eps.'&ppe='.$ppe.'">Mid Cap ($2b - $10b)</a></li>
        <li><a href="stock_list.php?l='.$letter.'&cap='.LARGE_CAP.'&price='.$price.'&eps='.$eps.'&ppe='.$ppe.'">Large Cap ($10b - $200b)</a></li>
      </ul>
      <h1>Stock Price</h1>
      <ul class="sidemenu">
        <li><a href="stock_list.php?l='.$letter.'&cap='.$cap.'&price=ASC&eps=&ppe=">Ascending</a></li>
        <li><a href="stock_list.php?l='.$letter.'&cap='.$cap.'&price=DESC&eps=&ppe=">Descending</a></li>
      </ul>
      <h1>Earnings Per Share</h1>
      <ul class="sidemenu">
        <li><a href="stock_list.php?l='.$letter.'&cap='.$cap.'&price=&eps=ASC&ppe=">Ascending</a></li>
        <li><a href="stock_list.php?l='.$letter.'&cap='.$cap.'&price=&eps=DESC&ppe=">Descending</a></li>
      </ul>
      <h1>Price Per Earnings</h1>
      <ul class="sidemenu">
        <li><a href="stock_list.php?l='.$letter.'&cap='.$cap.'&price=&eps=&ppe=ASC">Ascending</a></li>
        <li><a href="stock_list.php?l='.$letter.'&cap='.$cap.'&price=&eps=&ppe=DESC">Descending</a></li>
      </ul>
    </div>
    ';

?>