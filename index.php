<?php
/******************************************
* Coded by ebildude123
* (C) 2014
* ItsLIGHTNESS.com
* Please follow GPL if you wish to modify
******************************************/
define("MH_PER_S", 55);
define("KH_PER_S", 56);
$displayFormat = MH_PER_S;
include "header.php";
require "class.cgminer.php";
require "defines.php";
require "settings.php";
require "functions.php";

$startTime = microtime(true);
?>
<section id="tables" style="margin-top: 1px; padding-top: 1px;">
  <div class="page-header">
    <h3>Miner Statistics</h3>
  </div>
  
  <table class="table table-bordered table-striped table-hover">
    <thead>
      <tr>
        <th>Device</th>
        <th>Enabled</th>
        <th>Status</th>
		<th>Accepted</th>
		<th>Rejected</th>
		<th>HW Errors</th>
		<th>Temperature</th><?php if ($enableGPUdisp == true) { ?>
		<th>Voltage</th>
		<th>Intensity</th><?php } ?>
      </tr>
    </thead>
    <tbody>
<?php
foreach ($servers as $sUrl) {
	$sAddr = $sUrl[0];
	$sPort = $sUrl[1];
	$cgAPI = new cgminerPHP($sAddr, $sPort);
	if ($cgAPI != null) {
		$getDevices = $cgAPI->request("devs");
			
		foreach ($getDevices as $deviceID => $deviceInfo) {
			if ($deviceID != "STATUS") {
				if (($removeDisabled === true && $deviceInfo["Enabled"] ==  "Y") || ($removeDisabled === false)) {
								
					echo "<tr>\n";
					echo "<td>" . $deviceID . "</td>\n";
					$gEnabled = $deviceInfo["Enabled"];
					if ($gEnabled == "Y") {
						echo "<td style=\"background-color: #94DA8F; text-align: center;\"><b>Yes</b></td>\n";
					}		
					else {
						echo "<td style=\"background-color: #F2DEDE; text-align: center;\"><b>No</b></td>\n";
					}
					
					
					$gStatus = $deviceInfo["Status"];
					if ($gStatus == "Alive") {
						echo "<td style=\"background-color: #94DA8F; text-align: center;\"><b>Alive</b></td>\n";
					}		
					else {
						echo "<td style=\"background-color: #F2DEDE; text-align: center;\"><b>Dead</b></td>\n";
					}
					
					echo "<td>" . $deviceInfo["Accepted"] . "</td>\n";
					echo "<td>" . $deviceInfo["Rejected"] . "</td>\n";
					echo "<td>" . $deviceInfo["Hardware Errors"] . "</td>\n";
					
					if (isset($deviceInfo["Temperature"])) {
						echo "<td>" . $deviceInfo["Temperature"] . " C</td>\n";
					}
					else {
						echo "<td>N/A</td>\n";
					}
					
					if ($enableGPUdisp == true) {
					
						if (isset($deviceInfo["GPU Voltage"])) {
							echo "<td>" . $deviceInfo["GPU Voltage"] . "</td>\n";
						}
						else {
							echo "<td>N/A</td>\n";
						}
						
						if (isset($deviceInfo["Intensity"])) {
							echo "<td>" . $deviceInfo["Intensity"] . "</td>\n";
						}
						else {
							echo "<td>N/A</td>\n";
						}
					
					}
					
					echo "</tr>\n";
				
				}
	
			}
		}
	
	}
	else {
		echo "<tr>\n";
		echo "<td colspan=\"9\">Could not connect to server</td>\n";
		echo "</tr>\n";
	}
		
}
?>
	</tbody>
  </table>
</section>


<section id="tables" style="margin-top: 5px; padding-top: 5px;">
  <div class="page-header">
    <h3>Summary</h3>
  </div>
  
  <table class="table table-bordered table-striped table-hover">
    <thead>
      <tr>
        <th>Address</th>
		<th>Uptime</th>
        <th>Hash Rate</th>
        <th>Pool</th>
        <th>User</th>
      </tr>
    </thead>
    <tbody>
<?php
foreach ($servers as $sUrl) {
	$sAddr = $sUrl[0];
	$sPort = $sUrl[1];
	$cgAPI = new cgminerPHP($sAddr, $sPort);
	if ($cgAPI != null) {
		$getSummary = $cgAPI->request("summary");		
		$getPools = $cgAPI->request("pools");
		
		$getFormat = $cgAPI->request("coin");
		$hashMethod = $getFormat["COIN"]["Hash Method"];
		if ($hashMethod == "scrypt") {
			$displayFormat = KH_PER_S;
		}
		else {
			$displayFormat = MH_PER_S;
		}
		
		echo "<tr>\n";
		echo "<td>" . $sAddr . ", port " . $sPort . "</td>\n";
		
		$getUptime = $getSummary["SUMMARY"]["Elapsed"];
		$getUptime = secsToStr($getUptime);
		
		echo "<td>" . $getUptime . "</td>\n";
		
		$getSpeed = $getSummary["SUMMARY"]["MHS av"];
		if ($displayFormat == MH_PER_S) {
			$hashSpeed = round($getSpeed, 2);
			echo "<td>" . $hashSpeed . " MH/s</td>\n";
		}
		else {
			$hashSpeed = $getSpeed * 1000;
			$hashSpeed = round($hashSpeed, 2);
			echo "<td>" . $hashSpeed . " KH/s</td>\n";
		}
		
		echo "<td>" . $getPools["POOL0"]["URL"] . "</td>\n";
		echo "<td>" . $getPools["POOL0"]["User"] . "</td>\n";
			
		echo "</tr>\n";
	}
	else {
		echo "<tr>\n";
		echo "<td>" . $sAddr . ", port " . $sPort . "</td>\n";
		echo "<td colspan=\"3\">Could not connect to server</td>\n";
		echo "</tr>\n";
	}
	
}
?>
    </tbody>
  </table>
