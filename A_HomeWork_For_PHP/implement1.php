<?php
header("Content-Type: text/html; charset=utf8");
require_once("connect.php");
Selectn();
function Selectn(){
    global $cont;
    if(empty($_POST['seledata'])){ //判断参数是否为空
        header("refresh:3;url='homepage.php'");
        exit("请输入需要查询的关键字！");
    }
    $term=$_REQUEST["term"];
    foreach($term as $i) //将term中的值赋予$im
        $im=$i;
    if(isset($_REQUEST['seledata'])){
        $seledata=$_REQUEST['seledata'];
        $seledata=mysqli_real_escape_string($cont,$seledata); //清理用户输入的参数
    }
    $sql_select = "select * from test.ge_info where $im like '%$seledata%'"; //查询语句
    $result=mysqli_query($cont,$sql_select);
    mysqli_close($cont);
    require 'homepage.php';
}

?>