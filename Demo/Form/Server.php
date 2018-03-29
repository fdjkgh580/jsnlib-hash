<?
session_start();
require_once '../../vendor/autoload.php';
$form = new Jsnlib\Hash\Form;

/* 1. 使用循環 */
// if ($form->check(true) !== true) die("程序已執行完畢，請返回前一頁重新操作");

/* 2. 不使用循環 */
if ($form->check() !== true) die("程序已執行完畢，請返回前一頁重新操作");

echo "success";