</section>

<?php if (count($apis) > 0) { ?>
<section id="tables" style="margin-top: 5px; padding-top: 5px;">
  <div class="page-header">
    <h3>API</h3>
  </div>
  
  <table class="table table-bordered table-striped table-hover">
    <thead>
      <tr>
		<th>Pool</th>
        <th>Balance</th> <!-- Confirmed reward -->
		<th>Hash Rate</th>
        <th>Total Shares</th>
        <th>Difficulty</th>
      </tr>
    </thead>
    <tbody>
<?php		
		foreach ($apis as $api) {
			echo "<tr>\n";
			$apiType = $api[0];
			$apiKey = $api[1];
			
			$getName = $getBalance = $getHashrate = $getShares = $getDiff = "";
			
			if ($apiType == BTCGUILD) {
				$getName = "BTCGuild";
				$getAPI = getPage($apiType . $apiKey);
				$getBalance = "There's a 15 second wait period for this API.";
				
				$apiArr = @json_decode($getAPI, true);
				if (isset($apiArr["user"])) {
					$getBalance = $apiArr["user"]["unpaid_rewards"];
					$getHashrate = 0;
					$getShares = 0;
					$getDiff = $apiArr["pool"]["difficulty"];
					
					foreach ($apiArr["workers"] as $worker) {
						$getHashrate += $worker["hash_rate"];
						$getShares += $worker["valid_shares"];
					}
					
					$getHashrate .= " MH/s";
				}
				elseif (trim($getAPI) == "API key did not match any users.") {
					$getBalance = "Invalid API key.";
				}
			}
			elseif ($apiType == FIFTYBTC) {
				$getName = "50BTC";
				$getAPI = getPage($apiType . $apiKey);
				
				$apiArr = @json_decode($getAPI, true);
				if (isset($apiArr["user"])) {
					$getBalance = $apiArr["user"]["confirmed_rewards"];
					$getHashrate = $apiArr["user"]["hash_rate"] . " MH/s";
					$getShares = 0;
					$getDiff = "n/a";
					
					foreach ($apiArr["workers"] as $worker) {
						$getShares += $worker["total_shares"];
					}
				}
				elseif (isset($apiArr["error"]) && $apiArr["error"] == "Token is invalid! Please renew token on your settings page.") {
					$getBalance = "Invalid API key.";
				}
				else {
					$getBalance = "Error loading the API.";
				}
			}
			elseif ($apiType == SLUSH || $apiType == WEMINELTC) {
				$rwdName = "";
				($apiType == SLUSH) ? $getName = "Slush" : $getName = "WeMineLTC";
				($apiType == SLUSH) ? $rwdName = "confirmed_reward" : $rwdName = "confirmed_rewards";
				$getAPI = getPage($apiType . $apiKey);
				
				$apiArr = @json_decode($getAPI, true);
				if (isset($apiArr["username"])) {					
					$getBalance = $apiArr[$rwdName];
					$getHashrate = 0;
					$getShares = 0;
					$getDiff = "n/a";
					
					foreach ($apiArr["workers"] as $worker) {
						$getHashrate += $worker["hashrate"];
						(isset($worker["shares"])) ? $getShares += $worker["shares"] : $getShares = "n/a";
					}
					
					($apiType == SLUSH) ? $getHashrate .= " MH/s" : $getHashrate .= " KH/s";
				}
				elseif (trim($getAPI) == "Invalid token" || trim($getAPI) == "Invalid Key.") {
					$getBalance = "Invalid API key.";
				}
				elseif (strpos($getAPI, "API Throttle In Effect!") !== false) {
					$getBalance = "There's a 60 second wait period for this API.";
				}
				else {
					$getBalance = "Error loading the API.";
				}
			}
			elseif ($apiType == LTC_HASHFASTER || $apiType == LTCRABBIT) {
				($apiType == LTC_HASHFASTER) ? $getName = "HashFaster" : $getName = "LTCRabbit";
				$getAPI = getPage($apiType . $apiKey);
				
				$apiArr = @json_decode($getAPI, true);
				if (isset($apiArr["getuserstatus"])) {
					$getBalance = "n/a";
					if (isset($apiArr["getuserstatus"]["balance"])) {
						$getBalance = $apiArr["getuserstatus"]["balance"];
					}
					($apiType == LTC_HASHFASTER) ? $getHashrate = $apiArr["getuserstatus"]["data"]["hashrate"] . " KH/s" : $getHashrate = $apiArr["getuserstatus"]["hashrate"] . " KH/s";
					(isset($apiArr["getuserstatus"]["data"]["shares"]["valid"])) ? $getShares = $apiArr["getuserstatus"]["data"]["shares"]["valid"] : $getShares = "n/a";
					
					$getAPIDiff = getPage(str_replace("&action=getuserstatus", "&action=getdifficulty", $apiType . $apiKey));
					$diffObj = json_decode($getAPIDiff);
					($apiType == LTC_HASHFASTER) ? $getDiff = $diffObj->getdifficulty->data : $getDiff = $diffObj->getdifficulty;
				}
				elseif (trim($getAPI) == "Access denied") {
					$getBalance = "Invalid API key.";
				}
				else {
					$getBalance = "Error loading the API.";
				}
			}
			
?>
		  <td><?php echo $getName; ?></td>
		  <td><?php echo $getBalance; ?></td>
		  <td><?php echo $getHashrate; ?></td>
		  <td><?php echo $getShares; ?></td>
		  <td><?php echo $getDiff; ?></td>
		</tr>
<?php
		}		
	}
