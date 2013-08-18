<?php
define('DB_CONFIG_BASE_PATH', '/home/rulai/MasterMiaoChan-Fellow-Guidance/conf/');

require_once('./Database.class.inc.php');
require_once('./SingleRecordOperator.class.inc.php');
require_once('./ListOperator.class.inc.php');
require_once('/home/rulai/MasterMiaoChan-Fellow-Guidance/libraries/tables/NewFriend.class.inc.php');
//require_once('/home/shiheng/polaris/MVCFramework/libraries/tables/OncallitemList.class.inc.php');
//require_once('./PagingOperator.class.inc.php');

//$a = new PagingOperator('Oncallitem', 5);
//$t = $a->getListInPage(1);

//$t = new OncallitemList(array('type' => 'BUG'), null, array(array('action_id', 'ASC')));
//$t = new OncallitemList(null, array(0, 2), array(array('action_id', 'ASC')));
//print_r($t->toArray());

//$t = new NewFriend(1);
//var_dump($t->name);

$t = new NewFriend(array('name' => '黃勛胤'));
var_dump($t->save());
var_dump($t->id);
?>
