<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<?php
	require_once 'model.php';
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
  
  <?php include 'admin_navigation.php'; ?>
    
    <div id="main">
        <div id="adminButtons"></div>
        <div id="status">
        <?php
			if(show_LoginForm() == false)
			{
				echo 'Please selected option from left.';
			}
		?>
        </div>
        <div id="message"></div>
     
    </div>
    <?php include 'right_bar.php'; ?>
  </div>
  <?php include 'footer.php'; ?>
</div>
</body>
</html>