?>
	</tbody>
  </table>
</section>
<?php if ($viewPrices == true) { ?>
<section id="tables" style="margin-top: 5px; padding-top: 5px;">
  <div class="page-header">
    <h3>Current Prices</h3>
  </div>
  
  <table class="table table-bordered table-striped table-hover">
    <thead>
      <tr>
		<th>&nbsp;</th>
        <th>MtGox</th>
		<th>BTC-e</th>
        <th>Coinbase</th>
        <th>Kraken</th>
      </tr>
    </thead>
    <tbody>
		<tr>
			<td>BTC</td>
<?php
$getMtgox = getPage("http://data.mtgox.com/api/1/BTCUSD/ticker");
$getMtgox = @json_decode($getMtgox, true);
if (isset($getMtgox["result"]) && $getMtgox["result"] == "success") {
	$getMtgox = $getMtgox["return"]["last"]["display"];
}
else {
	$getMtgox = "Error";
}
echo "<td>" . $getMtgox . "</td>\n";

$getBTCE = getPage("https://btc-e.com/api/2/btc_usd/ticker");
$getBTCE = json_decode($getBTCE, true);
if (isset($getBTCE["ticker"]["last"]) && $getBTCE["ticker"]["last"] != null) {
	$getBTCE = $getBTCE["ticker"]["last"];
	$getBTCE = "$" . number_format(floatval($getBTCE), 2);
}
else {
	$getBTCE = "Error";
}
echo "<td>" . $getBTCE . "</td>\n";

$getCoinbase = getPage("https://coinbase.com/api/v1/prices/buy");
$getCoinbase = json_decode($getCoinbase, true);
if (isset($getCoinbase["subtotal"]["amount"]) && $getCoinbase["subtotal"]["amount"] != null) {
	$getCoinbase = "$" . $getCoinbase["subtotal"]["amount"];
}
else {
	$getCoinbase = "Error";
}
echo "<td>" . $getCoinbase . "</td>\n";

$getKraken = getPage("https://api.kraken.com/0/public/Ticker?pair=XBTUSD");
$getKraken = json_decode($getKraken, true);
if (count($getKraken) > 0) {
	$getKraken = "$" . number_format($getKraken["result"]["XXBTZUSD"]["c"][0], 2);
}
else {
	$getKraken = "Error";
}
echo "<td>" . $getKraken . "</td>\n";
?>
		</tr>
		<tr>
			<td>LTC</td>
			<td>N/A</td>
<?php
$getBTCE = getPage("https://btc-e.com/api/2/ltc_usd/ticker");
$getBTCE = json_decode($getBTCE, true);
if (isset($getBTCE["ticker"]["last"]) && $getBTCE["ticker"]["last"] != null) {
	$getBTCE = $getBTCE["ticker"]["last"];
	$getBTCE = "$" . number_format(floatval($getBTCE), 2);
}
else {
	$getBTCE = "Error";
}
echo "<td>" . $getBTCE . "</td>\n";
?>
			<td>N/A</td>
<?php
$getKraken = getPage("https://api.kraken.com/0/public/Ticker?pair=LTCUSD");
$getKraken = json_decode($getKraken, true);
if (count($getKraken) > 0) {
	$getKraken = "$" . number_format($getKraken["result"]["XLTCZUSD"]["c"][0], 2);
}
else {
	$getKraken = "Error";
}
echo "<td>" . $getKraken . "</td>\n";
?>
		</tr>
	</tbody>
  </table>
</section>
<?php } ?>

<?php
include "footer.php";
?>