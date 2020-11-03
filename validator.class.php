<?php  
require 'paginate.class.php' ;
/* ####
class  by sajjad kareem
*/
class validator extends paginate{

/* find_errors prop */
private $errors=[];
/* find_errors prop */
/* validation prop */
private $input_names=array();
/* validation prop */
/* check all validation  */
private $check=array();
/* check all validation  */
/* masgs */
public $masgs=array();
/* masgs */
/* flag all validation */
//  public $ok;
/* custom name */
private $custom_name=array();
/* custom name */
private $custom_name2=array();
/* flag all validation */
public  function test_validate(){

  return $this->find_errors($this->check);}
public  function validation($valid_array,$msg_array=null){
    foreach($valid_array as $key=>$value){
    
  
          array_push($this->input_names,$this->escape_string(strip_tags(htmlentities(trim($_POST[$key])))));
       
    
    foreach($value as $option){

    /* start validation part  */
    
    switch($option){
     case substr($option,0,3) == "max":
      $this->max(substr($option,4),$key);
     break;
     case substr($option,0,3) == "min":
      $this->min(substr($option,4),$key);
     break;
     case substr($option,0,4)=="name":
      $this->custom_name[]=$key.':'.substr($option,5);
    break;
     case "require":
      $this->require($key);
     break;
     case "url":
     $this->url($key);
     break;
     case "number":
      $this->number($key);
     break;
     case "email":
      $this->email($key);
      break;
      case "eng":
     $this->eng($key);
      break;
      case "date":
      $this->date($key);
        break;
    } ;


   }

  }
  /*get the name inputs */
  // foreach($this->input_names as $inputs){
  //    echo     $inputs .'<br>';
  // }
    /*get the name inputs */}
private function date($input_name=null,$custom_name_arabic=null){
    foreach($this->custom_name as $value){
      $pos= strpos($value,":");
  /*custom name */$name= substr($value,0,$pos);
  /* origanl name */$custom_name= substr($value,$pos+1);

  if($name == $input_name){
    $custom_name_arabic=$custom_name;
  }

    }
    if(in_array($this->escape_string(strip_tags(htmlentities(trim(@$_POST[$input_name])))),$this->input_names)  ){
      $input_value=$this->escape_string(strip_tags(htmlentities(trim($_POST[$input_name]))));
    }
    if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$input_value)) {
      if(isset($custom_name_arabic)){
        array_push($this->masgs,' '.'الرجاء  كتابة تاريخ  صحيح في حقل  '.$custom_name_arabic);

      }else{
        array_push($this->masgs,' '.'الرجاء  كتابة تاريخ  صحيح في حقل  '.$input_name);

      }
      array_push($this->check,"false");
    }else{
      array_push($this->check,"true");
    }}
private function max($max_number,$input_name=null,$custom_name_arabic=null){
  foreach($this->custom_name as $value){
      $pos= strpos($value,":");
  /*custom name */$name= substr($value,0,$pos);
  /* origanl name */$custom_name= substr($value,$pos+1);

  if($name == $input_name){
    $custom_name_arabic=$custom_name;
  }

  }
    if(in_array($this->escape_string(strip_tags(htmlentities(trim(@$_POST[$input_name])))),$this->input_names)  ){
      $input_value=$this->escape_string(strip_tags(htmlentities(trim(@$_POST[$input_name]))));
    }
  // echo strlen($input_value) .'<Br>';
      if(strlen("$input_value") > $max_number){
        if(isset($custom_name_arabic)){
          array_push($this->masgs,' '.'  '.'  '.'  يجب ان تكون عدد الكلمات او الارقام في حقل'.' '.$custom_name_arabic. ' لا تتعدى'.' '. $max_number);
  
        }else{
          array_push($this->masgs,$max_number.' '.' لا تتعدى'.' '.$input_name.' '.'  يجب ان تكون عدد الكلمات او الارقام في حقل ' );

        }
       array_push($this->check,"false");
      }else{
        array_push($this->check,"true");
      };}
private function min($min_number,$input_name=null,$custom_name_arabic=null){
    foreach($this->custom_name as $value){
        $pos= strpos($value,":");
    /*custom name */$name= substr($value,0,$pos);
    /* origanl name */$custom_name= substr($value,$pos+1);

    if($name == $input_name){
      $custom_name_arabic=$custom_name;
  }

    }
      if(in_array($this->escape_string(strip_tags(htmlentities(trim($_POST[$input_name])))),$this->input_names)  ){
        $input_value=$this->escape_string(strip_tags(htmlentities(trim($_POST[$input_name]))));
      }
    // echo strlen($input_value) .'<Br>';
        if(strlen("$input_value") < $min_number){
          if(isset($custom_name_arabic)){
            array_push($this->masgs, $min_number.' '.'  '.'  '.'  يجب ان تكون عدد الكلمات او الارقام في حقل'.' '.$custom_name_arabic. ' على الاقل ');

    
          }else{
            array_push($this->masgs,' '.' على الاقل'.' '.$input_name.' '.' يجب انت تكون عدد الكلمات او الارقام في حقل   '.$min_number);

          }
      array_push($this->check,"false");
        }else{
          array_push($this->check,"true");
        };}
