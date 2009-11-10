<?php
    if($stock->getStockExchange() == null)
    {
        $stockExchange = "N/A";
    }
    else
    {
        $stockExchange = $stock->getStockExchange()->getName();
    }
   echo '<br />
    <table style="border-color: #A5A5A5; border-width: 1px 1px 0 0; border-style: solid" width="600" cellpadding="4" cellspacing="0" align="center">
        <tr>
            <td width="40%" bgcolor="#DDDBDB" style="border-color: #A5A5A5; border-width: 0 0 1px 1px; border-style: solid"><b>Stock Ticker</b></td>
            <td width="60%" style="border-color: #A5A5A5; border-width: 0 0 1px 1px; border-style: solid">'.$stock->getTicker().'</td>
        </tr>
        <tr>
            <td width="40%" bgcolor="#DDDBDB" style="border-color: #A5A5A5; border-width: 0 0 1px 1px; border-style: solid"><b>Stock Name</b></td>
            <td width="60%" style="border-color: #A5A5A5; border-width: 0 0 1px 1px; border-style: solid">'.$stock->getName().'</td>
        </tr>
        <tr>
            <td width="40%" bgcolor="#DDDBDB" style="border-color: #A5A5A5; border-width: 0 0 1px 1px; border-style: solid"><b>Last Trade</b></td>
            <td width="60%" style="border-color: #A5A5A5; border-width: 0 0 1px 1px; border-style: solid">$'.$stock->getLastTrade().'</td>
        </tr>
        <tr>
            <td width="40%" bgcolor="#DDDBDB" style="border-color: #A5A5A5; border-width: 0 0 1px 1px; border-style: solid"><b>Earnings/Share</b></td>
            <td width="60%" style="border-color: #A5A5A5; border-width: 0 0 1px 1px; border-style: solid">$'.$stock->getEarningsPerShare().'</td>
        </tr>
        <tr>
            <td width="40%" bgcolor="#DDDBDB" style="border-color: #A5A5A5; border-width: 0 0 1px 1px; border-style: solid"><b>Price/Earnings</b></td>
            <td width="60%" style="border-color: #A5A5A5; border-width: 0 0 1px 1px; border-style: solid">$'.$stock->getPricePerEarnings().'</td>
        </tr>
        <tr>
            <td width="40%" bgcolor="#DDDBDB" style="border-color: #A5A5A5; border-width: 0 0 1px 1px; border-style: solid"><b>Stock Exchange</b></td>
            <td width="60%" style="border-color: #A5A5A5; border-width: 0 0 1px 1px; border-style: solid">'.$stockExchange.'</td>
        </tr>
        <tr>
            <td width="40%" bgcolor="#DDDBDB" style="border-color: #A5A5A5; border-width: 0 0 1px 1px; border-style: solid"><b>1 Year Target Price</b></td>
            <td width="60%" style="border-color: #A5A5A5; border-width: 0 0 1px 1px; border-style: solid">$'.$stock->getTargetPrice().'</td>
        </tr>
        <tr>
            <td width="40%" bgcolor="#DDDBDB" style="border-color: #A5A5A5; border-width: 0 0 1px 1px; border-style: solid"><b>Data Updated:</b></td>
            <td width="60%" style="border-color: #A5A5A5; border-width: 0 0 1px 1px; border-style: solid">'.date("d F, Y",$stock->getInfoDate()).'</td>
        </tr>
    </table><br />
   ';
?>