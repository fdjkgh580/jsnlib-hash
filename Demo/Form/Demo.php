<?
session_start();
require_once '../../vendor/autoload.php';
$form = new Jsnlib\Hash\Form;
?>
<form method="post" action="Server.php">
    <input type="hidden" name="<?=$form->put('name')?>" value="<?=$form->put('value')?>">
    <input name="write" type="text" autofocus>
    <button type="submit">send</button>
</form>

