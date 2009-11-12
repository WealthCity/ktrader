<?php
    require_once("database.php");
    require_once("classes.php");
    require_once("model.php");
    
    if($_REQUEST['do'] == 'stockList')
    {
        $letter = $_REQUEST['l'];
        $cap = $_REQUEST['cap'];
        $price = $_REQUEST['price'];
        $eps = $_REQUEST['eps'];
        $ppe = $_REQUEST['ppe'];
        
        if($letter == "")
        {
            $letter = "a";
        }
        
       $stockTable = get_stock_list($letter, $cap, $price, $eps, $ppe);
        
       include 'html/stock_list.php';
    }
    else if($_REQUEST['do'] == 'updateStockDetails')
    {
        $start = $_REQUEST['s'];
        if($start == "")
        {
            $start = 0;
        }
        $query = mysql_query("SELECT ticker FROM stocks WHERE status = '".STATUS_ACTIVE."' LIMIT $start,1");
        if(mysql_num_rows($query) < 1)
        {
            echo -1;
            return;
        }
        while($array = mysql_fetch_array($query))
        {
            update_stock_info($array['ticker']);
        }
        $start = $start + 1;
        echo $start;
    }
    else if($_REQUEST['do'] == 'updateStockDailyData')
    {
        $start = $_REQUEST['s'];
        if($start == "")
        {
            $start = 0;
        }
        $query = mysql_query("SELECT ticker FROM stocks WHERE status = '".STATUS_ACTIVE."' LIMIT $start,1") or die(mysql_error() . "DD:".$start);
        if(mysql_num_rows($query) < 1)
        {
            echo -1;
            return;
        }
        while($array = mysql_fetch_array($query))
        {
            update_stock_daily($array['ticker']);
        }
        $start = $start + 1;
        echo $start;
    }
    else if($_REQUEST['do'] == 'deactivateStocks')
    {
        $start = $_REQUEST['s'];
        if($start == -1)
        {
            $query = mysql_query("SELECT id FROM stocks ORDER BY id DESC LIMIT 1");
            $array = mysql_fetch_array($query);
            echo ($array['id'] + 1);
            return;
        }
        
        echo $start . "||" .deactivateStock($start);

    }
    else if($_REQUEST['do'] == 'showNewPortfolio')
    {
        include('html/portfolio_new.php');
    }
?>