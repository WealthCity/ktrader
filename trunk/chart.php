<?php

/**
 * @author Kyle Thielk
 * File will output PHP Open Flash Chart XML which is parsed
 * by their flash client to create fancy graph.
 * */

error_reporting (E_ALL ^ E_NOTICE);

include 'php-ofc-library/open-flash-chart.php';
include 'classes.php';
//////////////////////
// REQUEST VARIABLES

$ticker_id = $_REQUEST['tid'];
$sma_5 = $_REQUEST['sa'];
$sma_10 = $_REQUEST['sb'];
$sma_15 = $_REQUEST['sc'];
$sma_25 = $_REQUEST['sd'];
$sma_50 = $_REQUEST['se'];

$time_period = $_REQUEST['t'];
if($time_period == "" || $time_period == 0 || $time_period < 20){$time_period = 20;}

//Lets make our query
$query = mysql_query("SELECT * FROM daily_data WHERE ticker_id='".$ticker_id."' ORDER BY date DESC LIMIT " . ($time_period + 50));
$query_count = mysql_num_rows($query);
if($query_count < $time_period)
{
    $time_period = $query_count;
}
$title = new title( date("D M d Y") . " - " . $time_period . " days");

//Initialize data
$data = array();
$highest = 0;
$lowest = 1000;
$closes = array();
$count = 0;

//In order to display a pretty chart keep track of our earliest and latest date for X-Axis rendering.
$lowest_date = time();
$highest_date = 0;

while ($array = mysql_fetch_array($query))
{
    $closes[$count] = $array['close'];
    $count = $count + 1;
    //We got more data than we currently need so we can calculate SMA
    if($count < $time_period)
    {
        if($count % 2 == 0 && $time_period <= 40)
        {
            $label_a[] = date("M d",$array['date']);
        }
        else if($count % 4 == 0 && $time_period <= 60)
        {
            $label_a[] = date("M d",$array['date']);
        }
        else if($count % 6 == 0 && $time_period <= 260)
        {
            $label_a[] = date("M d",$array['date']);
        }
         else if($count % 12 == 0 && $time_period <= 520)
        {
            $label_a[] = date("M d, Y",$array['date']);
        }
        else if($count % 260 == 0 && $time_period > 520)
        {
            $label_a[] = date("Y",$array['date']);
        }
        else
        {
            $label_a[] = "";    
        }
        
        //echo $array['date'] . "<br />";
        $data[] = new candle_value(
                number_format($array['high'], 2),
                number_format($array['open'], 2),
                number_format($array['close'], 2),
                number_format($array['low'], 2));
    
        if($array['high'] > $highest)
        {
            $highest = $array['high'];
        }
        if($array['low'] < $lowest)
        {
             $lowest = $array['low'];
        }
        if($array['date'] < $lowest_date)
        {
            $lowest_date = $array['date'];
        }
        if($array['date'] > $highest_date)
        {
             $highest_date =$array['date'];
        }
    }
}
//
// create an X Axis object
$x = new x_axis();
$x->set_grid_colour("#E3E3E3");
//$x->steps(3);
//$x->set_labels_from_array($labels);
// grid line and tick every day

$x->set_steps(TIME_DAY);


$labels = new x_axis_labels();

$label_a = array_reverse($label_a);

$labels->set_labels($label_a);
$labels->text('#date:M d#');
$labels->set_steps(TIME_DAY);


$labels->visible_steps(1);
$labels->rotate(90);

// finally attach the label definition to the x axis
$x->set_labels($labels);
//$x->set_steps(TIME_DAY);

