<?php
//المكتبة البرمجية لphp
//تم عمل المكتبة بواسطة سجاد عبد الكريم
// جميع الحقوق محفوظة
//  date  2/10/2021
session_start();
class TheDatabase
{
  private $localhost = 'localhost';
  private $username = 'root';
  private $password = '';
  public $database = 'ajax';
  protected $con;
  protected $update_array = [];
  public function getProdectionInt($getName)
  {
    if (isset($_GET["$getName"])) {
      return  filter_var($_GET["$getName"], FILTER_VALIDATE_INT);
    }
  }
  /**
   * Undocumented function
   *
   * @return void
   */
  public function connectPdo()
  {
    $connect = new PDO("mysql:host=" . $this->localhost . ";dbname=" . $this->database, $this->username, $this->password);
    return $connect;
  }
  /**
   * connect function
   * @return void
   */
  public function connect()
  {
    $con = mysqli_connect($this->localhost, $this->username, $this->password, $this->database);
    mysqli_set_charset($con, "utf8");
    if (!$con) {
      echo "لم يتم الاتصال";
    } else {
      echo "";
    }
    return $con;
  }
  public function escape_string($value)
  {
    $conn = $this->connect();
    return mysqli_real_escape_string($conn, $value);
  }
  public function insert($table_name, $assoc_array, $successmsg = null)
  {
    $keys = array();
    $values = array();
    foreach ($assoc_array as $key => $value) {
      $keys[] = $key;
      $values[] = $this->escape_string(strip_tags(htmlentities(@$_POST[$value])));
    }
    $query = "INSERT INTO `$table_name`(`" . implode("`,`", $keys) . "`) VALUES('" . implode("','", $values) . "')";
    $conn = $this->connect();
    $q =  mysqli_query($conn, $query);
    if ($q) {
      $_SESSION['success_message'] = $successmsg;
      echo  '     <script>
      if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
       }
    </script>';
    } else {
      $_SESSION['error_massage'] = 'هناك خطا  ';
    }
  }
  /**
   * Undocumented function
   *
   * @param [type] $table
   * @param [type] $array_update
   * @param [type] $where
   * @param [type] $successmsg
   * @return void
   */
  public function update($table, $array_update, $where, $successmsg = null)
  {
    foreach ($array_update as  $key => $value) {
      $value = $this->protection_input($value);
      array_push($this->update_array, $key . '=' . "'$value'");
    }
    $value_update = implode(",", $this->update_array);
    $sql = "UPDATE $table SET $value_update   WHERE $where";
    $conn = $this->connect();
    $q =  mysqli_query($conn, $sql);
    if ($q) {
      $_SESSION['success_message'] = $successmsg;
      echo '     <script>
      if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
      }
      </script>';
    } else {
      $_SESSION['error_massage'] = 'هناك خطا  ';
    }
  }
  /**
   *  تستخدم لحذف البيانات من قاعدة البيانات
   *
   * @param [type] $table   اسم الجدول
   * @param [type] $idname    اسم المتغير الحقل في جدول قاعدة البيانات
   * @param [type] $id   رقم المعرف
   * @param [type] $successmsg   الرسالة
   * @return void
   */
  public function delete($table, $idname, $id, $successmsg = null)
  {
    $query = "DELETE FROM $table WHERE $idname = $id";
    $conn = $this->connect();
    $q = mysqli_query($conn, $query);
    if ($q) {
      $_SESSION['success_message'] = $successmsg;
      echo '     <script>
        if ( window.history.replaceState ) {
          window.history.replaceState( null, null, window.location.href );
        }
        </script>';
    } else {
      $_SESSION['error_massage'] = 'هناك خطا  ';
    }
  }
  public function show($table, $where = null)
  {
    $query = "SELECT * FROM $table   $where  ";
    $conn = $this->connect();
    $q = mysqli_query($conn, $query);
    if ($q == false) {
      return false;
    }
    $rows = array();
    while ($row = mysqli_fetch_object($q)) {
      $rows[] = $row;
    }
    return $rows;
  }
  /**
   * number query function
   *
   * @param [type] $table
   * @param [type] $where
   * @return void
   */
  public function number_query($table, $where = null)
  {
    $conn = $this->connect();
    $query = "SELECT * FROM $table  $where  ";
    $q = mysqli_query($conn, $query);
    if ($q == false) {
      return false;
    }
    $num = mysqli_num_rows($q);
    return $num;
  }
  public function fetch($q)
  {
    $row = mysqli_fetch_array($q);
    return $row;
  }
  public function query($sql)
  {
    $conn = $this->connect();
    $query = mysqli_query($conn, $sql);
    if (!$query) {
      /*  $_SESSION['error_massage'] = 'åäÇß ÎØÇ Ýí ÇáÇÓÊÚáÇã'; */
    } else {
      echo "";
    }
    return $query;
  }
  /**
   * model function
   *
   * @param [type] $sql2
   * @param [type] $alert
   * @param [type] $page
   * @return void
   */
  public function model($sql2, $alert = null, $page = null)
  {
    $conn = $this->connect();
    @$query = mysqli_query($conn, $sql2);
    if ($query and $alert != "" and $page != "") {
      echo "<script>alert('$alert')</script>";
      echo "<Script>window.open('$page','_self')</Script>";
    } else if ($alert != "" and $page != "") {
      echo "<script>alert('åäÇß ÎØÇ ')</script>";
      echo "<Script>window.open('$page','_self')</Script>";
    }
  }
  /**
   * insert_last_id function
   *
   * @param [type] $table_name
   * @param [type] $assoc_array
   * @param [type] $successmsg
   * @param [type] $echo_id
   * @return void
   */
  public function insert_last_id($table_name, $assoc_array, $successmsg = null, $echo_id = null)
  {
    $keys = array();
    $values = array();
    foreach ($assoc_array as $key => $value) {
      $keys[] = $key;
      $values[] = $this->escape_string(strip_tags(htmlentities($_POST[$value])));
    }
    $query = "INSERT INTO `$table_name`(`" . implode("`,`", $keys) . "`) VALUES('" . implode("','", $values) . "')";
    $conn = $this->connect();
    $q =  mysqli_query($conn, $query);
    if ($q) {
      $last = $conn->insert_id;
      if ($echo_id == true) {
        $_SESSION['success_message'] = $successmsg . $last;
      } else {
        $_SESSION['success_message'] = $successmsg;
      }
      echo  '     <script>
    if ( window.history.replaceState ) {
      window.history.replaceState( null, null, window.location.href );
    }
    </script>';
      return $last;
    } else {
      $_SESSION['error_massage'] = 'هناك خطا  ';
    }
  }
  /**
   * protection_input function
   *
   * @param [type] $input
   * @return void
   */
  public function protection_input($input)
  {
    $protect = $this->escape_string(strip_tags(htmlentities(trim(@$_POST[$input]))));
    return $protect;
  }
  /**
   * delete_multi function
   *
   * @param [type] $table
   * @param [type] $idname
   * @param [type] $check_boxs_input
   * @param [type] $successmsg
   * @return void
   */
  public function delete_multi($table, $idname, $check_boxs_input, $successmsg = null)
  {
    if (isset($_POST["$check_boxs_input"])) {
      foreach ($_POST["$check_boxs_input"]  as $id) {
        $q = $this->delete($table, $idname, $id, $successmsg);
      }
    }
  }
};
class fileUpload extends TheDataBase
{
  /*
    *  class by sajjad kareem
    * create in 2020/11/1 9: 57pm
    */
  /* هذا الميزة تفيد في تشغيل طباعة رسالة الصح   */
  private $SuccessMessage = true;
  /* اسم الملف للرفع */
  private $fileName;
  /* الحجم المسموح به */
  private $sizeAllow;
  /* نوعية الملف المسموح به للرفع */
  private $typeFileAllow = [];
  /* اسم الملف الذي نريد ان نرفع له  */
  private $uploadTo;
  /* الاخطاء الخاصة بفحص الملف */
  private $e = array();
  /* صيغ الملفات القياسية المسموح لرفعها */
  private const standrTypefile = array('jpeg', 'jpg', 'png', 'gif', 'jfif', 'pdf', 'txt', 'zip');
  // بداية الرسالة الخطأ بوتستراب
  private $startMessageBootstrab = "<h6  align='right' style='color:red'>";
  // نهاية الرسالة الخطأ بوتستراب
  private $endMessageBootstrab = "</h6>";
  // بداية الرسالة الصح بوتستراب
  private $startMessageBootstrabSuccess = "<h6  align='right' style='color:green'>";
  // نهاية الرسالة  الصح بوتستراب
  private $endMessageBootstrabSuccess = "</h6>";
  //الحجم القياسي لرفع الملفات
  private const standrSizeFile = 20000000000;
  //رسالة الخطأ الخاصة بنوع الملف
  private const messageTypeFile = "الملف الذي رفعته لا يطابق الصيغة المطلوبة";
  //رسالة الخطأ الخاصة بحجم الملف
  private const messageSizeFile = "الملف الذي رفعته اكبر من الحجم المطلوب";
  //رابط الملف بعد الرفع
  public $filePathAfterUpload;
  //الرسالة الخاصة عند نجاح عملية الرفع
  private $messageSuccess;
  /*
  * الاستدعاء الرئيسي لدالة رفع الملفات
  */
  /**
   * Undocumented function
   *
   * @param [type] $fileName
   * @param [type] $uploadTo
   * @param [type] $messageSuccess
   * @param [type] $typeFileAllow
   * @param [type] $sizeAllow
   * @return void
   */
  public function uploadFile($fileName, $uploadTo, $messageSuccess = null, $typeFileAllow = null, $sizeAllow = null)
  {
    $this->messageSuccess = $messageSuccess;
    $this->fileName       = $fileName;
    $this->sizeAllow      = $sizeAllow;
    $this->typeFileAllow  = $typeFileAllow;
    $this->uploadTo       = $uploadTo;
    $this->setErrors();
    $this->checkUpload();
    return $this->filePathAfterUpload;
  }
  /*
* جلب نوعية الملف
*/
  private function getFileType()
  {
    if (isset($_FILES[$this->fileName]['name'])) {
      $fileName = $_FILES[$this->fileName]['name'];
    }
    /* صيغة الملف */
    if (isset($_FILES[$this->fileName]['name'])) {
      $file_extension = pathinfo($fileName, PATHINFO_EXTENSION);
      return $file_extension;
    }
  }
  /*
      * وظيفة هذه الدالة هي فحص الملف اذا كان بلصيغة المطلوبة
      *
      */
  /**
   * Undocumented function
   *
   * @return void
   */
  private  function checkAllowType()
  {
    if (!empty($this->typeFileAllow)) {
      $typeCheck = in_array($this->getFileType(), $this->typeFileAllow);
    } else if (empty($this->typeFileAllow)) {
      $typeCheck = in_array($this->getFileType(), self::standrTypefile);
    }
    if ($typeCheck == false) {
      return false;
    } else {
      return true;
    }
  }
  /*
* وظيفة هذه الدالة هي فحص حجم الملف
*/
  /**
   * Undocumented function
   *
   * @return void
   */
  private function checkAllowSize()
  {
    if (!empty($this->sizeAllow)) {
      if ($_FILES[$this->fileName]['size'] > $this->sizeAllow) {
        return false;
      } else {
        return true;
      }
    } else if (empty($this->sizeAllow)) {
      if ($_FILES[$this->fileName]['size'] > self::standrSizeFile) {
        return false;
      } else {
        return true;
      }
    }
  }
  /*
  * وظيفة هذه الدالة هي وضع الاخطاء الناتجة عن فحص الملف
  */
  /**
   * Undocumented function
   *
   * @return void
   */
  private function setErrors()
  {
    /* فحص نوع الملف الذي سوف يرفع */
    if ($this->checkAllowType() == false) {
      $this->e[] = $this->startMessageBootstrab . self::messageTypeFile . $this->endMessageBootstrab;
    }
    /* فحص حجم الملف الذي سوف يرفع */
    if ($this->checkAllowSize() == false) {
      $this->e[] = $this->startMessageBootstrab . self::messageSizeFile . $this->endMessageBootstrab;
    }
  }
  /*
* وظيفة هذه الدالة عمل امتداد للملف وارجاعه
*/

