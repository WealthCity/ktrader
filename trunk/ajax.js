var xmlhttp
var adminUpdateStockDetailsEnabled = true;
var adminUpdateStockDetailsStart = 0;
var adminUpdateStockDailyDataEnabled = true;
var adminUpdateStockDailyDataStart = 0;
//######################################//
// FUNCTIONS USED BY MULTIPLE TEMPLATES //
//######################################//

/**
* Get our XML object so we can do some AJAX magic!
*/
function GetXmlHttpObject()
{
	if (window.XMLHttpRequest)
	{
		// code for IE7+, Firefox, Chrome, Opera, Safari
		return new XMLHttpRequest();
	}
	if (window.ActiveXObject)
	{
		// code for IE6, IE5
		return new ActiveXObject("Microsoft.XMLHTTP");
	}
	return null;
}

/**
 * Called when changing letter for Stock Table.
 * @param letter {string}
 */ 
function display_stockList(letter, cap, price, eps, ppe)
{
    //Get our XML Object
	xmlhttp=GetXmlHttpObject();
	//Make sure we have javascript enabled.
	if (xmlhttp==null)
	{
		alert ("Your browser does not support AJAX!");
		return;
	}
	//Build URL to send to server
	var url="ajax.php?do=stockList&l="+letter+"&cap="+cap+"&price="+price+"&eps="+eps+"&ppe="+ppe;
	alert(url);
	//Link to function that receives response
	xmlhttp.onreadystatechange=display_stockList_response;
	//Open and Send AJAX request
	xmlhttp.open("GET",url,true);
	xmlhttp.send(null);
}
/**
* Handle server reponse from deleteImage_homepage
*/
function display_stockList_response()
{
	//We are ready to change page html
	if (xmlhttp.readyState==4)
	{
		document.getElementById("content").innerHTML=xmlhttp.responseText;
	}

}
function admin_updateStockDetailsEnable()
{
	if(adminUpdateStockDetailsEnabled)
	{
		adminUpdateStockDetailsEnabled = false;
		document.getElementById("adminButtons").innerHTML = "<input type=\"submit\" onClick=\"admin_updateStockDetailsEnable(); return false;\" value=\"Restart\">";
	}
	else
	{
		adminUpdateStockDetailsEnabled = true;
		document.getElementById("adminButtons").innerHTML = "<input type=\"submit\" onClick=\"admin_updateStockDetailsEnable(); return false;\" value=\"Pause\">";
		admin_updateStockDetails(adminUpdateStockDetailsStart - 1);
	}
}
function admin_updateStockDetails(start)
{
	document.getElementById("adminButtons").innerHTML = "<input type=\"submit\" onClick=\"admin_updateStockDetailsEnable(); return false;\" value=\"Pause\">";
    //Get our XML Object
	xmlhttp=GetXmlHttpObject();
	//Make sure we have javascript enabled.
	if (xmlhttp==null)
	{
		alert ("Your browser does not support AJAX!");
		return;
	}
	//Build URL to send to server
	var url="ajax.php?do=updateStockDetails&s="+start;
	//Link to function that receives response
	xmlhttp.onreadystatechange=admin_updateStockDetails_response;
	//Open and Send AJAX request
	xmlhttp.open("GET",url,true);
	xmlhttp.send(null);
}
/**
* Handle server reponse from deleteImage_homepage
*/
function admin_updateStockDetails_response()
{
	//We are ready to change page html
	if (xmlhttp.readyState==4)
	{
		start = xmlhttp.responseText;
		if(start == -1)
		{
			document.getElementById("status").innerHTML= document.getElementById("status").innerHTML + "You have updated all of the stats.<br />";
		}
		else if(adminUpdateStockDetailsEnabled == false)
		{
			adminUpdateStockDetailsStart = start;
		}
		else
		{
			document.getElementById("status").innerHTML = "Updating ticker with id: " + start;
			admin_updateStockDetails(xmlhttp.responseText);
		}
	}

}
function admin_updateStockDailyDataEnable()
{
	if(adminUpdateStockDailyDataEnabled)
	{
		adminUpdateStockDailyDataEnabled = false;
		document.getElementById("adminButtons").innerHTML = "<input type=\"submit\" onClick=\"admin_updateStockDailyDataEnable(); return false;\" value=\"Restart\">";
	}
	else
	{
		adminUpdateStockDailyDataEnabled = true;
		document.getElementById("adminButtons").innerHTML = "<input type=\"submit\" onClick=\"admin_updateStockDailyDataEnable(); return false;\" value=\"Pause\">";
		admin_updateStockDailyData(adminUpdateStockDailyDataStart - 1);
	}
}
function admin_updateStockDailyData(start)
{
	document.getElementById("adminButtons").innerHTML = "<input type=\"submit\" onClick=\"admin_updateStockDailyDataEnable(); return false;\" value=\"Pause\">";
    //Get our XML Object
	xmlhttp=GetXmlHttpObject();
	//Make sure we have javascript enabled.
	if (xmlhttp==null)
	{
		alert ("Your browser does not support AJAX!");
		return;
	}
	//Build URL to send to server
	var url="ajax.php?do=updateStockDailyData&s="+start;
	//Link to function that receives response
	xmlhttp.onreadystatechange=admin_updateStockDailyData_response;
	//Open and Send AJAX request
	xmlhttp.open("GET",url,true);
	xmlhttp.send(null);
}
/**
* Handle server reponse from deleteImage_homepage
*/
function admin_updateStockDailyData_response()
{
	//We are ready to change page html
	if (xmlhttp.readyState==4)
	{
		start = parseInt(xmlhttp.responseText);
		if(start == -1)
		{
			document.getElementById("status").innerHTML= document.getElementById("status").innerHTML + "You have updated all of the stats.<br />";
		}
		else if(adminUpdateStockDailyDataEnabled == false)
		{
			adminUpdateStockDailyDataStart = start;
		}
		else
		{
			document.getElementById("status").innerHTML = "Updating record: " + start + "<br />";
			admin_updateStockDailyData(start);
		}
	}

}
var last_stock_id = 0;
var deactivateStocks_id = 0;
/**
 * Lets set up our task by first figuring out how many stocks we need to cycle through.
 */
