<?php
require_once('Changer.php');

// http://www.dev-changemaker.com/make-change.php?total_cost=601&amount_provided=800&ctype=yen
// http://www.dev-changemaker.com/make-change.php?total_cost=601&amount_provided=800&ctype=usd

$cType = $_GET['ctype'] ?? 'usd'; // defaults to us dollars
$totalCost = $_GET['total_cost'] ?? 0;
$amountProvided = $_GET['amount_provided'] ?? 0;
$isPretty = array_key_exists('pretty',$_GET); 

$changer = Changer::factory( $cType );
$results = $changer->makeChange( $totalCost, $amountProvided );

// -- output --
header('Content-Type: application/json');
echo json_encode($results, $isPretty?JSON_PRETTY_PRINT:null);
