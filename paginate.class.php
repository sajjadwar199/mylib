<?php  
require 'fileUpload.class.php' ;
class paginate extends  fileUpload{
//class by sajjad kareem
/* الجدول  */
 private $table;
/* الصفحة */
 private $page;
/* where */
 private $where;
/* عدد العناصر في الصفحة */
 private $limit=5;
/* عدد العناصر التي ستعرض في الصفحة */ 
 private $number_show;
/* تحديد عددالروابط في الوسط */ 
 private $links_between;
/* متغيرات الروابط في الرابط    ex ?page=2&&id=10 */ 
private $url_vars;
private function  page_get(){
   if(isset($_GET['page'])){
                $page=(int)$_GET['page'];
                if($page==0||$page<1){
                $this->number_show=0;
                }else{
                $this->number_show=($page * $this->limit) - $this->limit;
    } 
    }else{
        $this->number_show=0;
    }
    }
public  function  paginate_option($table,$page,$limit=null,$where=null,$links_between=null,$url_vars=null){
    $this->url_vars=$url_vars;
    $this->links_between=$links_between;
    $this->table=$table;
    $this->page=$page;
    $this->where=$where;
    if(isset($limit)){
        $this->limit=$limit;
    }
    /* فحص الصفحات في الرابط */ $this->page_get(); }
public  function  prev(){
    if(isset($_GET['page'])){
        $page= (int)$_GET['page'];
    }
    if(!isset($page)||$page==''||$page<1){
        $page=1;
    }

       if($page>1){
           $mins=$page-1;
      
    $prev_btn=" <li class='page-item'>"."<a class='page-link' href=$this->page?page=$mins$this->url_vars   >&laquo;</a>"."</li>";
      return $prev_btn;
       }
    }
private function  next($rowsperpage){
    if(isset($_GET['page']) and $_GET['page']!=''){
        $page= (int)$_GET['page'];
    }
    if(!isset($page)||$page==''||$page<1||$page>$rowsperpage){
        $page=1;
    }

       if($page+1<=$rowsperpage){
        $pos=$page+1;
      
    $next_btn=" <li class='page-item'>"."<a class='page-link' href=$this->page?page=$pos$this->url_vars  >&raquo;</a>"."</li>";
      return $next_btn;
       }
    }
private function  links($current_page, $total_pages, $url){
        if(isset($_GET['page'])){
            $page= (int)$_GET['page'];
        }
        // your current page
        // $pages=20; // Total number of pages
        if(!isset($page)||$page==''||$page<1||$page>$current_page){
            $page=1;
        }

        if($this->links_between!=''){
            $limit=$this->links_between;
        }else{
            $limit=5  ; /* عدد الصفحات التي تضهر في وسط تعداد الصفحات */

        }

        $links = "";
        if ($total_pages >= 1 && $current_page <= $total_pages) {
            if($page==1){
                $links .= "
        
                <li class='page-item active'>
                <a  class='page-link' href=\"{$url}?page=1$this->url_vars \">1</a>
                
                </li>";
            }else{
                $links .= "
        
                <li class='page-item'>
                <a  class='page-link' href=\"{$url}?page=1$this->url_vars \">1</a>
                
                </li>";
            }
        
            $i = max(2, $current_page - $limit);
            if ($i > 2)
                $links .= "              <li  class='page-item'> 
                <a class='page-link'>...</a>
                </li> ";
            for (; $i < min($current_page + $limit+1, $total_pages); $i++) {
                if($i==@$page){
                    $links .= "


                    <li class='page-item active'>
                    <a class='page-link' href=\"{$url}?page={$i}$this->url_vars \">{$i}</a>
                    </li>
    ";


                }else{
                    $links .= "
                    <li  class='page-item'> 
                    <a class='page-link' href=\"{$url}?page={$i}$this->url_vars \">{$i}</a>
                    </li>
                    
                    ";
                }
            
            }
            if ($i != $total_pages)
                $links .= "     <li  class='page-item'>    <a class='page-link'>...</a></li> ";
                if($page==$total_pages){

                     $links .= "
                    <li  class='page-item active'> 
                <a  class='page-link' href=\"{$url}?page={$total_pages}$this->url_vars \">{$total_pages}</a>
                    
                    </li>
                    
                    "; 
                }else{
                    $links .= "
                    <li  class='page-item'> 
                <a  class='page-link' href=\"{$url}?page={$total_pages}$this->url_vars \">{$total_pages}</a>
                    
                    </li>
                    
                    ";
                }
        
        }
        return $links;}



public  function  paginate_links(){
        if(isset($_GET['page'])){
            $page=(int)$_GET['page'];
        }
      
            $conn = $this->connect();
        /* عدد العناصر في الجدول */  $count_items="SELECT COUNT(*) from $this->table $this->where ";
            /* استعلام عن عدد العناصر في الجدول */  $excecute_pagination=mysqli_query( $conn,$count_items);
            $row_pagination=mysqli_fetch_array($excecute_pagination);
            /* جميع الصفوف */ $total_rows=array_shift($row_pagination);
            /*  عدد الروابط في الصفحة في الصفحة    */$rowsperpage=$total_rows/$this->limit;
            $rowsperpage=ceil($rowsperpage);
            if(!isset($page)||$page==''||$page<1||$page>$rowsperpage){
            $page=1;
            }
        
                
        
            ?>
            <nav aria-label="Page navigation example">
                
        <ul class="pagination">
        <?php        if($page>=1){
 ?>
    <!--prec btn -->  <?php   echo  $this->prev(); ?>

  
    <?php   /*  echo $this->links($rowsperpage);  */ 
    echo $this->links($page,$rowsperpage,$this->page);
    
    
    ?>
         


 <!--next btn --><?php echo $this->next($rowsperpage); }  ?>
        </nav>
        <?php 
    }
public  function  show_with_pagination(){
 
    $conn = $this->connect();
    $query = "SELECT * FROM $this->table   $this->where   LIMIT $this->number_show,$this->limit";
    
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




};
?>