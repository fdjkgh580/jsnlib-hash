<?
namespace Jsnlib\Hash;

class Form 
{

    //<input> 名稱
    public $hashname    =   "thisisdefaultname"; 
    
    //<input> 值
    public $hashval;
    
    //hash 加密的形式
    public $hashtype    =   "sha512";
    
    //種子 可隨意自訂字串做為hash加密
    public $seed        =   "grkiRETFREufeEFfeiwbhbgjrkegudkeso;lewrfuefn2349806uti4th884t63532ngrn";
    
    function __construct()
    {
        
        $type           =   $this->hashtype;
        
        $this->seed     =   hash($type, $this->seed . time() . uniqid());
        
        $this->hashname =   hash($type, $this->hashname . $this->seed);
        
        //值 = loop (名稱 + 加密過的種子密碼)
        $this->hashval  =   $this->loophashval();
        
    }
    
    
    //利用loop加長
    private function loophashval()
    {
        $innerval = hash($this->hashtype, $this->hashname . $this->seed); 
        $loopval = null;
        for ($a= 10; $a<=15; $a++) 
        {
            $loopval .= $innerval . hash($this->hashtype, $a);
        }
        return $loopval;
    }
    
    //註冊session
    private function register_session()
    {
        return $_SESSION['jshform'][$this->hashname] = $this->hashval;
    }
    
    /*
     * 因為<form>裡面的<input name>每秒受$this->seed影響，是變動的，所以把 hashname    名稱註冊到session
    */
    private function register_hashname()
    {
        return $_SESSION['jshform']['in_session_name'] = $this->hashname;
    }
        
    //放置input
    function put($type): string
    {
        $this->register_session();
        $this->register_hashname();
        if ($type == "name")
            return $_SESSION['jshform']['in_session_name'];
        else if ($type == "value")
            return $this->hashval;
        else throw new \Exception("參數錯誤");
    }

    public function check(bool $is_continue = false): bool
    {
        if (empty($_SESSION['jshform'])) return false;

        $input_name = $_SESSION['jshform']['in_session_name'];
        if (empty($input_name)) return false;
        if (empty($_POST[$input_name])) return false;        
        if (empty($_SESSION['jshform'][$input_name])) return false;

        if ($_POST[$input_name] !== $_SESSION['jshform'][$input_name]) return false;

        // 若不繼續使用
        if ($is_continue === false) unset($_SESSION['jshform']);
        
        return true;
    }
        
    // //驗證 - 使用重複循環
    // function check()
    // {
    //     if (empty($_SESSION['jshform'])) return false;

    //     echo $input_name = $_SESSION['jshform']['in_session_name'];
    //     die;
    //     if (($_POST[$input_name] != $_SESSION['jshform'][$input_name] ) or (empty($_POST[$input_name]) or empty($_SESSION['jshform'][$input_name]) ) ) 
    //     {
    //         return false;
    //     }
    //     else 
    //     {
    //         return true;
    //     }
    // }
        
    // // 驗證 - 不使用重複循環 (可用在只允許顯示一次的表單結果)
    // function check_die():bool
    // {
    //     if (empty($_SESSION['jshform'])) return false;

    //     $input_name = $_SESSION['jshform']['in_session_name'];

    //     if (($_POST[$input_name] != $_SESSION['jshform'][$input_name] ) or (empty($_POST[$input_name]) or empty($_SESSION['jshform'][$input_name]) ) ) 
    //     {
    //         return false;
    //     }
    //     else 
    //     {
    //         //若不使用，可避免送出表單時重整無法繼續操作
    //         unset($_SESSION['jshform']);
    //         return true;
    //     }

    // }
    


        
}