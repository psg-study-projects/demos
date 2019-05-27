<?php
require_once('Usdollar.php');

// http://www.dev-changemaker.com/make-change.php?total_cost=5.07&amount_provided=6

$usd = new Usdollar();

$totalCost = ($_GET['total_cost'] ?? 0)*100; // convert to base unit (cents for USD)
$amountProvided = ($_GET['amount_provided'] ?? 0)*100; // convert to base unit (cents for USD)
$isPretty = array_key_exists('pretty',$_GET); 

$results = $usd->makeChange( $totalCost, $amountProvided );

// -- output --
header('Content-Type: application/json');
echo json_encode($results, $isPretty?JSON_PRETTY_PRINT:null);
