<?php
    $action_page = $_REQUEST['action_page'];
    
    echo '
    <form action="'.$action_page.'&do=processTrade" method="POST">
        <p>
            <input type="hidden" name="stock_id" id="stock_id" value="'.$stock->getId().'" />
            <label>Stock Price</label>
            <input type="text" id="bought_price" name="bought_price" onchange="calculateTradeForm();return false;"/>
            <label>Total Purchase Price</label>
            <input type="text" id="total_price" name="total_price" onchange="calculateTradeForm();" />
            <label>Stocks to Purchase</label>
            <input type="text" id="number_of_stocks" name="number_of_stocks" readonly="true"/>
            <label>Date i.e 12 November 2009</label>
            <input type="text" id="bought_date_string" name="bought_date_string" />
            <label>Portfolio</label>
            '.FormBuilder::portfolioCombo('portfolio_id').'
            <label>Save Trade</label>
            <input type="submit" value="Cancel" onclick="trade_hideTrade();return false;" /><input type="submit" value="Add Trade" />
        </p>
    </form>    ';
?>