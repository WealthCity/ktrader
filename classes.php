<?php

/**
 * @author Kyle Thielk
 * Contains all classes needed by most every file
 * in this project. Also contains a bunch of constants.
 **/ 

    require_once("database.php");
    define("STATUS_ACTIVE", 1);
    define("STATUS_INACTIVE",0);
    define("TIME_YEAR", 31536000);
    define("TIME_HOUR",3600);
    define("TIME_DAY",86400);
    
    define("NANO_CAP",0);
    define("MICRO_CAP",1);
    define("SMALL_CAP",2);
    define("MID_CAP",3);
    define("LARGE_CAP",4);
    
    class Stock
    {
        public $id;
        private $ticker;
        private $name;
        private $description;
        private $last_trade;
        private $earnings_per_share;
        private $price_per_earnings;
        private $stock_exchange_id;
        private $stock_exchange;
        private $target_price;
        private $market_cap;
        private $date_updated;
        private $status;
        private $daily_data;
        private $daily_data_date;
        private $info_date;
        
        /**
         * Create a new Stock instance.
         * @param VariableType $mysql_array Can be either an actual array result returned from MySql, a ticker value i.e 'MOT', or a stock ID number.
         */
        function Stock($mysql_array, $build_stock_table = true)
        {
            if(is_array($mysql_array))
            {
                $this->buildStock($mysql_array, $build_stock_table);
            }
            else if($mysql_array != null && $mysql_array != "" && is_numeric($mysql_array) == false)
            {
                
                $query = mysql_query("SELECT * FROM stocks WHERE ticker = '$mysql_array' LIMIT 1");
                $array = mysql_fetch_array($query);
                
                //NO valid results
                if($array == false)
                {
                    $this->id = -1;
                }
                else
                {
                   $this->buildStock($array, $build_stock_table);
                }
            }
            else if(is_numeric($mysql_array))
            {
                $query = mysql_query("SELECT * FROM stocks WHERE id = '$mysql_array' LIMIT 1");
                $array = mysql_fetch_array($query);
                
                //NO valid results
                if($array == false)
                {
                    $this->id = -1;
                }
                else
                {
                   $this->buildStock($array, $build_stock_table);
                }
            }
            else
            {
                $this->id = -1;
            }
        }
        
        function getId()
        {
            return $this->id;
        }
        function getName()
        {
            return $this->name;
        }
        function setName($new_name)
        {
            $this->name = $new_name;
        }
        function getTicker()
        {
            return $this->ticker;
        }
        function setTicker($new_ticker)
        {
            $this->ticker = $new_ticker;
        }
        function getDescription()
        {
            return $this->description;
        }
        function setDescription($new_description)
        {
            $this->description = $new_description;
        }
        function getLastTrade()
        {
            return $this->last_trade;
        }
        function setLastTrade($new_last_trade)
        {
            $this->last_trade = $new_last_trade;
        }
        function getEarningsPerShare()
        {
            return $this->earnings_per_share;
        }
        function setEarningsPerShare($new_earnings_per_share)
        {
            $this->earnings_per_share = $new_earnings_per_share;
        }
        function getPricePerEarnings()
        {
            return $this->price_per_earnings;
        }
        function setPricePerEarnings($new_price_per_earnings)
        {
            $this->price_per_earnings = $new_price_per_earnings;
        }
        function getStockExchange()
        {
            return $this->stock_exchange;
        }
        /**
         * Set the stock exchange id for this stock. This will also query the database
         * for the StockExchange class with this ID and set it to $this->stock_exchange.
         */
        function setStockExchangeId($new_stock_exchange_id)
        {
            $this->stock_exchange_id = $new_stock_exchange_id;
            $this->stock_exchange = new StockExchange($this->stock_exchange_id);
        }
        function getTargetPrice()
        {
            return $this->target_price;
        }
        function setTargetPrice($new_target_price)
        {
            $this->target_price = $new_target_price;
        }
        function getMarketCap()
        {
            return $this->market_cap;
        }
        function setMarketCap($new_market_cap)
        {
            $this->market_cap = $new_market_cap;
        }
        function getDailyData()
        {
            return $this->daily_data;
        }
        function getDailyDataDate()
        {
            return $this->daily_data_date;
        }
        function setDailyDataDate($new_daily_data_date)
        {
            $this->daily_data_date = $new_daily_data_date;
        }
        function getInfoDate()
        {
            return $this->info_date;
        }
        function setInfoDate($new_info_date)
        {
            $this->info_date = $new_info_date;
        }
        function getStatus()
        {
            return $this->status;
        }
        function setStatus($new_status)
        {
            $this->status = $new_status;
        }
        /**
         * Convenience function that will return an array of size 5 with most recent data for this
         * Stock. arr[0] = most recent
         * @return Array Array of Daily Data
         * */
        private function build_daily_data()
        {
            $arr = array();
            $query = mysql_query("SELECT * FROM daily_data WHERE ticker_id = '".$this->id."' ORDER BY date DESC LIMIT 5");
            if(mysql_num_rows($query) < 1)
            {
                return null;
            }
            $i = 0;
            while($array = mysql_fetch_array($query))
            {
                $data = new DailyData($array);
                $arr[$i] = $data;
                $i = $i + 1;
            }
            return $arr;
        }
        /**
         * Build stock from array. Can set flag $build_stock_table to true which will
         * build our daily data array with 5 most recent.
         */
        private function buildStock($array, $build_stock_table)
        {
            $this->id                 = $array['id'];
            $this->name               = stripslashes($array['name']);
            $this->ticker             = $array['ticker'];
            $this->description        = stripslashes($array['description']);
            $this->last_trade         = $array['last_trade'];
            $this->earnings_per_share = $array['earnings_per_share'];
            $this->price_per_earnings = $array['price_per_earnings'];
            $this->stock_exchange_id  = $array['stock_exchange_id'];
            $this->target_price       = $array['target_price'];
            $this->market_cap         = $array['market_cap'];
            $this->status             = $array['status'];
            $this->date_updated       = $array['date_updated'];
            $this->daily_data_date    = $array['daily_data_date'];
            $this->info_date          = $array['info_date'];
            
            if($this->stock_exchange_id != "" && $this->stock_exchange_id != null && $this->stock_exchange_id > 0)
            {
                $this->stock_exchange = new StockExchange($this->stock_exchange_id);
            }
            if($build_stock_table)
            {
                $this->daily_data = $this->build_daily_data();
            }
        }
        /**
         * If any changes have been made to this stock, persist it to the database.
         */
        function persist()
        {
            if($this->id < 0)
            {
                
                mysql_query("INSERT INTO stocks
                            (name, description, last_trade, earnings_per_share, price_per_earnings, stock_exchange_id, target_price, market_cap, date_added, daily_data_date,status)
                            VALUES
                            ('".addslashes($this->name)."', '".addslashes($this->description)."','".$this->last_trade."',
                            '".$this->earnings_per_share."','".$this->price_per_earnings."',
                            '".$this->stock_exchange_id."','".$this->target_price."', '".$this->market_cap."',
                            '".time()."', '".$this->daily_data_date."','".$this->info_date."','".$this->status."')");
                $this->id = mysql_insert_id();

            }
            else
            {
                mysql_query("UPDATE stocks SET
                            name = '".addslashes($this->name)."',
                            description = '".addslashes($this->description)."',
                            last_trade = '".$this->last_trade."',
                            earnings_per_share = '".$this->earnings_per_share."',
                            price_per_earnings = '".$this->price_per_earnings."',
                            stock_exchange_id = '".$this->stock_exchange_id."',
                            target_price = '".$this->target_price."',
                            market_cap = '".$this->market_cap."',
                            date_updated = '".time()."',
                            daily_data_date = '".$this->daily_data_date."',
                            info_date = '".$this->info_date."',
                            status = '".$this->status."'
                            WHERE id='".$this->id."'");
            }
        }
        
    }
    /**
     * Will be in one instance of these per day.
     */ 
    class DailyData
    {
        private $id;
        private $ticker_id;
        private $date;
        private $open;
        private $high;
        private $low;
        private $close;
        private $adjusted_close;
        private $volume;
        
        /**
         * $mysql_array Must be an actualy MySQL result array.
         * */
        function DailyData($mysql_array)
        {
            if($mysql_array == null)
            {
                $this->id = -1;
            }
            else
            {
                $this->id             = $mysql_array['id'];
                $this->ticker_id      = $mysql_array['ticker_id'];
                $this->date           = $mysql_array['date'];
                $this->open           = $mysql_array['open'];
                $this->high           = $mysql_array['high'];
                $this->low            = $mysql_array['low'];
                $this->close          = $mysql_array['close'];
                $this->adjusted_close = $mysql_array['adjusted_close'];
                $this->volume         = $mysql_array['volume'];
            }
        }
        function getId()
        {
            return $this->id;
        }
        function setId($new_id)
        {
            $this->id = $new_id;
        }
        function getTickerId()
        {
            return $this->ticker_id;
        }
        function setTickerId($new_ticker_id)
        {
            $this->ticker_id = $new_ticker_id;
        }
        function getDate()
        {
            return $this->date;
        }
        function setDate($new_date)
        {
            $this->date = $new_date;
        }
        function getOpen()
        {
            return $this->open;
        }
        function setOpen($new_open)
        {
            $this->open = $new_open;
        }
        function getHigh()
        {
            return $this->high;
        }
        function setHigh($new_high)
        {
            $this->high = $new_high;
        }
        function getLow()
        {
            return $this->low;
        }
        function setLow($new_low)
        {
            $this->low = $new_low;
        }
        function getClose()
        {
            return $this->close;
        }
        function setClose($new_close)
        {
            $this->close = $new_close;
        }
        function getAdjustedClose()
        {
            return $this->adjusted_close;
        }
        function setAdjustedClose($new_adjusted_close)
        {
            $this->adjusted_close = $new_adjusted_close;
        }
        function getVolume()
        {
            return $this->volume;
        }
        function setVolume($new_volume)
        {
            $this->volume = $new_volume;
        }
        /**
         * If exists, insert into database, else update in database.
         * */
        function persist()
        {
            if($this->id < 1)
            {
                //$check_query = mysql_query("SELECT id FROM daily_data WHERE date = '".$this->date."' AND ticker_id = '".$this->ticker_id."' LIMIT 1");
                //if(mysql_num_rows($check_query) < 1)
                //{
                    mysql_query("INSERT INTO daily_data
                                (ticker_id,date,open,high,low,close,volume,adjusted_close)
                                VALUES
                                ('".$this->ticker_id."','".$this->date."','".$this->open."','".$this->high."','".$this->low."',
                                '".$this->close."','".$this->volume."','".$this->adjusted_close."')");
                    $this->id = mysql_insert_id();
                //}
            }
            else
            {
                mysql_query("UPDATE daily_data SET
                            ticker_id = '".$this->ticker_id."',
                            date = '".$this->date."',
                            open = '".$this->open."',
                            high = '".$this->high."',
                            low  = '".$this->low."',
                            close = '".$this->close."',
                            volume = '".$this->volume."',
                            adjusted_close = '".$this->adjusted_close."'
                            WHERE id = '".$this->id."'");
            }
        }
    }
    /**
     * Holds an instance of a Stock Exchange.
     **/
    class StockExchange
    {
        private $id;
        private $ticker;
        private $name;
        
        /**
         * $id must be a numeric id value.
         * */
        function StockExchange($id)
        {
            if($id < 0 || $id == "")
            {
                $this->id = -1;
            }
            else
            {
                $query = mysql_query("SELECT * FROM exchanges WHERE id = '".$id."'");
                $array = mysql_fetch_array($query);
                
                $this->id = $array['id'];
                $this->ticker = $array['ticker'];
                $this->name = $array['name'];
            }
        }
        function getId()
        {
            return $this->id;
        }
        function getTicker()
        {
            return $this->ticker;
        }
        function setTicker($new_ticker)
        {
            $this->ticker = $new_ticker;
        }
        function getName()
        {
            return $this->name;
        }
        function setName($new_name)
        {
            $this->name = $new_name;
        }
        /**
         * If any changes have been made to this stock exchange, persist it to the database.
         */
        function persist()
        {
            if($this->id < 0)
            {
                mysql_query("INSERT INTO exchanges
                            (ticker, name)
                            VALUES
                            ('".$this->ticker."','".$this->name."')");
                $this->id = mysql_insert_id();
            }
            else
            {
                mysql_query("UPDATE exchanges SET
                        ticker = '".$this->ticker."',
                        name = '".$this->name."' 
                        WHERE id = '".$this->id."'");
            }
        }
    }
    /**
     * A portfolio is simply a container class for trades.
     **/
    class Portfolio
    {
        public $id;
        public $name;
        public $description;
        public $opening_capital;
        
        //NON DB Variables
        public $num_open_trades = 0;
        public $num_closed_trades = 0;
        public $account_value = 0;
        public $outstanding_capital = 0;
        public $current_cash = 0;
        public $current_profit = 0;
        
        function Portfolio($mysql_array)
        {
            if(is_array($mysql_array))
            {
                $this->buildPortfolio($mysql_array);
            }
            else if(is_numeric($mysql_array))
            {
                $query = mysql_query("SELECT * FROM portfolio WHERE id = '$mysql_array' LIMIT 1");
                $array = mysql_fetch_array($query);
                
                //NO valid results
                if($array == false)
                {
                    $this->id = -1;
                }
                else
                {
                   $this->buildPortfolio($array);
                }
            }
            else
            {
                $this->id = -1;
            }
        }
        /**
         * Given a mysql result array, build an instance.
         **/ 
        function buildPortfolio($mysql_array)
        {
            $this->id = $mysql_array['id'];
            $this->name = stripslashes($mysql_array['name']);
            $this->description = stripslashes($mysql_array['description']);
            $this->opening_capital = $mysql_array['opening_capital'];
        }
        /**
         * Given an POST data, populate this instance.
         * @return String Blank String on success, failure message on failure.
         **/ 
        function buildFromPost()
        {
            if($_REQUEST['do'] == 'processForm')
            {
                $this->name = $_POST['name'];
                $this->description = $_POST['description'];
                $this->opening_capital = $_POST['opening_capital'];
                
                if($this->name == "" || $this->description == "")
                {
                    return "Both name and description must be filled in.";
                }
                if(!is_numeric($this->opening_capital))
                {
                    return "Opening capital must be a number.";
                }
                return "";
            }
        }
        /**
         * Smart function that will either insert into DB, or update if already
         * exists.
         **/
        function persist()
        {
            if($this->id < 0)
            {
                 mysql_query("INSERT INTO portfolio
                            (name, description,opening_capital)
                            VALUES
                            ('".addslashes($this->name)."','".addslashes($this->description)."','".$this->opening_capital."')");
                 $this->id = mysql_insert_id();
            }
            else
            {
                mysql_query("UPDATE portfolio SET
                            name = '".addslashes($this->name)."',
                            description = '".addslashes($this->description)."',
                            opening_capital = '".$this->opening_capital."'
                            WHERE id = '".$this->id."'");
            }
        }
        /**
         * Get the number active (or open) trades associated
         * with this portfolio.
         **/ 
        function getNumOpenTrades()
        {
            if($this->num_open_trades > 0)
            {
                return $this->num_open_trades;
            }
            $query = mysql_query("SELECT id FROM trades WHERE sold_price < 0.01 AND portfolio_id = '".$this->id."'");
            $this->num_open_trades =  mysql_num_rows($query);
            return $this->num_open_trades;
        }
        /**
         * Get the number of closed trades associated with
         * this portfolio.
         **/ 
        function getNumClosedTrades()
        {
            if($this->num_closed_trades > 0)
            {
                return $this->num_closed_trades;
            }
            $query = mysql_query("SELECT id FROM trades WHERE sold_price > 0.00 AND portfolio_id = '".$this->id."'");
            $this->num_closed_trades =  mysql_num_rows($query);
            return $this->num_closed_trades;
        }
        /**
         * Calculate current capital, current outstanding capital, current profit/loss.
         **/ 
        function runCapitalReport()
        {
            
            $query = mysql_query("SELECT * FROM trades WHERE portfolio_id = '".$this->id."'");
            $trade_profit = 0;
            $cash_out = 0;
            $open_trade_profit = 0;
            while($array = mysql_fetch_array($query))
            {
                $trade = new Trade($array);
                
                
                //Closed Trade
                if($trade->sold_price > 0.00)
                {
                    $trade_profit += ($trade->sold_price - $trade->bought_price) * $trade->number_of_stocks;                    

                }
                else
                {
                    $ticker = $trade->stock->getTicker();
                    update_stock_info($ticker);
                    $trade->stock = new Stock($ticker);
                    //Open Trade
                    $open_trade_profit += ($trade->number_of_stocks * ($trade->stock->getLastTrade() - $trade->bought_price));
                    $cash_out += ($trade->bought_price * $trade->number_of_stocks);
                }
                
            }
            
            $this->current_cash = $this->opening_capital + $trade_profit - $cash_out;
            $this->account_value = $this->opening_capital + $trade_profit + $open_trade_profit;
            
        }
    }
    class Trade
    {
        public $id;
        public $stock_id;
        public $portfolio_id;
        public $number_of_stocks;
        public $bought_price;
        public $sold_price;
        public $bought_date;
        public $bought_date_string;
        public $sold_date;
        public $sold_date_string;
        public $stock;
        
        function Trade($mysql_array = null)
        {
            if(is_array($mysql_array))
            {
                $this->buildTrade($mysql_array);
            }
            else if(is_numeric($mysql_array))
            {
                $query = mysql_query("SELECT * FROM trades WHERE id = '$mysql_array' LIMIT 1");
                $array = mysql_fetch_array($query);
                
                //NO valid results
                if($array == false)
                {
                    $this->id = -1;
                }
                else
                {
                   $this->buildTrade($array);
                }
            }
            else
            {
                $this->id = -1;
            }
        }
        function buildTrade($mysql_array)
        {
            $this->id               = $mysql_array['id'];
            $this->stock_id         = $mysql_array['stock_id'];
            $this->portfolio_id     = $mysql_array['portfolio_id'];
            $this->number_of_stocks = $mysql_array['number_of_stocks'];
            $this->bought_price     = $mysql_array['bought_price'];
            $this->sold_price       = $mysql_array['sold_price'];
            $this->bought_date      = $mysql_array['bought_date'];
            $this->sold_date        = $mysql_array['sold_date'];
            
            $this->stock              = new Stock($this->stock_id,false);
            $this->bought_date_string = date("d F Y", $this->bought_date);
            $this->sold_date_string   = date("d F Y", $this->sold_date);
        }
        /**
         * Will get data necessary to trade from POST variables.
         **/ 
        function closeTrade()
        {
            if($this->id < 1)
            {
                return "There was a fatal problem. There was no attached Trade.";
            }
            
            if($_REQUEST['do'] == "closeTrade")
            {
                $this->sold_price = $_POST['sold_price'];
                $this->sold_date_string = $_POST['sold_date_string'];
                
                $this->sold_date = strtotime($this->sold_date_string);
                
                if(!is_numeric($this->sold_price))
                {
                    return "Sold price must be a number.";
                }
                if($this->sold_date == false || $this->sold_date == -1)
                {
                    return "Close date was not recognized. Try form 12 November, 2009";
                }
                return "";
            }
        }
        /**
         * Will build instance of Trade from $_POST variables.
         * @return String Blank string on success, status string on failure.
         **/ 
        function buildFromPost()
        {
            //Make sure aren't calling this function by accident.
            if($_REQUEST['do'] == "processTrade")
            {
                $this->stock_id           = $_POST['stock_id'];
                $this->portfolio_id       = $_POST['portfolio_id'];
                $this->number_of_stocks   = $_POST['number_of_stocks'];
                $this->bought_price       = $_POST['bought_price'];
                $this->sold_price         = $_POST['sold_price'];
                $this->bought_date_string = $_POST['bought_date_string'];
                
                $this->bought_date  = strtotime($this->bought_date_string);
                $this->stock        = new Stock($this->stock_id);
                
                if( !$this->stock_id || !$this->portfolio_id)
                {
                    return "There was a fatal error. Either stock_id or portfolio_id was not set.";
                }
                if(!is_numeric($this->number_of_stocks) || !is_numeric($this->bought_price))
                {
                    return "Both number of stocks and bought price has to be numeric.";
                }
                if($this->number_of_stocks < 0.01 || $this->bought_price < 0.01)
                {
                    return "Both number of stocks and bought price has to be above 0.";
                }
                if($this->bought_date == false || $this->bought_date == -1)
                {
                    return "Provided date was invalid.";
                }
                $portfolio = new Portfolio($this->portfolio_id);
                $portfolio->runCapitalReport();
                if($portfolio->current_cash < ($this->bought_price * $this->number_of_stocks))
                {
                    return "Selected profile does not have enough available cash to purchase this stock.";
                }
                return "";
            }
        }
        function persist()
        {
            if($this->id < 0)
            {
                mysql_query("INSERT INTO trades
                            (stock_id,portfolio_id,number_of_stocks,bought_price,sold_price,bought_date)
                            VALUES
                            ('".$this->stock_id."','".$this->portfolio_id."','".$this->number_of_stocks."','".$this->bought_price."','".$this->sold_price."','".$this->bought_date."')") or die(mysql_error($query));
                $this->id = mysql_insert_id();
            }
            else
            {
                mysql_query("UPDATE trades SET
                            stock_id         = '".$this->stock_id."',
                            portfolio_id     = '".$this->portfolio_id."',
                            number_of_stocks = '".$this->number_of_stocks."',
                            bought_price     = '".$this->bought_price."',
                            sold_price       = '".$this->sold_price."',
                            bought_date      = '".$this->bought_date."',
                            sold_date        = '".$this->sold_date."'
                            WHERE id = '".$this->id."'
                            ");
            }
        }
        
    }
    class FormBuilder
    {
        function portfolioCombo($identifier = 'portfolio_id')
        {
            $combo = '<select name="'.$identifier.'" id="'.$identifier.'">';
            
            $query = mysql_query("SELECT * FROM portfolio ORDER BY name ASC");
            while ($array = mysql_fetch_array($query))
            {
                $portfolio = new Portfolio($array);
                $portfolio->runCapitalReport();
                $combo .= '<option value="'.$portfolio->id.'">'.$portfolio->name.'(Current Cash: $'.round($portfolio->current_cash,2).')</option>';
            }
            $combo .= '</select>';
            return $combo;
        }
    }
?>