<?php
    require_once("database.php");

echo '
<div id="header">
    <h1 id="logo">Kyle<span class="green">stock</span>Tracker<span class="gray">!</span></h1>
    <h2 id="slogan">6 months at at time....</h2>
    <!--
    <form method="post" class="searchform" action="http://www.free-css.com/">
      <p>
        <input type="text" name="search_query" class="textbox" />
        <input type="submit" name="search" class="button" value="Search" />
      </p>
    </form>
    -->
    <ul>
      <!--<li id="current"><a href="index.php"><span>Home</span></a></li>-->
      <li id="current"><a href="index.php"><span>Home</span></a></li>
      <li><a href="stock_list.php"><span>Stock List</span></a></li>
      <li><a href="#"><span>Reports</span></a></li>
      <li><a href="#"><span>Performance</span></a></li>
      <li><a href="portfolio.php"><span>Portfolios</span></a></li>
      <li><a href="admin.php"><span>Admin</span></a></li>
    </ul>
  </div>
  <div id="content-wrap"> <img src="images/headerphoto.jpg" width="1200" height="120" alt="headerphoto" class="no-border" />
  ';  
?>