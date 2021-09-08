<?PHP
header("Content-Type: text/html; charset=utf8");
if (!isset($_POST["sub1"])) {
    header("refresh:1;url=index.html");
    exit("錯誤執行");
}

include 'connect.php';

$username = mysqli_real_escape_string($cont, $_REQUEST['username']);
$pwd = md5($_REQUEST['pwd']);
if ($username && $pwd) { 
    $sql_login = "select * from test.r_user where r_name='$username' and r_pwd='$pwd'"; //查询账户密码是否匹配
    $result = mysqli_query($cont, $sql_login);
    if (mysqli_fetch_row($result)) {
        header("refresh:0;url=homepage.php");
        exit;
    } else {
        echo "名字或密碼錯誤";
        echo "
            <script>
            setTimeout(function(){window.location.href='index.html';},1000);
            </script>
            "; //如果錯誤使用js 1秒後跳轉到登入頁面重試;
    }
} else { //如果使用者名稱或密碼有空
    echo "表單填寫不完整";
    echo "
        <script>
        setTimeout(function(){window.location.href='index.html';},1000);
        </script>";
    //如果錯誤使用js 1秒後跳轉到登入頁面重試;
}
mysql_close(); //關閉資料庫
?>