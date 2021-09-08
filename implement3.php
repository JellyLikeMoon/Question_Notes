<?php
header("Content-Type: text/html; charset=utf8");
require_once("connect.php");
Deleten();
function Deleten(){
    global $cont;
    if(empty($_POST['deledata'])){ //判断参数是否为空
        header("refresh:3;url=homepage.php");
        exit("请输入关键字删除数据！");
    }
    $deledata=$_POST['deledata'];
    $sql_dele="delete from test.ge_info where ge_name='$deledata'"; //删除语句
    if(mysqli_query($cont,$sql_dele)){
        echo "Delete Successful!";
        header("refresh:1;url=homepage.php");
    }else{
        echo "Error:".$sql_dele."<br />".mysqli_error($cont);
    }
    mysqli_close($cont);
}
?>