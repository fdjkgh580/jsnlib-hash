<?
session_start();
require_once '../../vendor/autoload.php';
$ajax = new Jsnlib\Hash\Ajax;

$array = $ajax->check($_POST['hash']);

//使用json回傳
echo json_encode($array);