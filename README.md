# Simple Realtime Quote Using PHP Socket + Javascript WebSocket

That is very simple to display realtime quote data using PHP socket server and javascript [WebSocket](https://developer.mozilla.org/en-US/docs/Web/API/WebSocket) processing data

Data to processing is JSON object string like:
```
[{
		"Name" : "DX A0",
		"Month" : "CASH",
		"Last" : 89.606,
		"Change" : "-0.06",
		"PtcChange" : "-0.07",
		"Volume" : 0,
		"High" : "89.73",
		"Low" : "89.565",
		"Open" : 89.698,
		"Previous" : 89.664,
		"Bid" : 0,
		"BidSize" : 0,
		"Ask" : 0,
		"AskSize" : 0,
		"OpInt" : 0,
		"Time" : "11:06:10"
	}, {
		"Name" : "DX H18",
		"Month" : "03/18",
		"Last" : 89.59,
		"Change" : "-0.05",
		"PtcChange" : "-0.06",
		"Volume" : 27204,
		"High" : "89.715",
		"Low" : "89.54",
		"Open" : 89.685,
		"Previous" : 89.643,
		"Bid" : 89.585,
		"BidSize" : 22,
		"Ask" : 89.595,
		"AskSize" : 33,
		"OpInt" : 21347,
		"Time" : "11:06:10"
	}
]
```

**Data server**

Data server code like:

```
<?php
header('Content-Type:application/json;charset=utf-8');
$exchange = array('stock' => array('prefix' => 'DX','name' => 'USD'),'future' => array('prefix' => 'RM','name' => 'Robusta'));

if (!isset($_GET['exchange'])) {
	exit('[]');
}

$ex = trim($_GET['exchange']);

if (!isset($exchange[$ex])) {
	exit('[]');
}
$year = '18';

$sm = rand(1,12);


$data = array();
$prefix = $exchange[$ex]['prefix'];
$pname = $exchange[$ex]['name'];

$am = 'FGHJKMNQUVXZ';

$sym = array(
	array(
		'Name' => $prefix.'A0',
		'Month' => 'CASH'
	),
	array(
		'Name' => $prefix.$am[($sm-1)].$year,
		'Month' => sprintf($pname.' %2d/'.$year,$sm)
	)
);
do {
	$sm++;
	if ($sm > 12) {
		$sm = 1;
		$year ++;
	}
	$sym[] = array(
		'Name' => $prefix.$am[($sm-1)].$year,
		'Month' => sprintf($pname.' %2d/'.$year,$sm)
	);
} while (count($sym) < 4);


foreach($sym as $ss) {
	$OpInt = rand(0, 10000);
	$check = $prefix == 'DX' ? true : false;
	$Last = $check ? rand(80, 100) : rand(1700, 1985);
	$Previous = $check ? rand(80, 100) : rand(1700, 1985);
	$Open = $check ? rand(80, 100) : rand(1700, 1985);
	$High = $check ? rand(80, 100) : rand(1700, 1985);
	$Low = min($Last,$Previous,$Open, $High);
	$Bid = min($Last,$Previous,$Open, $High);
	$Ask = min($Last,$Previous,$Open, $High);
	$BidSize = rand(0, 100);
	$AskSize = rand(0, 100);
	$Volume = rand(0, 10000);
	$Change = ($Last - $Previous);
	$data[] = array(
		"Name" => $ss['Name'],
		"Month" => $ss['Month'],
		"Last" => $Last,
		"Change" => $Change,
		"PtcChange" => $Change/100,
		"Volume" => $Volume,
		"High" => $High,
		"Low" => $Low,
		"Open" => $Open,
		"Previous" => $Previous,
		"Bid" => $Bid,
		"BidSize" => $BidSize,
		"Ask" => $Ask,
		"AskSize" => $AskSize,
		"OpInt" => $OpInt,
		"Time" => date('H:i:s')
	);
}

exit(json_encode($data));

```

**Run socket server**
```
php -q server.php
```

**View results**
```
http://localhost/stock-php-socket/realtime.php
```

![img](https://raw.githubusercontent.com/hoathienvu8x/stock-php-socket/master/capture.JPG)

**Add NGINX configuration run socket**

```
cp -Rf nginx.conf /etc/nginx/sites-enabled/websocket.conf
systemctl restart nginx or service nginx restart
```

*** Open firewall port 8080 on CentOS 7 ***

Use this command to find your active zone(s):

```
firewall-cmd --get-active-zones
```

It will say either public, dmz, or something else. You should only apply to the zones required. In the case of public try:

```
firewall-cmd --zone=public --add-port=8080/tcp --permanent
```

Then remember to reload the firewall for changes to take effect.

```
firewall-cmd --reload
```

Otherwise, substitute public for your zone, for example, if your zone is dmz:

```
firewall-cmd --zone=dmz --add-port=2888/tcp --permanent
```
