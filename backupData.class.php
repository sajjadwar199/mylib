<?php
require 'flashMessage.class.php' ;
class backupData extends flashMessage{
/*
* class create by sajjad kareem
* create in 2020/11/07 at 11:55 pm
* وظيفة هذه الكلاس هو عمل نسخ احتياطي للبيانات
*/
/*
 * وظيفة هذه الدالة هي عمل اتصال مع قاعدة البيانات وارجاع قيمة عدد الجداول في القاعدة*/
 public function getNumberTables(){
    $connect= $this->connectPdo();
    /* جلب جميع الجداول الموجودة في قاعدة البيانات */
    $get_all_table_query = "SHOW TABLES";
    $statement = $connect->prepare($get_all_table_query);
    $statement->execute();
    $result = $statement->fetchAll();
    return $result;
 }


 /* وظيفة هذه الدالة هي عمل نسخ احتياطي للبيانات مع رفع الملف
 * @param $backToFolder اسم الملف الذي تريد نقل ملف النسخ الاحتياطي له
 * ex  $obj->makeBackUp("d:/folder");
 */
public function makeBackUp($backToFolder=null){
 $connect= $this->connectPdo();

if(isset($_POST['table']))
{

   if(!isset($_POST['table'])){
      $this->setMessage('الرجاء اختيار جداول لعمل نسخ احتياطي لها',"danger");
    }
 $output = '';
 foreach($_POST["table"] as $table)
 {
  $show_table_query = "SHOW CREATE TABLE " . $table . "";
  $statement = $connect->prepare($show_table_query);
  $statement->execute();
  $show_table_result = $statement->fetchAll();
  foreach($show_table_result as $show_table_row)
  {
   $output .= "\n\n" . $show_table_row["Create Table"] . ";\n\n";
  }
  $select_query = "SELECT * FROM " . $table . "";
  $statement = $connect->prepare($select_query);
  $statement->execute();
  $total_row = $statement->rowCount();

  for($count=0; $count<$total_row; $count++)
  {
   $single_result = $statement->fetch(PDO::FETCH_ASSOC);
   $table_column_array = array_keys($single_result);
   $table_value_array = array_values($single_result);
   $output .= "\nINSERT INTO $table (";
   $output .= "" . implode(", ", $table_column_array) . ") VALUES (";
   $output .= "'" . implode("','", $table_value_array) . "');\n";
  }
 }
 $file_name = 'database_backup_on_' . date('Y-m-d').'_'.time().'.sql';
 $file_handle = fopen($backToFolder.$file_name, 'w+');
 fwrite($file_handle, $output);
 fclose($file_handle);
 header('Content-Description: File Transfer');

 header('Content-Transfer-Encoding: binary');
 header('Expires: 0');
 header('Cache-Control: must-revalidate');
    ob_clean();
   flush();
      $this->setMessage("تم عمل نسخ احتياطي بنجاح","success");

      echo  ' <script>
      if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
      }
      </script>';
   };






?>
<div class="card border-info   mb-3 container">
  <div class="card-header"><h5 align="center">نسخ احتياطي لقاعدة البيانات</h5></div>
  <div class="card-body text-info  ">
    <h5 class="card-title"><h5  align="center">اختر الجداول التي تريد عمل لها نسخ أحتياطي</h5></h5>
    <?php $this->printMessage(); ?>
    <p class="card-text">
    <form method="post" id="export_form" style="text-align:center;">
     <hr>
    <?php

    foreach($this->getNumberTables() as $table)
    {
    ?>
     <div class="form-check form-check-inline">
      <label><input type="checkbox" class="checkbox_table" name="table[]" value="<?php echo $table["Tables_in_".$this->database]; ?>" /> <?php echo $table["Tables_in_".$this->database]; ?></label>
     </div>
    <?php
    }
    ?>
    <Div id="export_form"></Div>
     <div class="form-group">
      <input type="submit" name="submit" id="submit" class="btn btn-info" value="تصدير" />
     </div>
    </form>




    </p>
  </div>



<?php

}
};
?>