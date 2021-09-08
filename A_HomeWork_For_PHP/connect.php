<?php
    loginDatabase("127.0.0.1:3306","root","");

    function loginDatabase($sql_server,$sql_user,$sql_pw){
        global $cont;
        $cont = mysqli_connect($sql_server,$sql_user,$sql_pw); //连接数据库信息
        if(!$cont){
            die("connect falied: ".mysqli_connect_error());
        }
        mysqli_query($cont,"set shop 'utf8'"); //设置数据库编码
    }
?>