  private function getFilePath()
  {
    if ($this->checkAllowType() != false && $this->checkAllowSize() != false) {
      $rand                      = substr(md5(uniqid(rand(), true)), 3, 10);
      $path                      = $this->uploadTo . '/' . $rand . '_' . time() . '.' . $this->getFileType();
      $this->filePathAfterUpload = $path;
      return $path;
    } else {
      return false;
    }
  }
  /*
* وظيفة هذه الدالة هي طباعة الاخطاء الناتجة عن فحص الملف
*/
  public function checkUpload()
  {
    if (isset($_FILES[$this->fileName]['error'])) {
      if ($_FILES[$this->fileName]['error'] > 0) {
        if ($_FILES[$this->fileName]["name"] == "") {
          echo  $this->startMessageBootstrab . "الرجاء اختيار ملف " . $this->endMessageBootstrab;
        } else {
          echo  $this->startMessageBootstrab . "هناك أخطاء مجهولة عددها:" . $_FILES[$this->fileName]['error'] . $this->endMessageBootstrab;
        }
        return false;
      }
    }
    if (!empty($this->e)) {
      foreach ($this->e as $errors) {
        echo  $errors;
      }
      return false;
    }
    //رفع الملف اذا لم تكن هناك اخطاء
    if ($this->checkAllowType() != false && $this->checkAllowSize() != false) {
      // بدء عملية الرفع
      $this->filePathAfterUpload = $this->getFilePath();
      $okUpload                  = move_uploaded_file($_FILES[$this->fileName]['tmp_name'], $this->filePathAfterUpload);
      if ($okUpload && $this->SuccessMessage == true) {
        echo  $this->startMessageBootstrabSuccess . $this->messageSuccess . $this->endMessageBootstrabSuccess;
      }
      echo  ' <script>
      if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
      }
      </script>';
      return true;
    }
  }

  /**
   * دالة لتحديث الملفات
   *
   * @param [type] $table اسم الجدول
   * @param [type] $where  الشرط
   * @param [type] $oldUrl اسم حقل الرابط في قاعدة البيانات
   * @param [type] $newUrl اسم الملف المرسل ex ..  <input name="file" type="file" />    name = file  الاسم
   * @param [type] $uploadTo اسم المجلد الذي تريد الرفع اليه
   * @param [type] $SuccessMessage
   * @return void
   */
  public function updateFile($table, $where, $oldUrl, $newUrl, $uploadTo, $SuccessMessage = null)
  {
    //عمل whereللشرط الثاني للتحديث في قاعدة البيانات
    $pos = strpos($where, "where");
    /*custom name */
    $where2 = substr($where, $pos + 5);

    // جلب الملف القديم
    foreach ($this->show($table, $where) as $old) {
      $oldPath = $old->$oldUrl;
    }
    //رفع الملف الجديد بدل القديم
    $newPath = $this->uploadFile($newUrl, $uploadTo, $SuccessMessage);

    $_POST['new'] = $newPath;

    $this->update($table, [$oldUrl => 'new'], $where2);
    //حذف القديم بعد الرفع
    if ($newPath != false) {
      if ($oldPath != null and file_exists($oldPath)) {
        $this->deleteFiles($oldPath);
        // عمل تحديث للرابط

      }
      return $newPath;
    } else {
      echo  '<div class="alert alert-warning">لم يتم تحديث الملف </div>';
      $_POST['new'] = $oldPath;
      $this->update($table, [$oldUrl => 'new'], $where2);
      return $oldPath;
    }
  }

  /*
* وظيفة هذه الدالة هي لحذف الملفات
*/
  public function deleteFiles($urlFile, $message = null)
  {
    if (unlink($urlFile) and $message != null) {
      echo  $this->startMessageBootstrabSuccess . $message . $this->startMessageBootstrabSuccess;
    };
  }
};
class paginate extends  fileUpload
{
    //class by sajjad kareem
    /* الجدول  */
    private $table;
    /* الصفحة */
    private $page;
    /* where */
    private $where;
    /* عدد العناصر في الصفحة */
    private $limit = 5;
    /* عدد العناصر التي ستعرض في الصفحة */
    private $number_show;
    /* تحديد عددالروابط في الوسط */
    private $links_between;
    /* متغيرات الروابط في الرابط    ex ?page=2&&id=10 */
    private $url_vars;
    private function  page_get()
    {
        if (isset($_GET['page'])) {
            $page = (int)$_GET['page'];
            if ($page == 0 || $page < 1) {
                $this->number_show = 0;
            } else {
                $this->number_show = ($page * $this->limit) - $this->limit;
            }
        } else {
            $this->number_show = 0;
        }
    }
    public  function  paginate_option($table, $page, $limit = null, $where = null, $links_between = null, $url_vars = null)
    {
        $this->url_vars      = $url_vars;
        $this->links_between = $links_between;
        $this->table         = $table;
        $this->page          = $page;
        $this->where         = $where;
        if (isset($limit)) {
            $this->limit = $limit;
        }
        /* فحص الصفحات في الرابط */
        $this->page_get();
    }
    public  function  prev()
    {
        if (isset($_GET['page'])) {
            $page = (int)$_GET['page'];
        }
        if (!isset($page) || $page == '' || $page < 1) {
            $page = 1;
        }

        if ($page > 1) {
            $mins = $page - 1;

            $prev_btn = " <li class='page-item'>" . "<a class='page-link' href=$this->page?page=$mins$this->url_vars   >&laquo;</a>" . "</li>";
            return $prev_btn;
        }
    }
    private function  next($rowsperpage)
    {
        if (isset($_GET['page']) and $_GET['page'] != '') {
            $page = (int)$_GET['page'];
        }
        if (!isset($page) || $page == '' || $page < 1 || $page > $rowsperpage) {
            $page = 1;
        }
        if ($page + 1 <= $rowsperpage) {
            $pos      = $page + 1;
            $next_btn = " <li class='page-item'>" . "<a class='page-link' href=$this->page?page=$pos$this->url_vars  >&raquo;</a>" . "</li>";
            return $next_btn;
        }
    }
    private function  links($current_page, $total_pages, $url)
    {
        if (isset($_GET['page'])) {
            $page = (int)$_GET['page'];
        }
        // your current page
        // $pages=20; // Total number of pages
        if (!isset($page) || $page == '' || $page < 1 || $page > $current_page) {
            $page = 1;
        }

        if ($this->links_between != '') {
            $limit = $this->links_between;
        } else {
            $limit = 5;  /* عدد الصفحات التي تضهر في وسط تعداد الصفحات */
        }

        $links = "";
        if ($total_pages >= 1 && $current_page <= $total_pages) {
            if ($page == 1) {
                $links .= "

                <li class = 'page-item active'>
                <a  class = 'page-link' href = \"{$url}?page=1$this->url_vars \">1</a>

                </li>";
            } else {
                $links .= "

                <li class = 'page-item'>
                <a  class = 'page-link' href = \"{$url}?page=1$this->url_vars \">1</a>

                </li>";
            }

            $i = max(2, $current_page - $limit);
            if ($i > 2)
                $links .= "              <li  class='page-item'>
                <a class = 'page-link'>...</a>
                </li> ";
            for (; $i < min($current_page + $limit + 1, $total_pages); $i++) {
                if ($i == @$page) {
                    $links .= "


                    <li class = 'page-item active'>
                    <a  class = 'page-link' href = \"{$url}?page={$i}$this->url_vars \">{$i}</a>
                    </li>
    ";
                } else {
                    $links .= "
                    <li class = 'page-item'>
                    <a  class = 'page-link' href = \"{$url}?page={$i}$this->url_vars \">{$i}</a>
                    </li>

                    ";
                }
            }
            if ($i != $total_pages)
                $links .= "     <li  class='page-item'>    <a class='page-link'>...</a></li> ";
            if ($page == $total_pages) {

                $links .= "
                    <li class = 'page-item active'>
                    <a  class = 'page-link' href = \"{$url}?page={$total_pages}$this->url_vars \">{$total_pages}</a>

                    </li>

                    ";
            } else {
                $links .= "
                    <li class = 'page-item'>
                    <a  class = 'page-link' href = \"{$url}?page={$total_pages}$this->url_vars \">{$total_pages}</a>

                    </li>

                    ";
            }
        }
        return $links;
    }



