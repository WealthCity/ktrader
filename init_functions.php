<?php

/**
 * @author Kyle Thielk
 * Shouldn't be used but on a rare occasion. Contains functions used to initially
 * populate the database with stock tickers and some data attached to them.
 **/


/**
 * Use this method to update our master list of stocks. The file supplied should
 * have one stock name per separator.
 * @param String $filename The filename including location
 * @param String $separator The separator used between each stock.
 * @return void Return nothing.
 **/
function update_stocks($filename, $separator)
{
    $file = file_get_contents($filename);
    $sArray = explode($separator,$file);
    
    //Lets first populate an array with all stocks from DB so we don't
    //have to make a DB call for each stock in file.
    $query = mysql_query("SELECT * FROM stocks");
    
    $current_stocks = array();
    
    while($array = mysql_fetch_array($query))
    {
        $current_stocks[$array['name']] = true;
    }
    
    //Now lets cycle through ones to add
    foreach ($sArray as $i => $value)
    {
        if(array_key_exists($value,$current_stocks) == false && $value != "")
        {
            echo "Adding " . $value . " <br />";
            $stock = new Stock(null);
            $stock->setName($value);
            $stock->persist();
        }
        else
        {
            echo "Skipped " . $value . "<br />";    
        }
    }
    
}
function update_all_daily_data()
{
    $status = '';
    $query = mysql_query("SELECT * FROM stocks");
    while($array = mysql_fetch_array($query))
    {
        $stock = new Stock($array);
        if(update_stock_daily($stock->getTicker()))
        {
            $status .= 'Successfully Updated all stock info for ' . $stock->getTicker() . '<br />';
        }
        else
        {
            $status .= 'Failed to update stock ' . $stock->getTicker() . '<br />';    
        }
    }
    return $status;
}
?>