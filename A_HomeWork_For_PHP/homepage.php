<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personnel Information Sheet</title>
    <link rel="stylesheet" href="./css/style.css">
</head>
    <body >
    <?php
        $fromurl="index.html"; //禁止未经许可的跳转
        if( $_SERVER['HTTP_REFERER'] == "" )
        {
        header("Location: $fromurl");
        exit;
        }
    ?>
        <form action="implement1.php" method="post" class="form">
            <select name="term[]" class="btn">
                <option value="ge_id" >学号</option>
                <option value="ge_name" >名字</option>
                <option value="ge_depart" >部门</option>
                </option>
            </select>
            <input type="text" name="seledata" placeholder="输入学号/姓名/部门查询" class="input" />
            <button type="submit" class="btn">查询</button>
        </form>
        <form action="implement2.php" class="form" method="post">
            <input type="text" name="name" placeholder="名字" class="input" />
            <input type="text" name="depart" placeholder="部门" class="input" />
            <input type="text" name="time" placeholder="入职时间" class="input" />
            <button type="submit" class="btn">插入</button>
        </form>      
        <br>
        <form action="implement4.php" class="form" method="post">
            <input type="text" name="id" placeholder="学号" class="input" />
            <input type="text" name="upname" placeholder="修改后的名字" class="input" />
            <input type="text" name="updepart" placeholder="修改后的部门" class="input" />
            <button type="submit" class="btn">修改</button>
        </form>        
        <br>
        <form action="implement3.php" class="form" method="post">
            <input type="text" name="deledata" placeholder="输入名字删除" class="input" />
            <button type="submit" class="btn">删除</button>
        </form>

        <div class="container">
        <h1 style="font-size: 30px; text-align:center;">Personnel Information Sheet</h1>
        <table border=1 width=700 cellSpacing=0 align=center>
        <tr align=right>
            <th>ID</th>
            <th>Name</th>
            <th>Depart</th>
            <th>Time</th>
        </tr>
        </div>
        <?php

            if(!empty($result)){
                while($row=mysqli_fetch_array($result)){ //从查询结果中提取数据到row
                    echo "<tr align=right>";
                    echo "<td>".$row["ge_id"]."</td>"; 
                    echo "<td>".$row["ge_name"]."</td>";
                    echo "<td>".$row["ge_depart"]."</td>";
                    echo "<td>".$row["ge_time"]."</td>";
                    echo "</tr>";
                }
            }else{
                echo "<tr align=center><td colspan=4 >0 result!</td></tr>";
            }
            echo "</table>";
        ?>
    </body>
</html>