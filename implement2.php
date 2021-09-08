<?php
header("Content-Type: text/html; charset=utf8");
require_once("connect.php");
Insertn();
function Insertn(){
    global $cont;
    if (empty($_POST['name'] && $_POST['depart'] && $_POST['time'])) { //判断参数是否为空
        header("refresh:3;url=homepage.php");
        exit("请输入数据后提交！");
    }
    $sql_insert="insert into test.ge_info(ge_name,ge_depart,ge_time) values('$_POST[name]','$_POST[depart]','$_POST[time]')"; //插入语句
    if(mysqli_query($cont,$sql_insert)){
        echo "Insert Successful!<br />";
        echo $_POST['name']." ".$_POST['depart']." ".$_POST['time'];
        header("refresh:3;url=homepage.php");
    }else{
        echo "Error:".$sql_insert."<br />".mysqli_error($cont);
    }
    mysqli_close($cont);
}
?>