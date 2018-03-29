<?
session_start();
require_once '../../vendor/autoload.php';
$ajax = new Jsnlib\Hash\Ajax;
?>

<script src="plubin/jquery-1.7.2.js"></script>
<script>
$(function (){
	// 將資料連同 .hash 送出
	$(".send").on("click", function (){
	    $.post('Server.php', {
	    	'content': $('.content').val(),
	    	'hash': $('.hash').val()
	    }, function (data){
	    	// 若成功將替換 .hash 的值
	    	if (data.status == "success"){
	    		$('.hash').val(data.newhash);
	    	}
	    }, "json");
	})
})
</script>

<!-- 設定一個隱藏的 input 作為夾帶 hash 的值 -->
<input type="hidden" class="hash" value="<?=$ajax->put()?>">

<!-- 建立表單 -->
<input type="text" class="content">
<button class="send">AJAX送出</button>