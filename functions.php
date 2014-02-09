<?php
function secsToStr($secs) {
	$r = '';
	if($secs>=86400){$days=floor($secs/86400);$secs=$secs%86400;$r=$days.' day';if($days<>1){$r.='s';}if($secs>0){$r.=', ';}}
	if($secs>=3600){$hours=floor($secs/3600);$secs=$secs%3600;$r.=$hours.' hr';if($hours<>1){$r.='s';}if($secs>0){$r.=', ';}}
	if($secs>=60){$minutes=floor($secs/60);$secs=$secs%60;$r.=$minutes.' min';if($minutes<>1){$r.='s';}if($secs>0){$r.=', ';}}
	$r .= $secs.' sec'; if ($secs <> 1) { $r .= 's'; }
	return $r;
}

function getPage($url) {
	$ch = curl_init();
	$timeout = 5;
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; rv:12.0) Gecko/20120403211507 Firefox/14.0.1');
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
}
?>