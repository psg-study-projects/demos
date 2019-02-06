<?php

$attrs = $_POST;

$_state = json_decode(file_get_contents('store.txt'), true); 

if ( empty($_state) ) {
    $_state = [];
}

$_obj = $attrs;
$_obj['datetime'] = date("Y-m-d H:i:s");
$_obj['total_value'] = $_obj['stock_quantity'] * $_obj['item_price'];
$_state[] = $_obj;

$fp = fopen('store.txt', 'w');
$j = json_encode($_state);
fwrite($fp, $j);
fclose($fp);

$sum = 0;
foreach ($_state as $i => $o) {
    $sum += $o['total_value'];
}

header('Content-Type: application/json');
echo json_encode(['items'=>$_state, 'sum'=>$sum]);
exit;
