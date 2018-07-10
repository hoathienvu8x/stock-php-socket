<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<title>Live Quote custom</title>
	<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0" />
<style> * {margin:0;padding:0;outline-color:transparent;} body {background-color:#f1f1f1;font-family:Arial, Helvetica, sans-serif;font-size:13px;} body {background-color:#eee;font-family:Arial, Helvetica, sans-serif;font-size:13px;} table {width:100%;background-color:#fff;font-family:Arial, Helvetica, sans-serif;} table,th, td,tr {border-collapse:collapse;} th {font-weight:normal;font-size:11px;text-align:left;padding:0 6px 3px;border-bottom:1px solid #eee;} td {text-align:left;padding:6px 3px;} th:nth-child(3),td:nth-child(3) { text-aligh:center; }  td:nth-child(3) { font-weight:bold; } tr:nth-of-type(2n+2) {background-color:#f0f0f0;} tr {border-bottom:1px solid #eee;} table tr:last-child {border-bottom:none;} td.pup,.qup { color:#018401;font-weight:bold; } td.pdown,.qdown { color:#F10000;font-weight:bold; } tr td:nth-child(3) {font-size:16px;} tr.pdown { background-color:#ffaf8c; } tr.pup { background-color:#7BC82F; } span.pchg { color:#000; font-size:11px;font-weight:normal; } span.q { font-size:11px; display:inline-block; padding-left:5px;font-weight:normal!important; } span.evol {color:brown;font-size:11px;} </style><script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script></head><body><div id="quote" style="min-width:320px;max-width:450px;margin:20px auto 0;background-color:#fff;padding:10px;"></div>
<script>
	var number_format = function (number, decimals, dec_point, thousands_sep) {
		if (parseFloat(number) == 0.0) {
			return "0";
		}
		number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
		var n = !isFinite(+number) ? 0 : +number,
		prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
		sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
		dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
		s = '',
		toFixedFix = function (n, prec) {
			var k = Math.pow(10, prec);
			return '' + (Math.round(n * k) / k).toFixed(prec);
		};
		s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
		if (s[0].length > 3) {
			s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
		}
		if ((s[1] || '').length < prec) {
			s[1] = s[1] || '';
			s[1] += new Array(prec - s[1].length + 1).join('0');
		}
		return s.join(dec);

	};
	var qRound = function(v) {
		if (v.toString().indexOf('.') != -1) {
			v = number_format(v, 2);
		} else {
			v = number_format(v);
		}
		return v;
	}
	var qMaxMin = function(h, l, p) {
		var max = h - p;
		var mac = '';
		if (max > 0) {
			mac = ' qup';
		} else if (max < 0) {
			mac = ' qdown';
		}
		var min = l - p;
		var mic = '';
		if (min > 0) {
			mic = ' qup';
		} else if (min < 0) {
			mic = ' qdown';
		}
		return {
			max : (max > 0 ? '+'+qRound(max) : qRound(max)),
			mac : mac,
			min : (min > 0 ? '+'+qRound(min) : qRound(min)),
			mic : mic
		};
	};
	var qClass = function(c) {
		c = parseFloat(c.toString().replace('+',''));
		if (c < 0) {
			return 'pdown';
		} else if (c > 0) {
			return 'pup';
		}
		return '';
	}
	var qValid = function(v, s){
		v = Number(v.toString().replace(/,/g,'')), s = Number(s.toString().replace(/,/g,''));
		return (v <= 0 && s <= 0 ? '&#9866;' : '');
	}
	var is_empty = function(obj) {
		for(var prop in obj) {
			if(obj.hasOwnProperty(prop)){
				return false;
			}
		}
		return true;
	}
	var socket = new WebSocket('ws://127.0.0.1:8080/ws/server.php');
	socket.onopen = function(e) {
		// handle request to get other quotes using socket.send(json);
	};
	socket.onmessage = function(a) {
		try {
			var aq = JSON.parse(a.data);
			if (typeof aq == 'object') {
				(function($){
					if ($('#quote').length > 0) {
						if ($('#quote table.quote_tb').length <= 0) {
							var html = '<table class="quote_tb"><thead><tr><th>Month</th><th>Change</th><th>Last</th><th>High/Low</th><th>Bid/Ask</th></tr></thead><tbody>';
							for(var row in aq) {
								
								var c = qClass(aq[row].Change);
								var q = qMaxMin(aq[row].High, aq[row].Low, aq[row].Previous);
								var sep_num = 0;
								if (/^KC/.test(aq[row].Name) == true) {
									if (q.max.indexOf('.') == -1) {
										q.max = q.max + '.00';
									}
									if (q.min.indexOf('.') == -1) {
										q.min = q.min + '.00';
									}
									if (aq[row].Last.toString().indexOf('.') == -1) {
										aq[row].Last = aq[row].Last + '.00';
									}
									if (aq[row].High.toString().indexOf('.') == -1) {
										aq[row].High = aq[row].High + '.00';
									}
									if (aq[row].Low.toString().indexOf('.') == -1) {
										aq[row].Low = aq[row].Low + '.00';
									}
									if (aq[row].Bid.toString().indexOf('.') == -1) {
										aq[row].Bid = aq[row].Bid + '.00';
									}
									if (aq[row].Ask.toString().indexOf('.') == -1) {
										aq[row].Ask = aq[row].Ask + '.00';
									}
									sep_num = 2;
								}
								var vsub = '';
								if (aq[row].Change > 0) {
									vsub = '+';
								}
								
								html += '<tr id="'+row+'"><td>'+aq[row].Name+'<br /><strong>'+aq[row].Month+'</strong></td><td>'+vsub + number_format(aq[row].Change,sep_num)+'<br /><span class="pchg">('+aq[row].PtcChange+'%)</span></td><td>'+qRound(aq[row].Last)+'</td><td>'+qRound(aq[row].High)+'<span class="q'+q.mac+'">('+q.max+')</span><br />'+qRound(aq[row].Low)+'<span class="q'+q.mic+'">('+q.min+')</span></td><td>'+qRound(aq[row].Bid)+' <span class="evol">('+aq[row].BidSize+')</span><br />'+qRound(aq[row].Ask)+' <span class="evol">('+aq[row].AskSize+')</span></td></tr>';
							}
							html += '</tbody></table>';
							$('#quote').html(html);
						} else {
							for(var row in aq) {
								var c = qClass(aq[row].Change);
								var q = qMaxMin(aq[row].High, aq[row].Low, aq[row].Previous);
								var sep_num = 0;
								if (/^KC/.test(aq[row].Name) == true) {
									if (q.max.indexOf('.') == -1) {
										q.max = q.max + '.00';
									}
									if (q.min.indexOf('.') == -1) {
										q.min = q.min + '.00';
									}
									if (aq[row].Last.toString().indexOf('.') == -1) {
										aq[row].Last = aq[row].Last + '.00';
									}
									if (aq[row].High.toString().indexOf('.') == -1) {
										aq[row].High = aq[row].High + '.00';
									}
									if (aq[row].Low.toString().indexOf('.') == -1) {
										aq[row].Low = aq[row].Low + '.00';
									}
									if (aq[row].Bid.toString().indexOf('.') == -1) {
										aq[row].Bid = aq[row].Bid + '.00';
									}
									if (aq[row].Ask.toString().indexOf('.') == -1) {
										aq[row].Ask = aq[row].Ask + '.00';
									}
									sep_num = 2;
								}
								var vsub = '';
								if (aq[row].Change > 0) {
									vsub = '+';
								}
								$('tr#'+row+' td:eq(1),tr#'+row+' td:eq(4)').removeClass('pup pdown');
								$('table.quote_tb tr#'+row+' td:eq(1)').html(vsub + number_format(aq[row].Change,sep_num)+'<br /><span class="pchg">('+aq[row].PtcChange+'%)</span>');
								$('table.quote_tb tr#'+row+' td:eq(2)').html(qRound(aq[row].Last));
								$('table.quote_tb tr#'+row+' td:eq(3)').html(qRound(aq[row].High)+'<span class="q'+q.mac+'">('+q.max+')</span><br />'+qRound(aq[row].Low)+'<span class="q'+q.mic+'">('+q.min+')</span>');
								$('table.quote_tb tr#'+row+' td:eq(4)').html(qRound(aq[row].Bid)+' <span class="evol">('+aq[row].BidSize+')</span><br />'+qRound(aq[row].Ask)+' <span class="evol">('+aq[row].AskSize+')</span>');
								if (c != '') {
									$('tr#'+row).addClass(c);
									$('tr#'+row+' td:eq(1)').addClass(c);
								}
							}
						}
						setTimeout(function() {
							$('table.quote_tb tr').removeClass('pup pdown');
						}, 400);
					}
				})(jQuery);
			}
		} catch(e) {
			console.log(e.message);			
		}
	};
	socket.onerror = function(a) {
		console.log('Error: '+a.data);
	};
	socket.onclose = function(a) {
		console.log('Close: Server is offline');
	};
</script></body></html>
