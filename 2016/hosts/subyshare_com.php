<?php

class dl_subyshare_com extends Download {
    
    public function CheckAcc($cookie){
        $data = $this->lib->curl("http://subyshare.com/?op=my_account", $cookie, "");
        if(stristr($data, '<strong>PREMIUM</strong>')) return array(true, "Until ".$this->lib->cut_str($data, 'Premium account expire: <b>', '</b></span>')."<br>Traffic available today: ".strtoupper(trim($this->lib->cut_str($data, 'Traffic available today <strong>','</strong>'))));
        else if(stristr($data, '<b>Expired</b></span>') && !stristr($data, 'Premium account expire')) return array(false, "accfree");
		else return array(false, "accinvalid");
    }
    
    public function Login($user, $pass){
        $data = $this->lib->curl("http://subyshare.com/", "lang=english", "op=login&login={$user}&password={$pass}&redirect=");
        $cookie = "lang=english;{$this->lib->GetCookies($data)}";
		return $cookie;
    }
	
    public function Leech($url) {
		list($url, $pass) = $this->linkpassword($url);
		$data = $this->lib->curl($url, $this->lib->cookie, "");
		
		if($pass) {
			$post = $this->parseForm($this->lib->cut_str($data, '<Form', '</Form>'));
			$post["password"] = $pass;
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			if(stristr($data,'Wrong password'))  $this->error("wrongpass", true, false, 2);
			elseif($this->isredirect($data))	return trim($this->redirect);
		}
		if(stristr($data,'type="password" name="password'))  $this->error("reportpass", true, false);
		elseif(strstr($data,'File Not Found') || strstr($data,'The file was removed by administrator') || strstr($data, '<td><h4></h4></td>')) $this->error("dead", true, false, 2);
		elseif(!$this->isredirect($data)) {
			$post = $this->parseForm($this->lib->cut_str($data, '<Form', '</Form>'));
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			if($this->isredirect($data))	return trim($this->redirect);
		}
		else  return trim($this->redirect);
		return false;
    }
	
}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Uptobox Download Plugin
* Downloader Class By [FZ]
* Created: ..;; LTT ::..
* Fix by hitpro
*/
?>