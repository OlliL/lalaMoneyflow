<?php
#apc_store('test',"hugo");
#var_dump(apc_fetch('test'));
#var_dump(apc_fetch('lalaMoneyflowText#1-1'));
#var_dump(apc_fetch('lalaMoneyflowText#2-1'));
#var_dump(apc_fetch('CapitalsourceById#1'));
$yac = new Yac();
$yac->set('test','hugo');
var_dump($yac->get('test'));
?>

