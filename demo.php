<?php
require "./vendor/autoload.php";

use PhpJsonDb\JsonDb\JsonAction;

$jsondb = new JsonAction("sql");

$data = $jsondb->update(array("a"=>"fex"),1,"id");

?>