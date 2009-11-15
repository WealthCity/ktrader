<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<?php
	require_once 'model.php';
    error_reporting(0);
    
    if($_REQUEST['do'] == "processForm")
    {
        $portfolio = new Portfolio();
        $message = $portfolio->buildFromPost();
        if($message == "")
        {
            $message = "Successfully aded portfolio!";
            include 'html/green_messagebox.php';
            $portfolio->persist();
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
<title>Portfolios - Kyle Stock Tracker</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" href="images/ktrader.css" type="text/css" />
<script type="text/javascript" src="ajax.js"></script>
</head>
<body>
<div id="wrap">
    
  <?php include 'header.php'; ?>
  
  <?php include 'portfolio_navigation.php'; ?>
    
    <div id="main">
        <div id="new_portfolio">
            
        </div>
        
        <div id="content">
        <?php
            
            
			if(show_LoginForm() == false)
			{
                $portfolio_id = $_REQUEST['portfolio_id'];
                if(is_numeric($portfolio_id) && $portfolio_id > -1)
                {
                    $portfolioTable = get_trade_list_from_portfolio($portfolio_id, -1);
                }
                else
                {
                    $portfolioTable = get_portfolio_list();
                }
                include 'html/portfolio.php';
                
			}
		?>
      <!-- Just so can see how to do cool thing.
      <p class="post-footer align-right"> <a href="http://www.free-css.com/" class="readmore">Read more</a> <a href="http://www.free-css.com/" class="comments">Comments (7)</a> <span class="date">Oct 15, 2006</span> </p>
      -->
        </div>
        
    </div>
  </div>
  <?php include 'footer.php'; ?>
</div>
</body>
</html>
