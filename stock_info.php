<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<?php
    error_reporting (E_ALL ^ E_NOTICE);
    
	require_once 'model.php';
    require_once 'php-ofc-library/open-flash-chart-object.php';
    require_once 'php-ofc-library/open-flash-chart.php';
    require_once 'php-ofc-library/ofc_candle.php';
    
    $ticker = $_REQUEST['s'];
    //update_stock_info($ticker);
    update_stock_daily($ticker);
    update_stock_info($ticker);
	$stock = get_stock($ticker);
    
    $t = $_REQUEST['t'];
    if($t == "")
    {
        $t = 20;
    }
    $tid = $_REQUEST['tid'];
    $sa  = $_REQUEST['sa'];
    $sb  = $_REQUEST['sb'];
    $sc  = $_REQUEST['sc'];
    $sd  = $_REQUEST['sd'];
    $se  = $_REQUEST['se'];
    $chart_url = urlencode('chart.php?t='.$t.'&tid='.$stock->getId().'&sa='.$sa.'&sb='.$sb.'&sc='.$sc.'&sd='.$sd.'&se='.$se);
    
    if($_REQUEST['do'] == 'processTrade')
    {
        $trade = new Trade();
        $message = $trade->buildFromPost();
        if($message == "")
        {
            $trade->persist();
            $message = "Successfully added trade.";
            include 'html/green_messagebox.php';
        }
        else
        {
            include 'html/red_messagebox.php';
        }
    }
    if($_REQUEST['do'] == 'closeTrade')
    {
        $trade_id = $_POST['trade_id'];
        $trade = new Trade($trade_id);
        
        $message = $trade->closeTrade();
        
        if($message == "")
        {
            $trade->persist();
            $message = "Successfully closed trade.";
            include 'html/green_messagebox.php';
        }
        else
        {
            include 'html/red_messagebox.php';
        }
    }
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $ticker .' ($'.$stock->getLastTrade().')'; ?> - Kyle Stock Tracker</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" href="images/ktrader.css" type="text/css" />
<script type="text/javascript" src="ajax.js"></script>
<script type="text/javascript" src="js/swfobject.js"></script>
<script type="text/javascript">
swfobject.embedSWF(
  "open-flash-chart.swf", "my_chart", "750", "350",
  "9.0.0", "expressInstall.swf",
  {"data-file":"<?php echo $chart_url; ?>"}
  );
</script>

</head>
<body>
<div id="wrap">
    
  <?php include 'header.php'; ?>
  
  <?php include 'stock_info_navigation.php'; ?>
    
    <div id="main">
        <div id="new_trade"></div>
        <div id="trade_list"></div>
        
       <?php
			if(show_LoginForm() == false)
			{
                    
                include 'html/stock_info.php';
            }
		?>
        
        <?php
            if($sa == 1){echo '<input type="checkbox" id="sma5" onclick="changeChart(\''.$ticker.'\', '.$t.'); return false;" checked/>SMA5';}
            else{echo '<input type="checkbox" id="sma5" onclick="changeChart(\''.$ticker.'\', '.$t.'); return false;" />SMA5';}
            if($sb == 1){echo '<input type="checkbox" id="sma10" onclick="changeChart(\''.$ticker.'\', '.$t.'); return false;" checked/>SMA10';}
            else{echo '<input type="checkbox" id="sma10" onclick="changeChart(\''.$ticker.'\', '.$t.'); return false;" />SMA10';}
            if($sc == 1){echo '<input type="checkbox" id="sma15" onclick="changeChart(\''.$ticker.'\', '.$t.'); return false;" checked/>SMA15';}
            else{echo '<input type="checkbox" id="sma15" onclick="changeChart(\''.$ticker.'\', '.$t.'); return false;" />SMA15';}
            if($sd == 1){echo '<input type="checkbox" id="sma25" onclick="changeChart(\''.$ticker.'\', '.$t.'); return false;" checked/>SMA25';}
            else{echo '<input type="checkbox" id="sma25" onclick="changeChart(\''.$ticker.'\', '.$t.'); return false;" />SMA25';}
            if($se == 1){echo '<input type="checkbox" id="sma50" onclick="changeChart(\''.$ticker.'\', '.$t.'); return false;" checked/>SMA50';}
            else{echo '<input type="checkbox" id="sma50" onclick="changeChart(\''.$ticker.'\', '.$t.'); return false;" />SMA50';}
            
            echo '<select id="time" onchange="changeChartTime(\''.$ticker.'\',this.value);return false;">';
            if($t == 20){echo '<option value="20" selected>1 Month';}
            else{echo '<option value="20">1 Month';}
            if($t == 60){echo '<option value="60" selected>3 Months';}
            else{echo '<option value="60">3 Months';}
            if($t == 120){echo '<option value="120" selected>6 Months';}
            else{echo '<option value="120">6 Months';}
            if($t == 260){echo '<option value="260" selected>1 Year';}
            else{echo '<option value="260">1 Year';}
            if($t == 1300){echo '<option value="1300" selected>5 Years';}
            else{echo '<option value="1300">5 Years';}
            if($t == 999999999){echo '<option value="999999999" selected>Max';}
            else{echo '<option value="999999999">Max';}
            echo '</select>';
            ?>
        <div bgcolor="#E3E3E3" style="border-width: 1px; border-style: solid; border-color: #E1E1E1; width:750px">
            <div id="my_chart"></div>
        </div>

     
    </div>
    <?php include 'right_bar.php'; ?>
  </div>
  <?php include 'footer.php'; ?>
</div>
</body>
</html>