//SMA 50
$sma50 = array();
$sma25 = array();
$sma15 = array();
$sma10 = array();
$sma5 = array();
$sum5 = 0;
$sum10 = 0;
$sum15 = 0;
$sum25 = 0;
$sum50 = 0;
for($i = $time_period + 49; $i >= 0; $i--)
{
    
    $sum50 = $sum50 + $closes[$i];
    if($i < ($time_period))
    {
        $sum50 = $sum50 - $closes[$i + 50];
        $index = $time_period - 1 - $i;
        $sma50[$index] = $sum50 / 50;
        
        if($sma50[$index] < $lowest)
        {
            $lowest = $sma50[$index];
        }
        else if($sma50[$index] > $highest)
        {
            $highest = $sma50[$index];
        }
    }
    
    $sum25 = $sum25 + $closes[$i];
    if($i + 25 < ($time_period + 49))
    {
        $sum25 = $sum25 - $closes[$i + 25];
    }
    
    if($i < ($time_period))
    {
        $index = $time_period - 1 - $i;
        $sma25[$index] = $sum25 / 25;
        
        if($sma25[$index] < $lowest)
        {
            $lowest = $sma25[$index];
        }
        else if($sma25[$index] > $highest)
        {
            $highest = $sma25[$index];
        }
    }
    
    $sum15 = $sum15 + $closes[$i];
    if($i + 15 < ($time_period + 49))
    {
        $sum15 = $sum15 - $closes[$i + 15];
    }
    
    if($i < ($time_period))
    {
        $index = $time_period - 1 - $i;
        $sma15[$index] = $sum15 / 15;
        
        if($sma15[$index] < $lowest)
        {
            $lowest = $sma15[$index];
        }
        else if($sma15[$index] > $highest)
        {
            $highest = $sma15[$index];
        }
    }
    
    $sum10 = $sum10 + $closes[$i];
    if($i + 10 < ($time_period + 49))
    {
        $sum10 = $sum10 - $closes[$i + 10];
    }
    
    if($i < ($time_period))
    {
        $index = $time_period - 1 - $i;
        $sma10[$index] = $sum10 / 10;
        
        if($sma10[$index] < $lowest)
        {
            $lowest = $sma10[$index];
        }
        else if($sma10[$index] > $highest)
        {
            $highest = $sma10[$index];
        }
    }
    
    $sum5 = $sum5 + $closes[$i];
    if($i + 5 < ($time_period + 49))
    {
        $sum5 = $sum5 - $closes[$i + 5];
    }
    
    if($i < ($time_period))
    {
        $index = $time_period - 1 - $i;
        $sma5[$index] = $sum5 / 5;
        
        if($sma5[$index] < $lowest)
        {
            $lowest = $sma5[$index];
        }
        else if($sma5[$index] > $highest)
        {
            $highest = $sma5[$index];
        }
    }
}

$line_sma50_default_dot = new dot();
$line_sma50_default_dot->colour('#1919EA');

$line_sma50 = new line();
$line_sma50->set_default_dot_style($line_sma50_default_dot);
$line_sma50->set_values( $sma50 );
$line_sma50->set_width( 1 );
$line_sma50->set_colour("#1919EA");
$line_sma50->set_key("SMA50",12);

$line_sma25_default_dot = new dot();
$line_sma25_default_dot->colour('#EA1919');

$line_sma25 = new line();
$line_sma25->set_default_dot_style($line_sma25_default_dot);
$line_sma25->set_values( $sma25 );
$line_sma25->set_width( 1 );
$line_sma25->set_colour("#EA1919");
$line_sma25->set_key("SMA25",12);

$line_sma15_default_dot = new dot();
$line_sma15_default_dot->colour('#5E1999');

$line_sma15 = new line();
$line_sma15->set_default_dot_style($line_sma15_default_dot);
$line_sma15->set_values( $sma15 );
$line_sma15->set_width( 1 );
$line_sma15->set_colour("#5E1999");
$line_sma15->set_key("SMA15",12);

$line_sma10_default_dot = new dot();
$line_sma10_default_dot->colour('#DEA325');

$line_sma10 = new line();
$line_sma10->set_default_dot_style($line_sma10_default_dot);
$line_sma10->set_values( $sma10 );
$line_sma10->set_width( 1 );
$line_sma10->set_colour("#DEA325");
$line_sma10->set_key("SMA10",12);

$line_sma5_default_dot = new dot();
$line_sma5_default_dot->colour('#16AAAF');

$line_sma5 = new line();
$line_sma5->set_default_dot_style($line_sma5_default_dot);
$line_sma5->set_values( $sma5 );
$line_sma5->set_width( 1 );
$line_sma5->set_colour("#16AAAF");
$line_sma5->set_key("SMA5",12);

//print_r($data);
//echo "<br /><br />";
$data = array_reverse($data);
//print_r($data);
$candle = new candle('#134871');//9933CC

$candle->set_values($data);
$candle->set_tooltip('#x_label#<br>High: #high#<br>Open: #open#<br>Close: #close#<br>Low: #low#');

$y = new y_axis();

$range = $highest - $lowest;
if($range < 15)
{
    $y->set_range( round($lowest - 1), round($highest + 1), 1);
}
else if($range < 100)
{
    $y->set_range( round($lowest - 1), round($highest + 1), 5);
}
else
{
    $y->set_range( round($lowest - 10, -1), round($highest + 10, -1), 10);
}
$y->set_grid_colour("#E3E3E3");

$chart = new open_flash_chart();
$chart->set_bg_colour("#FCFCFC");
$chart->set_title( $title );
$chart->add_element( $candle );
if($sma_50==1){$chart->add_element($line_sma50);}
if($sma_25==1){$chart->add_element($line_sma25);}
if($sma_15==1){$chart->add_element($line_sma15);}
if($sma_10==1){$chart->add_element($line_sma10);}
if($sma_5==1){$chart->add_element($line_sma5);}
$chart->set_x_axis( $x );
$chart->set_y_axis( $y );

echo $chart->toPrettyString();


?>