    public  function  paginate_links()
    {
        if (isset($_GET['page'])) {
            $page = (int)$_GET['page'];
        }

        $conn = $this->connect();
        /* عدد العناصر في الجدول */
        $count_items = "SELECT COUNT(*) from $this->table $this->where ";
        /* استعلام عن عدد العناصر في الجدول */
        $excecute_pagination = mysqli_query($conn, $count_items);
        $row_pagination      = mysqli_fetch_array($excecute_pagination);
        /* جميع الصفوف */
        $total_rows = array_shift($row_pagination);
        /*  عدد الروابط في الصفحة في الصفحة    */
        $rowsperpage = $total_rows / $this->limit;
        $rowsperpage = ceil($rowsperpage);
        if (!isset($page) || $page == '' || $page < 1 || $page > $rowsperpage) {
            $page = 1;
        }



?>
        <nav aria-label = "Page navigation example">

            <ul class = "pagination">
                <?php if ($page >= 1) {
                ?>
                    <!--prec btn --> <?php echo  $this->prev(); ?>


                    <?php   /*  echo $this->links($rowsperpage);  */
                    echo $this->links($page, $rowsperpage, $this->page);


                    ?>

                    <!--next btn --><?php echo $this->next($rowsperpage);
                                }  ?>
        </nav>
<?php
    }
    public  function  show_with_pagination()
    {

        $conn  = $this->connect();
        $query = "SELECT * FROM $this->table   $this->where   LIMIT $this->number_show,$this->limit";

        $q = mysqli_query($conn, $query);
        if ($q == false) {
            return false;
        }
        $rows = array();
        while ($row = mysqli_fetch_object($q)) {
            $rows[] = $row;
        }

        return $rows;
    }
};
class validator extends paginate
{

