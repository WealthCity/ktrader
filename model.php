<?php

/**
 * @author Kyle Thielk
 * Model contains much of the functionality and processing
 * that our backend system needs to display valuable information
 * to front end.
 *
 * NOTE: This is currently a catch all model.php file. We might in the future
 * need to break it up to sub files i.e report_model.php, admin_model.php etc...
 **/


require_once ("includes/login_session.php");
require_once ("classes.php");

/**
 * If we are not logged in, will show log in form.
 * @return Boolean True if we are not logged in yet. False if we are.
 * */
function show_LoginForm()
{
    global $session;
    global $form;
    
    if($session->logged_in)
    {
        return false;
    }
    else
    {
        if($form->num_errors > 0)
        {
            echo "<font size=\"2\" color=\"#ff0000\">".$form->num_errors." error(s) found</font>";
        }
        echo '
        <h1>Please <span class="green">Login</span></h1>
        <form action="login_process.php" method="POST">
        <p>
        <label>Username</label>
        <input type="text" name="user" maxlength="30" value="'.$form->value("user").'"></td><td>'.$form->error("user").'
        <label>Password</label>
        <input type="password" name="pass" maxlength="30" value="'.$form->value("pass").'"></td><td>'.$form->error("pass").'
        <label>Remember Me?</label>
        <input type="checkbox" name="remember"';
        if($form->value("remember") != "")
        {
            echo "checked";
        }
        echo '>
        <font size="2">Yes &nbsp;&nbsp;&nbsp;&nbsp;
        <input type="hidden" name="sublogin" value="1">
        <input type="submit" value="Login">
        <!-- <tr><td colspan="2" align="left"><br><font size="2">[<a href="login_forgotpass.php">Forgot Password?</a>]</font></td><td align="right"></td></tr> -->
        <!-- <tr><td colspan="2" align="left"><br>Not registered? <a href="login_register.php">Sign-Up!</a></td></tr> -->
        </p>
        </form>
        ';
    }
    return true;
}
/**
 * Determine if current user is logged in.
 * @return Boolean True if logged in, False if logged out.
 * */
function is_logged_in()
{
    return $session->logged_in;
}
/**
 * This function will build a list of all stocks starting with $letter.
 * @param String $letter The letter that all stock tickers should start with.
 * @return String HTML representation of Stock List.
 */ 
function get_stock_list($letter, $cap, $stock_price, $eps, $ppe)
{
    $stockTable = '
    <table style="border-color: #A5A5A5; border-width: 1px 1px 0 0; border-style: solid" width="720" cellpadding="4" cellspacing="0">
        <tr>
            <td width="90" bgcolor="#DDDBDB" style="border-color: #A5A5A5; border-width: 0 0 1px 1px; border-style: solid"><b>Stock Ticker</b></td>
            <td width="150" bgcolor="#DDDBDB" style="border-color: #A5A5A5; border-width: 0 0 1px 1px; border-style: solid"><b>Stock Name</b></td>
            <td width="120" bgcolor="#DDDBDB" style="border-color: #A5A5A5; border-width: 0 0 1px 1px; border-style: solid"><b>Last Trade</b></td>
            <td width="120" bgcolor="#DDDBDB" style="border-color: #A5A5A5; border-width: 0 0 1px 1px; border-style: solid"><b>Earnings/Share</b></td>
            <td width="120" bgcolor="#DDDBDB" style="border-color: #A5A5A5; border-width: 0 0 1px 1px; border-style: solid"><b>Price/Earings</b></td>
            <td width="120" bgcolor="#DDDBDB" style="border-color: #A5A5A5; border-width: 0 0 1px 1px; border-style: solid"><b>Market Cap</b></td>
        </tr>';
        
    $query = mysql_query(build_stock_list_query($letter, $cap, $stock_price,$eps,$ppe));
    while ($array = mysql_fetch_array($query))
    {
        $stock = new Stock($array, false);
        $stockTable .= '<tr>
                        <td width="15%" style="border-color: #A5A5A5; border-width: 0 0 1px 1px; border-style: solid">
                            <a href="stock_info.php?s='.$stock->getTicker().'">'.$stock->getTicker() . '</a>
                        </td>
                        <td width="25%" style="border-color: #A5A5A5; border-width: 0 0 1px 1px; border-style: solid">
                            '.$stock->getName() . '
                        </td>
                        <td width="20%" style="border-color: #A5A5A5; border-width: 0 0 1px 1px; border-style: solid">
                            $'.$stock->getLastTrade() . '
                        </td>
                        <td width="20%" style="border-color: #A5A5A5; border-width: 0 0 1px 1px; border-style: solid">
                            $'.$stock->getEarningsPerShare() . '
                        </td>
                        <td width="20%" style="border-color: #A5A5A5; border-width: 0 0 1px 1px; border-style: solid">
                            $'.$stock->getPricePerEarnings() . '
                        </td>
                         <td width="20%" style="border-color: #A5A5A5; border-width: 0 0 1px 1px; border-style: solid">
                            $'.$stock->getMarketCap(). 'B
                        </td>
                        </tr>
                        ';
    }
    $stockTable .= '</table>';
    return $stockTable;
}
/**
 * Helper function that simply analyzes parameters to determine how to build query
 * for our stock list.
 * */