function deactivateStocks()
{
	document.getElementById("adminButtons").innerHTML = "<input type=\"submit\" onClick=\"deactivateStocksEnable(); return false;\" value=\"Pause\">";
    //Get our XML Object
	xmlhttp=GetXmlHttpObject();
	//Make sure we have javascript enabled.
	if (xmlhttp==null)
	{
		alert ("Your browser does not support AJAX!");
		return;
	}
	//Build URL to send to server
	var url="ajax.php?do=deactivateStocks&s=-1";
	//Link to function that receives response
	xmlhttp.onreadystatechange=deactivateStocks_response;
	//Open and Send AJAX request
	xmlhttp.open("GET",url,true);
	xmlhttp.send(null);
}
/**
* Handle server reponse from deactivateStocks
*/
function deactivateStocks_response()
{
	//We are ready to change page html
	if (xmlhttp.readyState==4)
	{
		last_stock_id = xmlhttp.responseText;
		//Now we know total # of stocks. Start cycle.
		deactivateStocks_cycle(0);
	}

}
/**
 * Cycle through stocks looking for inactive stocks to deactivate.
 */ 
function deactivateStocks_cycle(start)
{
    //Get our XML Object
	xmlhttp=GetXmlHttpObject();
	//Make sure we have javascript enabled.
	if (xmlhttp==null)
	{
		alert ("Your browser does not support AJAX!");
		return;
	}
	//Build URL to send to server
	var url="ajax.php?do=deactivateStocks&s="+start;
	//Link to function that receives response
	xmlhttp.onreadystatechange=deactivateStocks_cycle_response;
	//Open and Send AJAX request
	xmlhttp.open("GET",url,true);
	xmlhttp.send(null);
}
/**
* Handle server reponse from deactivateStocks_cycle
*/
function deactivateStocks_cycle_response()
{
	//We are ready to change page html
	if (xmlhttp.readyState==4)
	{
		var resp = xmlhttp.responseText;
		var arr = resp.split("||");
		var id = parseInt(arr[0]);
		if(deactivateStocksEnabled == false)
		{
			deactivateStocks_id = id;
			return;
		}
		document.getElementById("status").innerHTML = "Updating record: " + id + " of " + last_stock_id;
		deactivateStocks_id = id;
		if(arr[1] != "")
		{
			document.getElementById("message").innerHTML = document.getElementById("message").innerHTML + arr[1] + "<br />";	
		}
		
		if(id >= last_stock_id)
		{
			return;
		}
		else
		{
			deactivateStocks_cycle(id + 1);
		}
	}

}
var deactivateStocksEnabled = true;
function deactivateStocksEnable()
{
	if(deactivateStocksEnabled)
	{
		deactivateStocksEnabled = false;
		document.getElementById("adminButtons").innerHTML = "<input type=\"submit\" onClick=\"deactivateStocksEnable(); return false;\" value=\"Resume\">";
	}
	else
	{
		deactivateStocksEnabled = true;
		document.getElementById("adminButtons").innerHTML = "<input type=\"submit\" onClick=\"deactivateStocksEnable(); return false;\" value=\"Pause\">";
		deactivateStocks_cycle(deactivateStocks_id - 1);
	}
}
function changeChart(ticker, time)
{
	if(time == ""){time = 0;}
	
	var url = "stock_info.php?s="+ticker+"&t="+time;
	url = url + "&sa=" + document.getElementById("sma5").checked * 1;
	url = url + "&sb=" + document.getElementById("sma10").checked * 1;
	url = url + "&sc=" + document.getElementById("sma15").checked  * 1;
	url = url + "&sd=" + document.getElementById("sma25").checked * 1;
	url = url + "&se=" + document.getElementById("sma50").checked * 1;

	window.location = url;
}
function changeChartTime(ticker, time)
{
	changeChart(ticker, time);
}