  /* find_errors prop */
  private $errors = [];
  /* find_errors prop */
  /* validation prop */
  private $input_names = array();
  /* validation prop */
  /* check all validation  */
  private $check = array();
  /* check all validation  */
  /* masgs */
  public $masgs = array();
  /* masgs */
  /* flag all validation */
  //  public $ok;
  /* custom name */
  private $custom_name = array();
  /* custom name */
  private $custom_name2 = array();
  /* flag all validation */
  public  function test_validate()
  {

    return $this->find_errors($this->check);
  }
  public  function validation($valid_array, $msg_array = null)
  {
    foreach ($valid_array as $key => $value) {
      array_push($this->input_names, $this->escape_string(strip_tags(htmlentities(trim($_POST[$key])))));
      foreach ($value as $option) {

        /* start validation part  */

        switch ($option) {
          case substr($option, 0, 3) == "max":
            $this->max(substr($option, 4), $key);
            break;
          case substr($option, 0, 3) == "min":
            $this->min(substr($option, 4), $key);
            break;
          case substr($option, 0, 4) == "name":
            $this->custom_name[] = $key . ':' . substr($option, 5);
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
        };
      }
    }
    /*get the name inputs */
    // foreach($this->input_names as $inputs){
    //    echo     $inputs .'<br>';
    // }
    /*get the name inputs */
  }
  private function date($input_name = null, $custom_name_arabic = null)
  {
    foreach ($this->custom_name as $value) {
      $pos = strpos($value, ":");
      /*custom name */
      $name = substr($value, 0, $pos);
      /* origanl name */
      $custom_name = substr($value, $pos + 1);

      if ($name == $input_name) {
        $custom_name_arabic = $custom_name;
      }
    }
    if (in_array($this->escape_string(strip_tags(htmlentities(trim(@$_POST[$input_name])))), $this->input_names)) {
      $input_value = $this->escape_string(strip_tags(htmlentities(trim($_POST[$input_name]))));
    }
    if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $input_value)) {
      if (isset($custom_name_arabic)) {
        array_push($this->masgs, ' ' . 'الرجاء  كتابة تاريخ  صحيح في حقل  ' . $custom_name_arabic);
      } else {
        array_push($this->masgs, ' ' . 'الرجاء  كتابة تاريخ  صحيح في حقل  ' . $input_name);
      }
      array_push($this->check, "false");
    } else {
      array_push($this->check, "true");
    }
  }
  private function max($max_number, $input_name = null, $custom_name_arabic = null)
  {
    foreach ($this->custom_name as $value) {
      $pos = strpos($value, ":");
      /*custom name */
      $name = substr($value, 0, $pos);
      /* origanl name */
      $custom_name = substr($value, $pos + 1);

      if ($name == $input_name) {
        $custom_name_arabic = $custom_name;
      }
    }
    if (in_array($this->escape_string(strip_tags(htmlentities(trim(@$_POST[$input_name])))), $this->input_names)) {
      $input_value = $this->escape_string(strip_tags(htmlentities(trim(@$_POST[$input_name]))));
    }
    // echo strlen($input_value) .'<Br>';
    if (strlen("$input_value") > $max_number) {
      if (isset($custom_name_arabic)) {
        array_push($this->masgs, ' ' . '  ' . '  ' . '  يجب ان تكون عدد الكلمات او الارقام في حقل' . ' ' . $custom_name_arabic . ' لا تتعدى' . ' ' . $max_number);
      } else {
        array_push($this->masgs, $max_number . ' ' . ' لا تتعدى' . ' ' . $input_name . ' ' . '  يجب ان تكون عدد الكلمات او الارقام في حقل ');
      }
      array_push($this->check, "false");
    } else {
      array_push($this->check, "true");
    };
  }
  private function min($min_number, $input_name = null, $custom_name_arabic = null)
  {
    foreach ($this->custom_name as $value) {
      $pos = strpos($value, ":");
      /*custom name */
      $name = substr($value, 0, $pos);
      /* origanl name */
      $custom_name = substr($value, $pos + 1);

      if ($name == $input_name) {
        $custom_name_arabic = $custom_name;
      }
    }
    if (in_array($this->escape_string(strip_tags(htmlentities(trim($_POST[$input_name])))), $this->input_names)) {
      $input_value = $this->escape_string(strip_tags(htmlentities(trim($_POST[$input_name]))));
    }
    // echo strlen($input_value) .'<Br>';
    if (strlen("$input_value") < $min_number) {
      if (isset($custom_name_arabic)) {
        array_push($this->masgs, $min_number . ' ' . '  ' . '  ' . '  يجب ان تكون عدد الكلمات او الارقام في حقل' . ' ' . $custom_name_arabic . ' على الاقل ');
      } else {
        array_push($this->masgs, ' ' . ' على الاقل' . ' ' . $input_name . ' ' . ' يجب انت تكون عدد الكلمات او الارقام في حقل   ' . $min_number);
      }
      array_push($this->check, "false");
    } else {
      array_push($this->check, "true");
    };
  }
  private function number($input_name = null, $custom_name_arabic = null)
  {
    foreach ($this->custom_name as $value) {
      $pos = strpos($value, ":");
      /*custom name */
      $name = substr($value, 0, $pos);
      /* origanl name */
      $custom_name = substr($value, $pos + 1);

      if ($name == $input_name) {
        $custom_name_arabic = $custom_name;
      }
    }

    if (in_array($this->escape_string(strip_tags(htmlentities(trim(@$_POST[$input_name])))), $this->input_names)) {
      $input_value = $this->escape_string(strip_tags(htmlentities(trim(@$_POST[$input_name]))));
    }
    if (!preg_match("/^[0-9]+$/", $input_value)) {
      if (isset($custom_name_arabic)) {
        array_push($this->masgs, ' ' . ' الرجاء ادخال ارقام فقط في حقل   ' . $custom_name_arabic);
      } else {
        array_push($this->masgs, $input_name . ' ' . ' الرجاء ادخال ارقام فقط في حقل   ');
      };
      array_push($this->check, "false");
    } else {
      array_push($this->check, "true");
    }
  }
  private function require($input_name = null, $custom_name_arabic = null)
  {
    foreach ($this->custom_name as $value) {
      $pos = strpos($value, ":");
      /*custom name */
      $name = substr($value, 0, $pos);
      /* origanl name */
      $custom_name = substr($value, $pos + 1);

      if ($name == $input_name) {
        $custom_name_arabic = $custom_name;
      }
    }

    if (in_array($this->escape_string(strip_tags(htmlentities(trim(@$_POST[$input_name])))), $this->input_names)) {
      $input_value = $this->escape_string(strip_tags(htmlentities(trim(@$_POST[$input_name]))));
    }
    if (empty($input_value)) {
      if (isset($custom_name_arabic)) {
        array_push($this->masgs, 'لا تترك حقل ' . $custom_name_arabic . ' ' . 'فارغ');
      } else {
        array_push($this->masgs, '  فارغ' . ' ' . $input_name . ' ' . '  لا تترك حقل   ');
      }
      array_push($this->check, "false");
    } else {
      array_push($this->check, "true");
    }
  }
  private function url($input_name = null, $custom_name_arabic = null)
  {
    foreach ($this->custom_name as $value) {
      $pos = strpos($value, ":");
      /*custom name */
      $name = substr($value, 0, $pos);
      /* origanl name */
      $custom_name = substr($value, $pos + 1);

      if ($name == $input_name) {
        $custom_name_arabic = $custom_name;
      }
    }
    if (in_array($this->escape_string(strip_tags(htmlentities(trim(@$_POST[$input_name])))), $this->input_names)) {
      $input_value = $this->escape_string(strip_tags(htmlentities(trim($_POST[$input_name]))));
    }
    if (!filter_var($input_value, FILTER_VALIDATE_URL)) {
      if (isset($custom_name_arabic)) {
        array_push($this->masgs, ' ' . 'الرجاء  كتابة رابط  صحيح في حقل  ' . $custom_name_arabic);
      } else {
        array_push($this->masgs, $input_name . ' ' . 'الرجاء  كتابة رابط  صحيح في حقل  ');
      }
      array_push($this->check, "false");
    } else {
      array_push($this->check, "true");
    }
  }
  private function eng($input_name = null, $custom_name_arabic = null)
  {
    foreach ($this->custom_name as $value) {
      $pos = strpos($value, ":");
      /*custom name */
      $name = substr($value, 0, $pos);
      /* origanl name */
      $custom_name = substr($value, $pos + 1);

      if ($name == $input_name) {
        $custom_name_arabic = $custom_name;
      }
    }
    if (in_array($this->escape_string(strip_tags(htmlentities(trim(@$_POST[$input_name])))), $this->input_names)) {
      $input_value = $this->escape_string(strip_tags(htmlentities(trim($_POST[$input_name]))));
    }
    $regex = '/^[a-zA-Z0-9$@$!%*?&#^-_. +]+$/';
    if (!preg_match($regex, $input_value)) {
      if (isset($custom_name_arabic)) {
        array_push($this->masgs, 'الرجاء كتابة اللغة الانكليزية فقط في حقل' . ' ' . $custom_name_arabic);
      } else {
        array_push($this->masgs, $input_name . ' ' . 'الرجاء كتابة اللغة الانكليزية فقط في حقل');
      }
      array_push($this->check, "false");
    } else {
      array_push($this->check, "true");
    }
  }
  private function email($input_name = null, $custom_name_arabic = null)
  {
    foreach ($this->custom_name as $value) {
      $pos = strpos($value, ":");
      /*custom name */
      $name = substr($value, 0, $pos);
      /* origanl name */
      $custom_name = substr($value, $pos + 1);

      if ($name == $input_name) {
        $custom_name_arabic = $custom_name;
      }
    }
    if (in_array($this->escape_string(strip_tags(htmlentities(trim(@$_POST[$input_name])))), $this->input_names)) {
      $input_value = $this->escape_string(strip_tags(htmlentities(trim($_POST[$input_name]))));
    }
    if (!@filter_var($this->protection_input($input_value), FILTER_VALIDATE_EMAIL)) {
      if (isset($custom_name_arabic)) {
        array_push($this->masgs, 'الرجاء كتابة بريد الكتروني  صحيح في حقل ' . ' ' . $custom_name_arabic);
      } else {
        array_push($this->masgs, $input_name . ' ' . 'الرجاء كتابة بريد الكتروني  صحيح في حقل ');
      }
      array_push($this->check, "false");
    } else {
      array_push($this->check, "true");
    }
  }
  public  function errors_validate()
  {
    foreach ($this->masgs as $errors) {
      echo  '  <div class="alert alert-dismissible alert-warning text-right direction-rtl" role="alert">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>' . $errors . '</strong>.
            </div>';
    }
  }

  public  function find_errors($errors_array)
  {
    $error =  $this->errors[] = $errors_array;

    $flag_false = array();
    $flag_true = array();
    $errors_array = array();

    for ($i = 0; $i < count($error); $i++) {
      if ($error[$i]  == "false") {
        array_push($flag_false, "false");
      } else {
        array_push($flag_true, "true");
      }
    }
    if ($flag_false != null) {
      return false;
    } else if ($flag_true != null) {
      return true;
    }

    //   return $this->chack_bool;
  }
};
class flashMessage extends validator
{
     /**
     * class create by sajjad kareem
     * create in 2020/11/3 8:04pm
     */
     //نوع الرسالة
     private $messageType;
     //الرسالة
     private $message;
     //انواع الرسائل القياسية

