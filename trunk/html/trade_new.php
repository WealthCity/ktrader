<?php
    $action_page = $_REQUEST['action_page'];
    
    echo '
    <form action="'.$action_page.'&do=processTrade" method="POST">
    <label>Purchase Price</label>
    <input type="text" id="bought_price" name="bought_price" />
    <label>Date i.e 12 November 2009</label>
    <input type="text" id="date" name="date" />
    <label>Portfolio</label>
    '.FormBuilder::portfolioCombo('portfolio_id').'
    <label>Save Trade</label>
    <input type="submit" value="Add Trade" />
        ';
?>