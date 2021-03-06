<?php
namespace App\Services;

use App\Models\Api;
use App\Services\CurlService;
class SunBetService{
    public $pre;   // 玩家前缀
    public  $domain;
    public  $comId;
    public $comKey;
    public $gamePlatform;
    public $debug;
    public $salt ;
    public $betLimitCode;
    public $currencyCode;
    public $isspeed;
    public $isdemo;

    public function __construct()
    {
        $mod = Api::where('api_name', 'SunBet')->first();
        $this->pre = $mod->prefix;// 玩家前缀
        $this->domain = $mod->api_domain;
        $this->comId = $mod->api_id;
        $this->comKey = $mod->api_key;
        $this->gamePlatform = $mod->api_name;
        $this->debug = 0;
        $this->salt = $this->salt(5);
        $this->betLimitCode = 'A';
        $this->currencyCode = 'RMB';
        $this->isspeed = 0;
        $this->isdemo = 0;
    }

    public function register($username,$password,$limit = '1,1,1,1,1,1,1,1,1,1,1,1,1,1', $limitvideo = 2, $limitroulette = 1){
        $code = $this->salt.md5($this->comKey.$this->comId.$username.$password.$this->currencyCode.$limit.$limitvideo.$limitroulette.$this->salt);
        $url = "http://".$this->domain."/api/SunBet/Register.ashx";
        $post_data = array('apiAccount'=>$this->comId,'userName'=>$username,'password'=>$password,'currencyCode'=>$this->currencyCode,'limit'=>$limit,'limitvideo'=>$limitvideo,'limitroulette' =>$limitroulette,'code'=>$code);

        $receive = $this->send_post($url,$post_data);
        return $receive;
    }

    /*
     * 登录
     */
    public function login($username,$password,$gametype = 1, $iframe = 0, $domain){
        $gamekind = 0;
        $platformname = 'Oriental';
        $languageCode = 'zh-cn';
        $code = $this->salt.md5($this->comKey.$this->comId.$username.$password.$this->salt);
        $url = "http://".$this->domain."/api/SunBet/Login.ashx";
        $post_data = array('apiAccount'=>$this->comId,'userName'=>$username,'password'=>$password,'domain'=>$domain,'gamekind'=>$gamekind,'gametype'=>$gametype,'platformname'=>$platformname,'iframe'=>$iframe,'languageCode'=>$languageCode,'code'=>$code);

        $receive = $this->send_post($url,$post_data);
        return $receive;
    }


    /*
     * 存款 http://<domain>/api/ag/deposit.ashx
     */
    public function deposit($username,$password,$amount){
        $transSN = date("YmdHms");//交易编号
        $code = $this->salt.md5($this->comKey.$this->comId.$username.$password.$transSN.$amount.$this->salt);
        $url = "http://".$this->domain."/api/SunBet/Deposit.ashx";
        $post_data = array('apiAccount'=>$this->comId,'userName'=>$username,'password'=>$password,'transSN'=>$transSN,'amount'=>$amount,'code'=>$code);
        $receive = $this->send_post($url,$post_data);
        return $receive;
    }

    /*
     * 提款 http://<domain>/api/ag/withdrawal.ashx
     */
    public function withdrawal($username,$password,$amount){
        $transSN = date("YmdHms");//交易编号
        $code = $this->salt.md5($this->comKey.$this->comId.$username.$password.$transSN.$amount.$this->salt);
        $url = "http://".$this->domain."/api/SunBet/Withdrawal.ashx";
        $post_data = array('apiAccount'=>$this->comId,'userName'=>$username,'password'=>$password,'transSN'=>$transSN,'amount'=>$amount,'code'=>$code);
        $receive = $this->send_post($url,$post_data);
        return $receive;
    }

    /*
     * 查询余额 http://<domain>/api/ag/balance.ashx
     */
    public function balance($username,$password){
        $code = $this->salt.md5($this->comKey.$this->comId.$username.$password.$this->salt);
        $url = "http://".$this->domain."/api/SunBet/Balance.ashx";
        $post_data = array('apiAccount'=>$this->comId,'userName'=>$username,'password'=>$password,'code'=>$code);

        $receive = $this->send_post($url,$post_data);
        return $receive;
    }


    /*
    * 游戏记录 http://<domain>/api/ag/betrecord.ashx
    */
    public function betrecord($startDate,$endDate){
        $tableid = '';
        $Stage = '';
        $inning = '';
        $code = $this->salt.md5($this->comKey.$this->comId.$tableid.$Stage.$inning.$startDate.$endDate.$this->salt);
        $url = "http://".$this->domain."/api/SunBet/GetGameResult.ashx";
        $post_data = array('apiAccount'=>$this->comId,'tableid' => $tableid, 'Stage' => $Stage, 'inning' => $inning,'datestart'=>$startDate,'dateend'=>$endDate,'code'=>$code);
        $receive = $this->send_post($url,$post_data);
        return $receive;
    }

    public function getBettingRecord($vendorid)
    {
        $code = $this->salt.md5($this->comKey.$this->comId.$vendorid.$this->salt);
        $url = "http://".$this->domain."/api/SunBet/GetBettingRecord.ashx";
        $post_data = array('apiAccount'=>$this->comId,'vendorid'=>$vendorid,'code'=>$code);
        $receive = $this->send_post($url,$post_data);
        return $receive;
    }

    public function GetVendorId($ordernumber)
    {
        $code = $this->salt.md5($this->comKey.$this->comId.$ordernumber.$this->salt);
        $url = "http://".$this->domain."/api/SunBet/GetVendorId.ashx";
        $post_data = array('apiAccount'=>$this->comId,'ordernumber'=>$ordernumber,'code'=>$code);
        $receive = $this->send_post($url,$post_data);
        return $receive;
    }

    /*
    * 商户余额查询
    */
    public function credit(){
        $code = $this->salt.md5($this->comKey.$this->comId.$this->salt);
        $url = "http://".$this->domain."/api/SunBet/Credit.ashx";
        $post_data = array('apiAccount'=>$this->comId,'code'=>$code);

        $receive = $this->send_post($url,$post_data);
        return $receive;
    }

    protected function send_post($url,$post_data) {
        $result = (new CurlService())->post($url, $post_data);

        return $result;
    }

    protected function salt($length)
    {
        $key="";
        $pattern='1234567890abcdefghijklmnopqrstuvwxyz';
        for($i=0;$i<$length;$i++)
        {
            $key .= $pattern{mt_rand(0,35)};
        }
        return $key;
    }
}