function build_stock_list_query($letter, $cap, $stock_price, $eps, $ppe)
{
    $query = "SELECT * FROM stocks WHERE SUBSTRING(ticker, 1, 1) = '".strtoupper($letter)."' AND last_trade > 0.01";
    
    if($cap != "")
    {
        $small = 0;
        $large = 0;
        if($cap == NANO_CAP)
        {
            $small = 0;
            $large = 0.05;
        }
        else if($cap == MICRO_CAP)
        {
            $small = 0.05;
            $large = 0.3;
        }
        else if($cap == SMALL_CAP)
        {
            $small = 0.3;
            $large = 2.0;
        }
        else if($cap == MID_CAP)
        {
            $small = 2.0;
            $large = 10.0;
        }
        else if($cap == LARGE_CAP)
        {
            $small = 10.0;
            $large = 200.0;
        }
        $query .= " AND market_cap > '".$small."' AND market_cap <= '".$large."'";
    }
    if($stock_price != "")
    {
        $query .= " ORDER BY last_trade " . $stock_price;
    }
    else if($eps != "")
    {
        $query .= " ORDER BY earnings_per_share " . $eps;
    }
    else if($ppe != "")
    {
        $query .= " ORDER BY price_per_earnings " . $ppe;
    }
    return $query;
}
/**
 * Given a ticker, get our Stock Class instance.
 * */
function get_stock($ticker)
{
    $stock = new Stock($ticker);
    if($stock->getId() < 0)
    {
        return;
    }
    return $stock;
}
/**
 * Smartly update daily data for stock. WIll only get updates we don't have.
 * @param String Ticker for stock we are updating.
 **/
function update_stock_daily($ticker)
{
    
    $stock = new Stock($ticker);
    if($stock->getId() < 0)
    {
        return false;
    }
    //Only update stocks that haven't been updated in 16 hours.
    if((time() - $stock->getDailyDataDate()) < (TIME_HOUR * 16))
    {
        return false;
    }
   
    //Lets figure out the last stock update we have
    $starting_date = 0;
    if($stock->getDailyData() == null)
    {
        $starting_date = time() - TIME_YEAR * 25;
    }
    else
    {
        $date_array = $stock->getDailyData();
        $starting_date = $date_array[0]->getDate() + TIME_DAY;
    }
    $starting_day = date('d',$starting_date);
    $starting_month = date('m', $starting_date) - 1;
    $starting_year = date('Y', $starting_date);
    $now_day = date('d', time() + TIME_DAY);
    $now_month = date('m',time() + TIME_DAY) - 1;
    $now_year = date('Y',time() + TIME_DAY);
    //No need to update.
    if($starting_day == $now_day && $starting_month == $now_month && $starting_year == $now_year)
    {
        return false;
    }

    $url = 'http://ichart.finance.yahoo.com/table.csv?s='.$ticker.'&d='.$now_month.'&e='.$now_day.'&f='.$now_year.'&g=d&a='.$starting_month.'&b='.$starting_day.'&c='.$starting_year.'&ignore=.csv';

    //Set timeout for fetching url
    $ctx = stream_context_create(array(
    'http' => array(
        'timeout' => 5
        )
    )
    );
    
    $file = @file_get_contents($url,0,$ctx);
    if($file == false)
    {
        return false;
    }
    $line_array = explode("\n",$file);
    
    //Each line contains one day's worth of data.
    foreach ($line_array as $i => $value)
    {
        if($i == 0)
        {
            //do nothing
        }
        else
        {
            
            $daily = parse_daily_data($value);
            if($daily != null)
            {
                $daily->setTickerId($stock->getId());
                $daily->persist();
            }
            
        }
    }
    //Set time we updated this stock.
    $stock->setDailyDataDate(time());
    //Persist so DB has this latest update.
    $stock->persist();
    return true;
    
    
}
/**
 * Given a line from Yahoo Finance download of stock data, this will parse it and
 * return a new DailyData instance.
 * */