private function number($input_name=null,$custom_name_arabic=null){
    foreach($this->custom_name as $value){
      $pos= strpos($value,":");
  /*custom name */$name= substr($value,0,$pos);
  /* origanl name */$custom_name= substr($value,$pos+1);

  if($name == $input_name){
    $custom_name_arabic=$custom_name;
  }
  
    }
 
    if(in_array($this->escape_string(strip_tags(htmlentities(trim(@$_POST[$input_name])))),$this->input_names)  ){
      $input_value=$this->escape_string(strip_tags(htmlentities(trim(@$_POST[$input_name]))));
    }
    if(!preg_match("/^[0-9]+$/",$input_value)){
      if(isset($custom_name_arabic)){
        array_push($this->masgs,' '.' الرجاء ادخال ارقام فقط في حقل   '. $custom_name_arabic);

      }else{
      array_push($this->masgs,$input_name.' '.' الرجاء ادخال ارقام فقط في حقل   ');

      };
      array_push($this->check,"false");
    }else{
      array_push($this->check,"true");
    }}
private function require($input_name=null,$custom_name_arabic=null){
    foreach($this->custom_name as $value){
      $pos= strpos($value,":");
  /*custom name */$name= substr($value,0,$pos);
  /* origanl name */$custom_name= substr($value,$pos+1);

  if($name == $input_name){
    $custom_name_arabic=$custom_name;
  }

    }
    
    if(in_array($this->escape_string(strip_tags(htmlentities(trim(@$_POST[$input_name])))),$this->input_names)  ){
      $input_value=$this->escape_string(strip_tags(htmlentities(trim(@$_POST[$input_name]))));
    }
    if (empty($input_value)) {
      if(isset($custom_name_arabic)){
        array_push($this->masgs,'لا تترك حقل '.$custom_name_arabic.' '.'فارغ');

      }else{
        array_push($this->masgs,'  فارغ'.' '.$input_name.' '.'  لا تترك حقل   ');

      }
      array_push($this->check,"false");
    }else{
      array_push($this->check,"true");
    }}
private function url($input_name=null,$custom_name_arabic=null){
    foreach($this->custom_name as $value){
      $pos= strpos($value,":");
  /*custom name */$name= substr($value,0,$pos);
  /* origanl name */$custom_name= substr($value,$pos+1);

  if($name == $input_name){
    $custom_name_arabic=$custom_name;
  }

    }
    if(in_array($this->escape_string(strip_tags(htmlentities(trim(@$_POST[$input_name])))),$this->input_names)  ){
      $input_value=$this->escape_string(strip_tags(htmlentities(trim($_POST[$input_name]))));
    }
    if (!filter_var($input_value,FILTER_VALIDATE_URL)) {
      if(isset($custom_name_arabic)){
        array_push($this->masgs,' '.'الرجاء  كتابة رابط  صحيح في حقل  '.$custom_name_arabic);

      }else{
        array_push($this->masgs,$input_name.' '.'الرجاء  كتابة رابط  صحيح في حقل  ');

      }
      array_push($this->check,"false");
    }else{
      array_push($this->check,"true");
    }}
private function eng($input_name=null,$custom_name_arabic=null){
    foreach($this->custom_name as $value){
      $pos= strpos($value,":");
  /*custom name */$name= substr($value,0,$pos);
  /* origanl name */$custom_name= substr($value,$pos+1);

  if($name == $input_name){
    $custom_name_arabic=$custom_name;
  }

    }
    if(in_array($this->escape_string(strip_tags(htmlentities(trim(@$_POST[$input_name])))),$this->input_names)  ){
      $input_value=$this->escape_string(strip_tags(htmlentities(trim($_POST[$input_name]))));
    }
    $regex = '/^[a-zA-Z0-9$@$!%*?&#^-_. +]+$/';
    if (!preg_match($regex,$input_value) ) {
      if(isset($custom_name_arabic)){
        array_push($this->masgs,'الرجاء كتابة اللغة الانكليزية فقط في حقل'.' '.$custom_name_arabic);

      }else{
        array_push($this->masgs,$input_name.' '.'الرجاء كتابة اللغة الانكليزية فقط في حقل');

      }
      array_push($this->check,"false");
    }else{
      array_push($this->check,"true");

  }}
private function email($input_name=null,$custom_name_arabic=null){
    foreach($this->custom_name as $value){
      $pos= strpos($value,":");
  /*custom name */$name= substr($value,0,$pos);
  /* origanl name */$custom_name= substr($value,$pos+1);

  if($name == $input_name){
    $custom_name_arabic=$custom_name;
  }

    }
    if(in_array($this->escape_string(strip_tags(htmlentities(trim(@$_POST[$input_name])))),$this->input_names)  ){
      $input_value=$this->escape_string(strip_tags(htmlentities(trim($_POST[$input_name]))));
    }
    if (!@filter_var($this->protection_input($input_value),FILTER_VALIDATE_EMAIL)){
      if(isset($custom_name_arabic)){
        array_push($this->masgs,'الرجاء كتابة بريد الكتروني  صحيح في حقل '.' '.$custom_name_arabic);

      }else{
        array_push($this->masgs,$input_name.' '.'الرجاء كتابة بريد الكتروني  صحيح في حقل ');

      }
      array_push($this->check,"false");

    }else{
      array_push($this->check,"true");
    }
  }
public  function errors_validate(){
    foreach($this->masgs as $errors){
      echo  '  <div class="alert alert-dismissible alert-warning text-right direction-rtl" role="alert">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>'.$errors.'</strong>.
            </div>';
    }
  }

public  function find_errors($errors_array){
  $error=  $this->errors[]=$errors_array;

  $flag_false=array();
 $flag_true=array();
 $errors_array=array();
      
    for($i=0;$i<count($error);$i++){
           if($error[$i]  =="false"){
           array_push($flag_false,"false");

           }else{
            array_push($flag_true,"true");

           }
  }  
    if($flag_false !=null){
      return false;
    }else if($flag_true!=null){
      return true;
    }

                    //   return $this->chack_bool;

            }
    };


?>
