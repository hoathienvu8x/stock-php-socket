<?php
header("Refresh:1; url=get.php");

$url = 'http://data.local.xyz/exchange.php?exchange=stock';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36")');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
curl_setopt($ch, CURLOPT_TIMEOUT, 20);
$rt = curl_exec($ch);

$js = @json_decode($rt, true);

if ($js) {
	file_put_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'quote.php', "<?php \$json = '".$rt."'; ?>");
	exit('File changed');
}
exit('No changed');