function parse_daily_data($line)
{
    //Remove any quotes
    $line = str_replace('"','',$line);
    //Split into array
    $arr = explode(',',$line);
    
    if(count($arr) < 3)
    {
        return null;
    }
    $daily = new DailyData(null);
    
    $daily->setDate(strtotime($arr[0]));
    $daily->setOpen($arr[1]);
    $daily->setHigh($arr[2]);
    $daily->setLow($arr[3]);
    $daily->setClose($arr[4]);
    $daily->setVolume($arr[5]);
    $daily->setAdjustedClose($arr[6]);
    
    return $daily;
}
/**
 * Will update details about stock including EPS/PPE/Stock Price/Capitilization etc...
 * from Yahoo Finance. Can call many times, but will only actually ping yahoo if its been
 * 16 hours since last update.
 * */
function update_stock_info($ticker)
{
    $stock = new Stock($ticker, false);
    if($stock->getId() < 0)
    {
        return false;
    }
    //Only update stocks that haven't been updated in 16 hours.
    if((time() - $stock->getInfoDate()) < (TIME_HOUR * 16))
    {
        return false;
    }
    /**
     *s = stock, n = name, l1 = last trade, e = earnings per share, r = price per earings ration
     *x = stock exchange, t8 = 1 yr target price, j1 = market cap
     */
    $url = 'http://finance.yahoo.com/d/quotes.csv?s='.$stock->getTicker().'&f=snl1erxt8j1';
    //Set timeout for fetching url
    $ctx = stream_context_create(array(
    'http' => array(
        'timeout' => 3
        )
    )
    );
    
    $file = @file_get_contents($url,0,$ctx);
    if($file == false)
    {
        return false;
    }
    /**
     *$array[0] = Ticker
     *$array[1] = Name
     *$array[2] = Last Trade Price
     *$array[3] = Earnings/Share
     *$array[4] = Price/Earnings
     *$array[5] = Stock Exchange
     *$array[6] = Year Target Price
     *$array[7] = Market Cap
     */
    //File should only be one line, explode into array
    $array = explode(',',$file);

    //No Name means stock doesn't exist.
    if($array[1] == "")
    {
        $stock->setStatus(STATUS_INACTIVE);
        $stock->persist();
        return false;
    }
    $stock->setName(addslashes(str_replace('"','',$array[1])));
    $stock->setLastTrade($array[2]);
    $stock->setEarningsPerShare($array[3]);
    $stock->setPricePerEarnings($array[4]);
    
    $array[5] = strtoupper(str_replace('"','',$array[5]));
    if($array[5] == "")
    {
        //5 is our index N/A
        $stock->setStockExchangeId(5);
    }
    else
    {
        //Check to see if stock exchange exists, if it doesnt insert into database.
        $query = mysql_query("SELECT * FROM exchanges WHERE ticker = '".$array[5]."'");
        if(mysql_num_rows($query) < 1)
        {
            mysql_query("INSERT INTO exchanges
                        (ticker)
                        VALUES
                        ('".$array[5]."')");
            $stock->setStockExchangeId(mysql_insert_id());
        }
        else
        {
            $mysql_array = mysql_fetch_array($query);
            $stock->setStockExchangeId($mysql_array['id']);
        }
    }
    $stock->setTargetPrice($array[6]);
    //Get rid of any whitespace
    $array[7] = trim($array[7]);
    
    if(substr($array[7],strlen($array[7]) - 1, 1) == 'M')
    {
        $stock->setMarketCap($array[7]/1000);
    }
    else if(substr($array[7],strlen($array[7]) - 1, 1) == 'K')
    {
        $stock->setMarketCap($array[7]/1000000);
    }
    else
    {
        $stock->setMarketCap($array[7]/1);
    }
    //Set time of this update, so we dont' do it again for another 16 hrs minimum.
    $stock->setInfoDate(time());
    $stock->persist();
    return true;
}
/**
 * Will only deactivate if latest trade was 0 and last 5 days was also 0.
 */ 
function deactivateStock($stock_id)
{
    $stock = new Stock($stock_id, false);
    
    //Make sure stock exists.
    if($stock->getId() < 0)
    {
        return "";
    }
    //Don't do anything if already inactive
    if($stock->getStatus() == STATUS_INACTIVE)
    {
        return $stock->getTicker() . "(".$stock->getId().") was already inactive.";
    }
    if(update_stock_info($stock->getTicker()))
    {
        //Only need to get new data if was updated.
        $stock = new Stock($stock_id, false);
    }
    if($stock->getLastTrade() > 0.05)
    {
        return "";
    }
    update_stock_daily($stock->getTicker());

    $stock = new Stock($stock_id, true);

    $arr = $stock->getDailyData();

    if(sizeof($arr) < 5)
    {
        //If in here, we don't have enough data which means stock is probably a weird derivative.
        $stock->setStatus(STATUS_INACTIVE);
        $stock->persist();
        return "Removed Stock " . $stock->getTicker() . " (".$stock->getId().") due to not enough daily data.";
    }
    //Make sure has been less than 0.05 for quite some time.
    if( $arr[0]->getClose() < 0.05
       && $arr[1]->getClose() < 0.05
       && $arr[2]->getClose() < 0.05
       && $arr[3]->getClose() < 0.05)
    {
        $stock->setStatus(STATUS_INACTIVE);
        $stock->persist();
        return "Removed Stock " . $stock->getTicker() . " (".$stock->getId().") due to a last closing price of $".$stock->getLastTrade();
    }
    return "";
}
/**
 * Will return an HTML formatted list of all portfolio's in our system.
 */ 
function get_portfolio_list()
{
    $portfolioTable = '
    <table class="table" width="720" cellspacing="0">
        <tr>
            <td width="75" class="table_header"><b>Name</b></td>
            <td width="60" class="table_header"><b>Open Trades</b></td>
            <td width="60" class="table_header"><b>Closed Trades</b></td>
            <td width="60" class="table_header"><b>Opening Capital</b></td>
            <td width="60" class="table_header"><b>Account Value</b></td>
            <td width="60" class="table_header"><b>Current Cash</b></td>
            <td width="60" class="table_header"><b>Net Gain/Loss</b></td>
        </tr>';
    
    
    $query = mysql_query("SELECT * FROM portfolio ORDER BY name ASC");
    while ($array = mysql_fetch_array($query))
    {
        $portfolio = new Portfolio($array);
        $portfolio->runCapitalReport();
        $portfolioTable .= '
        <tr>
            <td width="75" class="table_row"><a href="portfolio.php?portfolio_id='.$portfolio->id.'">'.$portfolio->name.'</a></td>
            <td width="60" class="table_row">'.$portfolio->getNumOpenTrades().'</td>
            <td width="60" class="table_row">'.$portfolio->getNumClosedTrades().'</td>
            <td width="60" class="table_row">$'.round($portfolio->opening_capital,2).'</td>
            <td width="60" class="table_row">$'.round($portfolio->account_value,2).'</td>
            <td width="60" class="table_row">$'.round($portfolio->current_cash,2).'</td>
            <td width="60" class="table_row">$'.round($portfolio->account_value - $portfolio->opening_capital,2).'</td>
        </tr>
        ';        
    }
    $portfolioTable .= '</table>';
    
    return $portfolioTable;
}
/**
 * @param Mixed $stock Can be either a stock ID or an instance of stock class.
 * @return HTML formatted list of trades attached to this stock.
 **/ 
function get_trade_list_from_stock($stock, $close_trade_id = -1)
{
    if(is_numeric($stock))
    {
        $stock = new Stock($stock);
    }
    $tradeTable = '
    <input type="submit" value="Close Trades" onclick="trade_closeList();return false;" />
    <h2>Open Trades</h2>
    <table class="table" width="720" cellspacing="0">
        <tr>
            <td width="75" class="table_header"><b>Date</b></td>
            <td width="60" class="table_header"><b># of Stocks</b></td>
            <td width="60" class="table_header"><b>Bought Price</b></td>
            <td width="60" class="table_header"><b>Current Price</b></td>
            <td width="60" class="table_header"><b>Gain/Loss</b></td>
            <td width="60" class="table_header"><b>Portfolio</b></td>
            <td width="60" class="table_header"><b>Action</b></td>
        </tr>
    ';
    $query = mysql_query("SELECT * FROM trades WHERE stock_id = '".$stock->getId()."' AND sold_price < 0.01 ORDER BY bought_date DESC");
    if(mysql_num_rows($query) < 1)
    {
        $tradeTable .= '<tr><td width="100%" class="table_row" colspan="7">No Open Trades</td></tr>';
    }
    while ($array = mysql_fetch_array($query))
    {
        $trade = new Trade($array);
        $ticker = $trade->stock->getTicker();
        update_stock_info($ticker);
        $trade->stock = new Stock($ticker);
        $portfolio = new Portfolio($trade->portfolio_id);
        if($close_trade_id == $trade->id)
        {
            $tradeTable .= '
            <tr>
                <td width="75" class="table_row_bottomless">'.$trade->bought_date_string.'</td>
                <td width="60" class="table_row_bottomless">'.$trade->number_of_stocks.'</td>
                <td width="60" class="table_row_bottomless">'.$trade->bought_price.'</td>
                <td width="60" class="table_row_bottomless">'.$trade->stock->getLastTrade().'</td>
                <td width="60" class="table_row_bottomless">'.round((($trade->stock->getLastTrade() - $trade->bought_price) * $trade->number_of_stocks),2).'</td>
                <td width="60" class="table_row_bottomless">'.$portfolio->name.'</td>
                <td width="60" class="table_row_bottomless"><input type="submit" value="Cancel" onClick="trade_tradeStockList('.$stock->getId().',-1);return false;"</td>
            </tr>';
            $tradeTable .= '
            <tr>
                <td width="100%" colspan="7" class="table_row">
                    <form action="stock_info.php?do=closeTrade&s='.$trade->stock->getTicker().'" method="POST">
                        <p>
                            <input type="hidden" value="'.$trade->id.'" name="trade_id" id="trade_id"/>
                            <label>Close Date</label>
                            <input type="text" id="sold_date_string" name="sold_date_string" />
                            <label>Close Price</label>
                            <input type="text" id="sold_price" name="sold_price" />
                            <label>Save</label>
                            <input type="submit" value="Close Trades" />
                        </p>
                    </form>
                </td>
            </tr>
            ';
        }
        else
        {
            $tradeTable .= '
            <tr>
                <td width="75" class="table_row">'.$trade->bought_date_string.'</td>
                <td width="60" class="table_row">'.$trade->number_of_stocks.'</td>
                <td width="60" class="table_row">'.$trade->bought_price.'</td>
                <td width="60" class="table_row">'.$trade->stock->getLastTrade().'</td>
                <td width="60" class="table_row">'.round((($trade->stock->getLastTrade() - $trade->bought_price) * $trade->number_of_stocks),2).'</td>
                <td width="60" class="table_row">'.$portfolio->name.'</td>
                <td width="60" class="table_row"><input type="submit" value="Close" onClick="trade_tradeStockList('.$stock->getId().','.$trade->id.');return false;"</td>
            </tr>';    
        }
        
        
    }
    $tradeTable .= '</table>';
    $tradeTable .= '
    <h2>Closed Trades</h2>
    <table class="table" width="720" cellspacing="0">
        <tr>
            <td width="75" class="table_header"><b>Date Bought</b></td>
            <td width="60" class="table_header"><b># of Stocks</b></td>
            <td width="60" class="table_header"><b>Bought Price</b></td>
            <td width="60" class="table_header"><b>Sold Price</b></td>
            <td width="60" class="table_header"><b>Sold Date</b></td>
            <td width="60" class="table_header"><b>Gain/Loss</b></td>
            <td width="60" class="table_header"><b>Portfolio</b></td>
        </tr>
    ';
    
    $query = mysql_query("SELECT * FROM trades WHERE stock_id = '".$stock->getId()."' AND sold_price > 0.00 ORDER BY bought_date DESC");
    if(mysql_num_rows($query) < 1)
    {
        $tradeTable .= '<tr><td width="100%" class="table_row" colspan="8">No Closed Trades</td></tr>';
    }
    while ($array = mysql_fetch_array($query))
    {
        $trade = new Trade($array);
        $portfolio = new Portfolio($trade->portfolio_id);

        $tradeTable .= '
        <tr>
            <td width="75" class="table_row">'.$trade->bought_date_string.'</td>
            <td width="60" class="table_row">'.$trade->number_of_stocks.'</td>
            <td width="60" class="table_row">'.$trade->bought_price.'</td>
            <td width="60" class="table_row">'.$trade->sold_price.'</td>
            <td width="60" class="table_row">'.$trade->sold_date_string.'</td>
            <td width="60" class="table_row">'.round( ($trade->sold_price - $trade->bought_price)*$trade->number_of_stocks,2).'</td>
            <td width="60" class="table_row">'.$portfolio->name.'</td>
        </tr>';
    }
    $tradeTable .= '</table>';
    return $tradeTable;
}
/**
 * @param Mixed $stock Can be either a stock ID or an instance of stock class.
 * @return HTML formatted list of trades attached to this stock.
 **/ 
function get_trade_list_from_portfolio($portfolio, $close_trade_id = -1)
{
    if(is_numeric($portfolio))
    {
        $portfolio = new Portfolio($portfolio);
    }
    $tradeTable = '
    
    <h2>Open Trades</h2>
    <table class="table" width="720" cellspacing="0">
        <tr>
            <td width="75" class="table_header"><b>Date</b></td>
            <td width="60" class="table_header"><b># of Stocks</b></td>
            <td width="60" class="table_header"><b>Bought Price</b></td>
            <td width="60" class="table_header"><b>Current Price</b></td>
            <td width="60" class="table_header"><b>Gain/Loss</b></td>
            <td width="60" class="table_header"><b>Portfolio</b></td>
            <td width="60" class="table_header"><b>Action</b></td>
        </tr>
    ';
    $query = mysql_query("SELECT * FROM trades WHERE portfolio_id = '".$portfolio->id."' AND sold_price < 0.01 ORDER BY bought_date DESC");
    if(mysql_num_rows($query) < 1)
    {
        $tradeTable .= '<tr><td width="100%" class="table_row" colspan="7">No Open Trades</td></tr>';
    }
    while ($array = mysql_fetch_array($query))
    {
        $trade = new Trade($array);
        $ticker = $trade->stock->getTicker();
        update_stock_info($ticker);
        $trade->stock = new Stock($ticker);
        
        if($close_trade_id == $trade->id)
        {
            $tradeTable .= '
            <tr>
                <td width="75" class="table_row_bottomless">'.$trade->bought_date_string.'</td>
                <td width="60" class="table_row_bottomless">'.$trade->number_of_stocks.'</td>
                <td width="60" class="table_row_bottomless">'.$trade->bought_price.'</td>
                <td width="60" class="table_row_bottomless">'.$trade->stock->getLastTrade().'</td>
                <td width="60" class="table_row_bottomless">'.round((($trade->stock->getLastTrade() - $trade->bought_price) * $trade->number_of_stocks),2).'</td>
                <td width="60" class="table_row_bottomless">'.$portfolio->name.'</td>
                <td width="60" class="table_row_bottomless"><input type="submit" value="Cancel" onClick="trade_tradePortfolioList('.$portfolio->id.',-1);return false;"</td>
            </tr>';
            $tradeTable .= '
            <tr>
                <td width="100%" colspan="7" class="table_row">
                    <form action="portfolio.php?do=closeTrade&portfolio_id='.$portfolio->id.'" method="POST">
                        <p>
                            <input type="hidden" value="'.$trade->id.'" name="trade_id" id="trade_id"/>
                            <label>Close Date</label>
                            <input type="text" id="sold_date_string" name="sold_date_string" />
                            <label>Close Price</label>
                            <input type="text" id="sold_price" name="sold_price" />
                            <label>Save</label>
                            <input type="submit" value="Close Trades" />
                        </p>
                    </form>
                </td>
            </tr>
            ';
        }
        else
        {
            $tradeTable .= '
            <tr>
                <td width="75" class="table_row">'.$trade->bought_date_string.'</td>
                <td width="60" class="table_row">'.$trade->number_of_stocks.'</td>
                <td width="60" class="table_row">'.$trade->bought_price.'</td>
                <td width="60" class="table_row">'.$trade->stock->getLastTrade().'</td>
                <td width="60" class="table_row">'.round((($trade->stock->getLastTrade() - $trade->bought_price) * $trade->number_of_stocks),2).'</td>
                <td width="60" class="table_row">'.$portfolio->name.'</td>
                <td width="60" class="table_row"><input type="submit" value="Close" onClick="trade_tradePortfolioList('.$portfolio->id.','.$trade->id.');return false;"</td>
            </tr>';    
        }
        
        
    }
    $tradeTable .= '</table>';
    $tradeTable .= '
    <h2>Closed Trades</h2>
    <table class="table" width="720" cellspacing="0">
        <tr>
            <td width="75" class="table_header"><b>Date Bought</b></td>
            <td width="60" class="table_header"><b># of Stocks</b></td>
            <td width="60" class="table_header"><b>Bought Price</b></td>
            <td width="60" class="table_header"><b>Sold Price</b></td>
            <td width="60" class="table_header"><b>Sold Date</b></td>
            <td width="60" class="table_header"><b>Gain/Loss</b></td>
            <td width="60" class="table_header"><b>Portfolio</b></td>
        </tr>
    ';
    
    $query = mysql_query("SELECT * FROM trades WHERE portfolio_id = '".$portfolio->id."' AND sold_price > 0.00 ORDER BY bought_date DESC");
    if(mysql_num_rows($query) < 1)
    {
        $tradeTable .= '<tr><td width="100%" class="table_row" colspan="8">No Closed Trades</td></tr>';
    }
    while ($array = mysql_fetch_array($query))
    {
        $trade = new Trade($array);

        $tradeTable .= '
        <tr>
            <td width="75" class="table_row">'.$trade->bought_date_string.'</td>
            <td width="60" class="table_row">'.$trade->number_of_stocks.'</td>
            <td width="60" class="table_row">'.$trade->bought_price.'</td>
            <td width="60" class="table_row">'.$trade->sold_price.'</td>
            <td width="60" class="table_row">'.$trade->sold_date_string.'</td>
            <td width="60" class="table_row">'.round( ($trade->sold_price - $trade->bought_price)*$trade->number_of_stocks,2).'</td>
            <td width="60" class="table_row">'.$portfolio->name.'</td>
        </tr>';
    }
    $tradeTable .= '</table><br /><br />';
    return $tradeTable;
}
?>