     private const standrMessageType = array("success", "warning", "primary", "danger", "info", "light", 'dark');
     /* استدعاء الدالة الرئيسي */
     /**
      * setMessage function
      *
      * @param [string] $message
      * @param [string] $messageType
      * @return void
      */
     public function setMessage($message, $messageType)
     {
          $this->message = $message;
          $this->messageType = $messageType;
          $this->messageOption();
     }
     /* هذا الدالة مسؤؤلة عن اختيار نوع الرسالة */
     public function messageOption()
     {
          switch ($this->messageType) {
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
               default:
                    $this->success();
          };
     }
     /*  انواع الرسائل */
     /**
      * Undocumented function
      *
      * @return void
      */
     private function success()
     {
          $_SESSION['success_message'] = '<div class="alert alert-success"  style="text-align:right;" role="alert">' . $this->message . '</div>';
     }

     private function warning()
     {
          $_SESSION['warning_message'] = '<div class="alert alert-warning"  style="text-align:right;"  role="alert">' . $this->message . '</div>';
     }
     private function primary()
     {
          $_SESSION['primary_message'] = '<div class="alert alert-primary"  style="text-align:right;" role="alert">' . $this->message . '</div>';
     }
     private function danger()
     {
          $_SESSION['error_message'] = '<div class="alert alert-danger"  style="text-align:right;" role="alert">' . $this->message . '</div>';
     }

