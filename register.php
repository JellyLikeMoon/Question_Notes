<?php
header("Content-type:text/html;charset=utf-8");
UIInfomation();
loginDatabase("127.0.0.1:3306", "root", "");
InserData();

function UIInfomation()
{
    echo "<p>用户名：" . $_REQUEST['username'] . "</p>";
    echo "<p>密码：" . $_REQUEST['pwd'] . "</p>";
    echo "<p>邮箱：" . $_REQUEST['email'] . "</p>";
    echo "<p>性别：" . $_REQUEST['sex'] . "</p>";
    echo "<p>爱好：" . $_REQUEST['hobby'] . "</p>";
    echo "<p>备注：" . $_REQUEST['remarks'] . "</p>";
    echo "<p>IP地址：" . $_SERVER['REMOTE_ADDR'] . "</p>";
    echo "<p>浏览器环境：" . $_SERVER['HTTP_USER_AGENT'] . "</p>";
    if (empty($_REQUEST)) {
        die("NOT FROM!");
    }
    $check_fields = array('username', 'pwd', 'email');
    foreach ($check_fields as $N) {
        if (empty($_REQUEST[$N])) { //判断参数是否为空
            header("refresh:1;url=index.html");
            die("ERROR: " . $N . " Cant empty!");
        }
    }
}

function loginDatabase($sql_server, $sql_user, $sql_pw)
{
    global $cont;
    $cont = mysqli_connect($sql_server, $sql_user, $sql_pw);
    if (!$cont) {
        die("connect falied: " . mysqli_connect_error());
    }
    mysqli_query($cont, "set names 'utf8'");
    mysqli_query($cont, "use test");
}

function InserData()
{
    global $cont;
    $username = mysqli_real_escape_string($cont, $_REQUEST['username']);//获取数据
    $email = mysqli_real_escape_string($cont, $_REQUEST['email']);
    $sex = mysqli_real_escape_string($cont, $_REQUEST['sex']);
    $hobby = mysqli_real_escape_string($cont, $_REQUEST['hobby']);
    $remarks = mysqli_real_escape_string($cont, $_REQUEST['remarks']);
    $pwd = md5($_REQUEST['pwd']);
    $file = $_FILES["photo"];
    
    $sql_exit = "select r_id from test.r_user where r_name='$username' ";//检查时候用户名是否存在
    $result = mysqli_query($cont, $sql_exit);
    if (mysqli_fetch_row($result)) {
        header("refresh:1;url=index.html");
        die("username already exists, please change.");
    }

    $typeArr = explode("/", $file["type"]); //拆分为数组
    $photoname = "image/".time().".".$typeArr[1]; //文件存储位置
    $bol = move_uploaded_file($file["tmp_name"], $photoname); //更新文件存储位置

    $sql_insert = "insert into test.r_user(r_name,r_pwd,r_email,r_sex,r_hobby,r_remarks,r_path) values('$username','$pwd','$email','$sex','$hobby','$remarks','$photoname')"; //插入数据
    $result = mysqli_query($cont, $sql_insert);
    if ($result) {
        echo "successful!";
        header("refresh:5;url=index.html");
    } else {
        echo "failed!" . mysqli_error($cont);
        header("refresh:5;url=register.html");
    }
}




    