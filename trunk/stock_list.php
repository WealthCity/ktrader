<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<?php
	require_once 'model.php';
    error_reporting(0);
    $cap = $_REQUEST['cap'];
    $price = $_REQUEST['price'];
    $eps = $_REQUEST['eps'];
    $ppe = $_REQUEST['ppe'];
    $letter = $_REQUEST['l'];
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Kyle Stock Tracker</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" href="images/ktrader.css" type="text/css" />
<script type="text/javascript" src="ajax.js"></script>
</head>
<body>
<div id="wrap">
    
  <?php include 'header.php'; ?>
  
  <?php include 'stock_list_navigation.php'; ?>
    
    <div id="main">
        
        <div id="content">
        <?php
            
            
			if(show_LoginForm() == false)
			{
                if($letter == "")
                {
                    $letter = "a";
                }
				$stockTable = get_stock_list($letter,$cap,$price,$eps,$ppe);
                include 'html/stock_list.php';
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
