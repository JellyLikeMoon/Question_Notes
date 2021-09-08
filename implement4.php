<?php
header("Content-Type: text/html; charset=utf8");
require_once("connect.php");
Altern();
function Altern(){
    global $cont;
    if (empty($_POST['id'] && $_POST['upname'] && $_POST['updepart'])){ //判断参数是否为空
        header("refresh:3;url=homepage.php");
        exit("请输入修改关键词！");
    }
    $id=$_POST['id'];
    $upname=$_POST['upname'];
    $updepart=$_POST['updepart'];
    $sql_alt = "update test.ge_info set ge_name='$upname', ge_depart='$updepart' where ge_id='$id'"; //更新语句
    if(mysqli_query($cont,$sql_alt)){
        echo "Update Successful!<br>";
        echo $id." ".$upname." ".$updepart;
        header("refresh:1;url=homepage.php");
    }else{
        echo "Error:".$sql_alt."<br />".mysqli_error($cont);
    }
    mysqli_close($cont);
}
?>