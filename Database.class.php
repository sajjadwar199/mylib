<?php 
session_start();
class TheDatabase{
private $localhost='localhost';
private $username='root';
private $password='';
public $database='form';
protected $con;
protected $update_array=[];
public function connectPdo(){
  $connect = new PDO("mysql:host=".$this->localhost.";dbname=".$this->database,$this->username,$this->password);return $connect;}
public function connect(){
      $con = mysqli_connect($this->localhost, $this->username,$this->password,$this->database);
      mysqli_set_charset($con,"utf8");
      if (!$con) {
          echo "لم يتم الاتصال";
      } else {
          echo "";
      }
      return $con;}

public function escape_string($value){

    $conn = $this->connect();
        return mysqli_real_escape_string($conn,$value);}


public function insert($table_name,$assoc_array,$successmsg=null){

    $keys = array();
    $values= array();
    foreach($assoc_array as $key => $value){
        $keys[] = $key;
       
          $values[] =$this->escape_string(strip_tags(htmlentities(@$_POST[$value]))) ;
        
    }
    $query = "INSERT INTO `$table_name`(`".implode("`,`", $keys)."`) VALUES('".implode("','", $values)."')";
   
    $conn = $this->connect();
    $q=  mysqli_query($conn,$query);
    if($q){
    $_SESSION['success_message']=$successmsg;
    echo  '     <script>
    if ( window.history.replaceState ) {
      window.history.replaceState( null, null, window.location.href );
    }
    </script>';
  }else{
    $_SESSION['error_massage'] = 'هناك خطا  ';

  }} 

public function update($table,$array_update,$where,$successmsg=null){
    foreach($array_update as  $key=>$value)
  {
    $value=$this->protection_input($value);
    array_push($this->update_array,$key.'='."'$value'");
  }
  $value_update= implode(",",$this->update_array);
    
    $sql = "UPDATE $table SET $value_update   WHERE $where";
      $conn = $this->connect();
    $q=  mysqli_query($conn,$sql);

    if($q){
      $_SESSION['success_message']=$successmsg;
      echo '     <script>
      if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
      }
      </script>';
    }else{
      $_SESSION['error_massage'] = 'هناك خطا  ';

    } 
    } 
public function delete($table,$idname,$id,$successmsg=null){

   
        $query = "DELETE FROM $table WHERE $idname = $id";
    $conn = $this->connect();
      $q= mysqli_query($conn,$query);
       if($q){
       
        $_SESSION['success_message']=$successmsg;
        echo '     <script>
        if ( window.history.replaceState ) {
          window.history.replaceState( null, null, window.location.href );
        }
        </script>';
      }else{
        $_SESSION['error_massage'] = 'هناك خطا  ';
    
      }
        } 

public function show($table,$where=null){
 $query = "SELECT * FROM $table   $where  ";
 $conn = $this->connect();
 $q= mysqli_query($conn,$query);
 if ($q == false) {
  return false;
 }
 $rows = array();
 while ($row =mysqli_fetch_object($q)) {
  $rows[] = $row;
 }

 return $rows;
    } 


public function number_query($table,$where=null){
  $conn = $this->connect();
  $query = "SELECT * FROM $table  $where  ";
  $q= mysqli_query($conn,$query);
  if ($q == false) {
    return false;
  }
  $num=mysqli_num_rows($q);
  return $num;}  

public function fetch($q){
    $row = mysqli_fetch_array($q);
    return $row;}

public function query($sql){

  $conn = $this->connect();
  $query = mysqli_query($conn, $sql);
  if (!$query) {

     /*  $_SESSION['error_massage'] = 'åäÇß ÎØÇ Ýí ÇáÇÓÊÚáÇã'; */
  } else {
      echo "";
  }
  return $query;}
public function model($sql2,$alert=null,$page=null){
   
    $conn = $this->connect();
    @$query = mysqli_query($conn, $sql2);
    if ($query and $alert != "" and $page != "") {
        echo "<script>alert('$alert')</script>";
        echo "<Script>window.open('$page','_self')</Script>";
    } else if ($alert != "" and $page != "") {
        echo "<script>alert('åäÇß ÎØÇ ')</script>";
        echo "<Script>window.open('$page','_self')</Script>";
    }}
public function insert_last_id($table_name,$assoc_array,$successmsg=null,$echo_id=null){

    $keys = array();
    $values= array();
    foreach($assoc_array as $key => $value){
        $keys[] = $key;
        $values[] =$this->escape_string(strip_tags(htmlentities($_POST[$value]))) ;
    }
    $query = "INSERT INTO `$table_name`(`".implode("`,`", $keys)."`) VALUES('".implode("','", $values)."')";
    $conn =$this->connect();
    $q=  mysqli_query($conn,$query);
    if($q){
      $last=$conn->insert_id;
      if($echo_id == true){
        $_SESSION['success_message']=$successmsg.$last;

      }else{
        $_SESSION['success_message']=$successmsg;
      }
    echo  '     <script>
    if ( window.history.replaceState ) {
      window.history.replaceState( null, null, window.location.href );
    }
    </script>';
    return $last;
  }else{
    
    $_SESSION['error_massage'] = 'هناك خطا  ';

  }} 
public function protection_input($input){
   $protect=$this->escape_string(strip_tags(htmlentities(trim(@$_POST[$input])))) ;

    return $protect;
    }
public function delete_multi($table,$idname,$check_boxs_input,$successmsg=null){
  if(isset($_POST["$check_boxs_input"])){
 
    foreach($_POST["$check_boxs_input"]  as $id){
  $q=$this->delete($table,$idname,$id,$successmsg);
    }

 }}

};
?>
