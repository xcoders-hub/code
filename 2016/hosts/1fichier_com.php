<?php

class dl_1fichier_com extends Download {
   
   public function CheckAcc($cookie) {
        $data = $this->lib->curl("https://1fichier.com/console/abo.pl", $cookie, "");
        if (stristr($data, 'Your account is Premium until')) return array(true, "Account: ".$this->lib->cut_str($data, "Your account is ", '<span class="spacer spacer-10"></span>'));
        elseif (stristr($data, 'After test for FREE our services, choose your Offer')) return array(false, "accfree");
        else return array(false, "accinvalid");
   }
   
   public function Login($user, $pass){
      $data = $this->lib->curl("https://1fichier.com/login.pl", "", "mail={$user}&pass={$pass}&lt=on&purge=on&valider=Send");
      $cookie = $this->lib->GetCookies($data);
         return $cookie;
   }
   
   public function Leech($url) {
      $data = $this->lib->curl($url, $this->lib->cookie, "");
      if(stristr($data, "The requested file could not be found")) $this->error("dead", true, false, 2);
      elseif($this->isredirect($data)) return trim($this->redirect);
      return false;
   }

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* 1fichier Download Plugin 
* Downloader Class By [FZ]
* Fixed Check Account by Enigma [06.02.2016]
*/
?>