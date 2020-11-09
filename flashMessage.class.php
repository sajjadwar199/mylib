<?php 
require 'validator.class.php' ;
class flashMessage extends validator{
/* 
 * class create by sajjad kareem 
 * create in 2020/11/3 8:04pm
 */ 
  //نوع الرسالة 
  private $messageType;
  //الرسالة 
  private $message;
  //انواع الرسائل القياسية
  private const standrMessageType=array("success","warning","primary","danger","info","light",'dark');
  /* استدعاء الدالة الرئيسي */
  public function setMessage($message,$messageType){
      $this->message=$message;
      $this->messageType=$messageType;
      $this->messageOption();
  }
  /* هذا الدالة مسؤؤلة عن اختيار نوع الرسالة */
  public function messageOption(){
      switch($this->messageType){
       case "success":
        $this->success();
       break;
       case "warning":
        $this->warning();
       break;
       case "primary":
        $this->primary();
       break;
       case "danger":
        $this->danger();
       break;
       case "info":
        $this->info();
       break;
       case "light":
        $this->light();
       break;
       case "dark":
        $this->dark();
       break;
       default :
       $this->success();
      };
  }
   /*  انواع الرسائل */
   private function success(){
   $_SESSION['success_message']='<div class="alert alert-success"  style="text-align:right;" role="alert">'.$this->message.'</div>';
  }

  private function warning(){
    $_SESSION['warning_message']='<div class="alert alert-warning"  style="text-align:right;"  role="alert">'.$this->message.'</div>';
  }
  private function primary(){
    $_SESSION['primary_message']='<div class="alert alert-primary"  style="text-align:right;" role="alert">'.$this->message.'</div>';
 }
 private function danger(){
    $_SESSION['error_message']='<div class="alert alert-danger"  style="text-align:right;" role="alert">'.$this->message.'</div>';
 }

 private function info(){
    $_SESSION['info_message']='<div class="alert alert-info"  style="text-align:right;" role="alert">'.$this->message.'</div>';
 } 


 private function light(){
    $_SESSION['light_message']='<div class="alert alert-light"  style="text-align:right;" role="alert">'.$this->message.'</div>';
 } 

 private function dark(){
    $_SESSION['dark_message']='<div class="alert alert-dark"  style="text-align:right;" role="alert">'.$this->message.'</div>';
 } 
  /*  انواع الرسائل */

  /* هذه الدالة خاصة بطبع الرسالة */
  public function printMessage(){
  if(isset($_SESSION['success_message'])){
        echo $_SESSION['success_message'];
        $_SESSION['success_message']='';
  }if(isset($_SESSION['warning_message'])){
        echo   $_SESSION['warning_message'];
        $_SESSION['warning_message']='';
   }if(isset($_SESSION['primary_message'])){
        echo $_SESSION['primary_message'];
        $_SESSION['primary_message']='';
   } if(isset($_SESSION['error_message'])){
        echo $_SESSION['error_message'];
        $_SESSION['error_message']='';
   } if(isset($_SESSION['info_message'])){
        echo $_SESSION['info_message'];
        $_SESSION['info_message']='';
   } if(isset($_SESSION['light_message'])){
        echo $_SESSION['light_message'];
        $_SESSION['light_message']='';
   }if(isset($_SESSION['dark_message'])){
        echo $_SESSION['dark_message'];
        $_SESSION['dark_message']='';
   }
  }
};
?>