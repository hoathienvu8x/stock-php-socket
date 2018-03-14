# Simple Realtime Quote Using PHP Socket + Javascript WebSocket

That is very simple to display realtime quote data using PHP socket server and javascript [WebSocket] (https://developer.mozilla.org/en-US/docs/Web/API/WebSocket) processing data

Data to processing is JSON object string like:
```
[{
		"Name" : "DX A0",
		"Month" : "USD CASH",
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
		"Month" : "USD Index 03/18",
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

**Run socket server**
```
php -q server.php
```

**View results**
```
http://localhost/stock-php-socket/realtime.php
```