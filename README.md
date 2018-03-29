# jsnlib-hash
讓 Client 夾帶一個 Hash 並傳送數據到 Server，可以判斷傳送的來源是否合法。提供兩種方式，第一種是 AJAX 動態交換 Hash，第二種是傳統表單 Form 發送的方式。

# 方法一、AJAX
透過 AJAX 的傳送數據，並取回新的 Hash，參考步驟如下
1. 啟用 session 並初始化
````php
session_start();
require_once 'vendor/autoload.php';
$ajax = new Jsnlib\Hash\Ajax;
````
2. HTML 建立表單
````html
<!-- 設定一個隱藏的 input 作為夾帶 hash 的值 -->
<input type="hidden" class="hash" value="<?=$ajax->put()?>">

<!-- 建立表單 -->
<input type="text" class="content">
<button class="send">送出</button>
````

3. 透過 jQuery 發送 POST 到伺服器
````javascript
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
````

4. Server 的部分驗證 Client 的合法性
````php
session_start();
require_once 'vendor/autoload.php';
$ajax = new Jsnlib\Hash\Ajax;

$array = $ajax->check($_POST['hash']);

//使用json回傳
echo json_encode($array);
````
### 成功返回的參數 
- status = success
- newhash 新的 hash
### 失敗返回的參數 
- status = false
- from_hash 來源的 hash
- here_hash 本地應該合法通過的 hash

# 方法二、Form
認證 Client 傳送過來的表單是為否合法來源。

1. 啟用 Session 並初始化
````php
session_start();
require_once 'vendor/autoload.php';
$form = new Jsnlib\Hash\Form;
````
2. HTML 表單，並置入 hash 供 Server 驗證
````html
<form method="post" action="Server.php">
    <input type="hidden" name="<?=$form->put('name')?>" value="<?=$form->put('value')?>">
    <input name="write" type="text" autofocus>
    <button type="submit">send</button>
</form>
````
3. Server 認證來源是否合法
````php
session_start();
require_once '../../vendor/autoload.php';
$form = new Jsnlib\Hash\Form;

/* 1. 使用循環 */
// if ($form->check(true) !== true) die("程序已執行完畢，請返回前一頁重新操作");

/* 2. 不使用循環 */
if ($form->check() !== true) die("程序已執行完畢，請返回前一頁重新操作");

echo "success";
````
### check($is_continue = false): bool
- is_continue 若為 true，重新整理驗證仍然有效；false 則僅有一次性的驗證。
