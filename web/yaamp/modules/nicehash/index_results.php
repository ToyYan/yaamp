<?php

$apikey = 'c9534a11-0e4e-4d00-be64-a00e34cd927a';
$apiid = '7215';

$res = fetch_url("https://www.nicehash.com/api?method=balance&id=$apiid&key=$apikey");
$a = json_decode($res);
$balance = $a->result->balance_confirmed;

echo "balance $balance<br>";

echo "<br><table class='dataGrid'>";
echo "<thead>";
echo "<tr>";
echo "<th>ID</th>";
echo "<th>Algo</th>";
echo "<th>BTC</th>";
echo "<th>Nicehash</th>";
echo "<th>Yaamp</th>";
echo "<th>Price</th>";
echo "<th>Speed</th>";
echo "<th>Last Dec</th>";
echo "<th>Workers</th>";
echo "<th>Accepted</th>";
echo "<th>Rejected</th>";
echo "<th></th>";
echo "</tr>";
echo "</thead><tbody>";

$list = getdbolist('db_nicehash');
foreach($list as $nicehash)
{
	$price2 = mbitcoinvaluetoa(dboscalar("select price from services where algo='$nicehash->algo'")*1000);

	$d = datetoa2($nicehash->last_decrease);
	$yaamp = mbitcoinvaluetoa(dboscalar("select price from hashrate where algo='$nicehash->algo' order by time desc limit 1"));

	echo "<tr class='ssrow'>";
	echo "<td>$nicehash->orderid</td>";
	echo "<td>$nicehash->algo</td>";
	echo "<td>$nicehash->btc</td>";
	echo "<td>$price2</td>";

	if($yaamp > $price2*1.1)
		echo "<td style='color: #4a4'>$yaamp</td>";
	else
		echo "<td>$yaamp</td>";

	if($nicehash->price > $yaamp)
		echo "<td style='color: #a44'>$nicehash->price</td>";
	else
		echo "<td>$nicehash->price</td>";
	
	echo "<td>$nicehash->speed</td>";
	echo "<td>$d</td>";
	
	if(!$nicehash->workers && !$nicehash->accepted && !$nicehash->rejected)
	{
		echo "<td colspan=3></td>";
	}
	else
	{
		echo "<td>$nicehash->workers</td>";
		echo "<td>$nicehash->accepted</td>";
		echo "<td>$nicehash->rejected</td>";
	}
	
	if($nicehash->active)
		echo "<td><a href='/nicehash/stop?id=$nicehash->id'>stop</a></td>";
	else
		echo "<td><a href='/nicehash/start?id=$nicehash->id'>start</a></td>";
		
	echo "</tr>";
}

echo "</tbody></table>";