     private function info()
     {
          $_SESSION['info_message'] = '<div class="alert alert-info"  style="text-align:right;" role="alert">' . $this->message . '</div>';
     }
     private function light()
     {
          $_SESSION['light_message'] = '<div class="alert alert-light"  style="text-align:right;" role="alert">' . $this->message . '</div>';
     }
     private function dark()
     {
          $_SESSION['dark_message'] = '<div class="alert alert-dark"  style="text-align:right;" role="alert">' . $this->message . '</div>';
     }
     /*  انواع الرسائل */
     /* هذه الدالة خاصة بطبع الرسالة */
     public function printMessage()
     {
          if (isset($_SESSION['success_message'])) {
               echo $_SESSION['success_message'];
               $_SESSION['success_message'] = '';
          }
          if (isset($_SESSION['warning_message'])) {
               echo   $_SESSION['warning_message'];
               $_SESSION['warning_message'] = '';
          }
          if (isset($_SESSION['primary_message'])) {
               echo $_SESSION['primary_message'];
               $_SESSION['primary_message'] = '';
          }
          if (isset($_SESSION['error_message'])) {
               echo $_SESSION['error_message'];
               $_SESSION['error_message'] = '';
          }
          if (isset($_SESSION['info_message'])) {
               echo $_SESSION['info_message'];
               $_SESSION['info_message'] = '';
          }
          if (isset($_SESSION['light_message'])) {
               echo $_SESSION['light_message'];
               $_SESSION['light_message'] = '';
          }
          if (isset($_SESSION['dark_message'])) {
               echo $_SESSION['dark_message'];
               $_SESSION['dark_message'] = '';
          }
     }
};
class backupData extends flashMessage
{
   /*
* class create by sajjad kareem
* create in 2020/11/07 at 11:55 pm
* وظيفة هذه الكلاس هو عمل نسخ احتياطي للبيانات
*/
   /*
 * وظيفة هذه الدالة هي عمل اتصال مع قاعدة البيانات وارجاع قيمة عدد الجداول في القاعدة*/
   public function getNumberTables()
   {
      $connect = $this->connectPdo();
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
   public function makeBackUp($backToFolder = null)
   {
      $connect = $this->connectPdo();

      if (isset($_POST['table'])) {

         if (!isset($_POST['table'])) {
            $this->setMessage('الرجاء اختيار جداول لعمل نسخ احتياطي لها', "danger");
         }
         $output = '';
         foreach ($_POST["table"] as $table) {
            $show_table_query = "SHOW CREATE TABLE " . $table . "";
            $statement = $connect->prepare($show_table_query);
            $statement->execute();
            $show_table_result = $statement->fetchAll();
            foreach ($show_table_result as $show_table_row) {
               $output .= "\n\n" . $show_table_row["Create Table"] . ";\n\n";
            }
            $select_query = "SELECT * FROM " . $table . "";
            $statement = $connect->prepare($select_query);
            $statement->execute();
            $total_row = $statement->rowCount();

            for ($count = 0; $count < $total_row; $count++) {
               $single_result = $statement->fetch(PDO::FETCH_ASSOC);
               $table_column_array = array_keys($single_result);
               $table_value_array = array_values($single_result);
               $output .= "\nINSERT INTO $table (";
               $output .= "" . implode(", ", $table_column_array) . ") VALUES (";
               $output .= "'" . implode("','", $table_value_array) . "');\n";
            }
         }
         $file_name = 'database_backup_on_' . date('Y-m-d') . '_' . time() . '.sql';
         $file_handle = fopen($backToFolder . $file_name, 'w+');
         fwrite($file_handle, $output);
         fclose($file_handle);
         header('Content-Description: File Transfer');

         header('Content-Transfer-Encoding: binary');
         header('Expires: 0');
         header('Cache-Control: must-revalidate');
         ob_clean();
         flush();
         $this->setMessage("تم عمل نسخ احتياطي بنجاح", "success");

         echo  ' <script>
         if ( window.history.replaceState ) {
         window.history.replaceState( null, null, window.location.href );
         }
         </script>';
      };






?>
      <div class="card border-success   mb-3 container">
         <div class="card-header">
            <h5 align="center">نسخ احتياطي لقاعدة البيانات</h5>
         </div>
         <div class="card-body-success text-success  ">
            <h5 class="card-title">
               <h5 align="center">اختر الجداول التي تريد عمل لها نسخ أحتياطي</h5>
            </h5>
            <?php $this->printMessage(); ?>
            <p class="card-text">
               <form method="post" id="export_form" style="text-align:center;">
                  <hr>
                  <?php

                  foreach ($this->getNumberTables() as $table) {
                  ?>
                     <div class="form-check form-check-inline">
                        <label><input type="checkbox" class="checkbox_table" name="table[]" value="<?php echo $table["Tables_in_" . $this->database]; ?>" /> <?php echo $table["Tables_in_" . $this->database]; ?></label>
                     </div>
                  <?php
                  }
                  ?>
                  <Div id="export_form"></Div>
                  <div class="form-group">
                     <input type="submit" name="submit" id="submit" class="btn btn-success" value="تصدير" />
                  </div>
               </form>




            </p>
         </div>



   <?php
   }
};

class masterpage extends backupData
{
  /* عنوان الصفحة */
  private $titlePage;
  /* مكان ملفات القالب */
  private $templateFileUrl;
  /* مكان عنوان الجزء الاول من الماستر بيج */
  private $startSectionName;
  /* عنوان مكان الجزء الثاني من الماستر بيج */
  private $endSectionName;
  /* الدالة الرئيسية للاستدعاء */
  //urlDir  امتداد روابط الصفحات      ex ../views/home
  private $urlDir;
  public function master($titlePage, $startSectionName, $endSectionName, $templateFileUrl, $urlDir = null)
  {
    $this->templateFileUrl = $templateFileUrl;
    $this->titlePage = $titlePage;
    $this->startSectionName = $startSectionName;
    $this->endSectionName = $endSectionName;
  }
  /* دالة تخصص عنوان الصفحة */
  public  function setTitle()
  {
    return define('title', $this->titlePage);
  }
  /* دالة تخصص امتداد الروابط مثل ../../ */
  public function setUrlDir()
  {
    return define('Dir', $this->templateFileUrl);
  }
  /* دالة تخصص الجزء الاول للقالب  */
  public function startSection()
  {
    $this->setTitle();
    $this->setUrlDir();
    define('urlDir', $this->urlDir);
    return   include    $this->startSectionName . '.php';
  }
  /* دالة تخصص الجزء الثاني للقالب  */
  public function endSection()
  {

    return include  $this->endSectionName . '.php';
  }
};

class ajaxx extends masterpage
{
    /**
     * وظيفة هذا الكلاس للتعامل مع جميع عمليات الاجاكس
     * create by sajjad kareem  at  2020/12/8
     */
    /**
     * Undocumented variable
     *
     * @var [type]
     */
    /**
     * اسم الصفحة التي نجلب منها البيانات
     *
     * @var [string]
     */
    private $AjaxPageName;
    /**
     * وظيفة هذا المتغير هو لجمع البيانات  لأرسالها
     *
     * @var array
     */
    private $dataRequest = [];

    /**
     * وظيفة هذا المغير هو لتحديد المكان الذي تعرض فيه البيانات
     *
     * @var [type]
     */
    private $divShowId;
    /**
     * json وظيفة هذا المتغير هو لتحديد نوع البيانات التي نستقبلها مثلا اذا كانت
     *
     * @var [type]
     */
    private $dataType = 'text';
    /**
     *  post ,get وضيفة المتغير هو لتحديد نوع الطلب اذا كان
     *
     * @var [type]
     */
    private $typeRequest = 'post';
    /**
     *  دالة الأستدعاء الرئيسية
     * @param $event ex .. ['btn'=>'click']
     * @param $spinnerId ex ..spinner علامة جاري التحميل
     * @param [string] $pageName   اسم  صفحة الاجاكس
     * @param [string] $divShowId   معرف مكان عرض البيانات
     * @param [string] $data   ex .. ["name","age"]   البيانات
     * @param [string] $method   ex .. post or get
     * @param [string] $dataType   ex .. json or text
     * @return void
     */
    /**
     * وظيفة المتغير  هو ليضم اسم معرف التحميل قبل الاستجابة
     *
     * @var [string]
     */
    private $spinnerId;
    public function ajax($AjaxPageName, $divShowId, $event, $loaderId = null, $data = null, $method = null, $dataType = null)
    {
        $this->AjaxPageName = $AjaxPageName;
        $this->divShowId = $divShowId;
        $this->spinnerId = $loaderId;
        if ($method != null) {
            $this->typeRequest = $method;
        }
        if ($dataType != null) {
            $this->dataType = $dataType;
        }
        if ($data != null) {
            $this->dataRequest = $data;
            foreach ($event as $key => $value) {
                $this->send($key, $value);
            }
        } else if ($data == null) {
            foreach ($event as $key => $value) {
                $this->load($key, $value);
            }
        }
    }
    /**
     * وظيفة هذه الدالة تقوم بارجاع النتيجة للصفحة
     *
     * @return void
     */
    private function load($eventId, $event)
    {
        if ($this->spinnerId != null) {


?>
            <script>
                $(document).ready(function() {
                    $('#<?php echo $eventId; ?>').<?php echo $event; ?>(function() {
                        <?php if ($event == 'click') { ?>
                            $('#<?php echo $eventId; ?>').attr('disabled', 'disabled');
                            <?php  }; ?>$('#<?php echo $this->spinnerId; ?>').show();
                            $.ajax({
                                url: "<?php echo  $this->AjaxPageName; ?>",
                                method: "<?php echo $this->typeRequest; ?>",
                                type: '<?php echo  $this->dataType;  ?>',
                                success: function(data) {
                                    $('#<?php echo $this->spinnerId; ?>').append(data.data);
                                    $('#<?php echo  $this->divShowId; ?>').append().html(data);
                                    $('#<?php echo $eventId; ?>').removeAttr('disabled');

                                }
                            }).done(function() {
                                $('#<?php echo $this->spinnerId; ?>').hide();
                            });
                    })
                });
            </script>
        <?php
            return true;
        } else {
        ?>
            <script>
                $(document).ready(function() {
                    $('#<?php echo $eventId; ?>').<?php echo $event; ?>(function() {
                        <?php if ($event == 'click') { ?>
                            $('#<?php echo $eventId; ?>').attr('disabled', 'disabled');
                        <?php  }; ?>
                        $.ajax({
                            url: "<?php echo  $this->AjaxPageName; ?>",
                            method: "<?php echo $this->typeRequest; ?>",
                            type: '<?php echo  $this->dataType;  ?>',
                            success: function(data) {
                                $('#<?php echo  $this->divShowId; ?>').append().html(data);
                                $('#<?php echo $eventId; ?>').removeAttr('disabled');

                            }
                        })
                    })
                });
            </script>
        <?php
            return true;
        }
    }
    /**
     * تقوم هذه الدالة بأرسال القيم عبر  اجاكس
     *   @param $eventId ex  ..("#btn");
     *   @param   $event  ex .. click ,keyUp,keyDown
     * @return void
     */
    private function send($eventId, $event)
    {

        if ($this->spinnerId != null) {


        ?>
            <script>
                $(document).ready(function() {
                    $('#<?php echo $eventId; ?>').<?php echo $event; ?>(function() {
                        <?php if ($event == 'click') { ?>
                            $('#<?php echo $eventId; ?>').attr('disabled', 'disabled');
                        <?php  }; ?>
                        $('#<?php echo $this->spinnerId; ?>').show();
                        $.ajax({
                            url: "<?php echo  $this->AjaxPageName; ?>",
                            method: "<?php echo $this->typeRequest; ?>",
                            type: '<?php echo  $this->dataType;  ?>',
                            data: {
                                <?php
                                foreach ($this->dataRequest as $vars) {
                                    echo $vars . ':' ?>$('#<?php echo $vars;  ?>').val(),
                            <?php
                                }
                            ?> },
                            success: function(data) {
                                $('#<?php echo $this->spinnerId; ?>').append(data.data);
                                $('#<?php echo  $this->divShowId; ?>').append().html(data);
                                $('#<?php echo $eventId; ?>').removeAttr('disabled');

                            }
                        }).done(function() {
                            $('#<?php echo $this->spinnerId; ?>').hide();
                        });
                    })
                });
            </script>
        <?php
            return true;
        } else {
        ?>
            <script>
                $(document).ready(function() {
                    $('#<?php echo $eventId; ?>').<?php echo $event; ?>(function() {
                        <?php if ($event == 'click') { ?>
                            $('#<?php echo $eventId; ?>').attr('disabled', 'disabled');
                        <?php  }; ?> $.ajax({
                            url: "<?php echo  $this->AjaxPageName; ?>",
                            method: "<?php echo $this->typeRequest; ?>",
                            type: '<?php echo  $this->dataType;  ?>',
                            data: {
                                <?php
                                foreach ($this->dataRequest as $vars) {
                                    echo $vars . ':' ?>$('#<?php echo $vars;  ?>').val(),
                            <?php
                                }
                            ?>},
                            success: function(data) {
                                $('#<?php echo  $this->divShowId; ?>').append().html(data);
                                $('#<?php echo $eventId; ?>').removeAttr('disabled');

                            }
                        })
                    })
                });
            </script>
<?php
            return true;
        }
    }
    /**
     * وظيفة الدالة هي لتحميل البيانات عند تحميل الصفحة
     *
     * @param [type] $divShowId
     * @param [type] $ajaxPageName
     * @param string $method
     * @param string $type
     * @param string $loaderId
     *
     * @return void
     */
    public function ajaxLoad($ajaxPageName,$divShowId,$loaderId=null,$method=null,$type=null){
        if($method==null){
            $method='get';
        }
        if($type==null){
            $type='text';
        }
if($loaderId!=null){


        ?>
     <script>
    $(document).ready(function() {
      $(window).ready(function() {
        $('#<?php echo $loaderId; ?>').show();
        $.ajax({
          url: "<?php echo  $ajaxPageName; ?>",
          method:"<?php echo $method; ?>",
          type:"<?php  echo $type; ?>",
          success:function(data) {
            $('#<?php echo $loaderId; ?>').append(data.data);
           $('#<?php echo $divShowId; ?>').append().html(data);

          }
        }).done(function() {
                            $('#<?php echo $loaderId; ?>').hide();
                        });
      });

    });
  </script>

     <?php
     }else if($loaderId==null){

        ?>
        <script>
       $(document).ready(function() {
         $(window).ready(function() {
           $.ajax({
             url: "<?php echo  $ajaxPageName; ?>",
             method:"<?php echo $method; ?>",
             type:"<?php  echo $type; ?>",
             success:function(data) {
              $('#<?php echo $divShowId; ?>').append().html(data);
             }
           });
         });

       });
     </script>

        <?php

     }
    }

};
class core  extends ajaxx{
    /* الكلاس الرئيسي */
     /*
     * هذه هي مكتبة بسيطة من تصميم سجاد عبد الكريم
     *
     */




    };


   ?>
