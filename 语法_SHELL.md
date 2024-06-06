---
title: 语法_SHELL
date: 2024-02-21T08:53:48Z
lastmod: 2024-06-03T23:11:50Z
---

# Shell 语法篇

---

# 变量

```bash
name="name"                     # 定义变量
echo $name
echo ${name}                    # "{}" 变量界定符

name="name"
readonly name                   # 只读变量:一旦写入不能改变值，输出只读变量可以不加$
echo name

${#name}                        # 变量的字符长度
unset name                      # 取消变量

tm=$(data)                      # 命令替换(command substitutions): 将命令data的返回值作为变量tm的值
tm=`data`                       # 旧方法

echo "your name:$name"          # 双引号内可以输出变量和转义符
echo 'your name:$name'          # 单引号内的内容原样输出
```

# 字符串操作

```bash
distro="ubuntu"
echo ${#distro} 				# 获取字符串长度
str1="book"
str2="hand"
str3=$str1$str2 				# 字符连接
expr index "$str" "word" 		# 查找特定单词或字母的索引值$str

echo ${distro:0:6}  			# 0表示起始位置，6表示从起始位置开始提取6个字符
echo ${distro:2}    			# 从索引2位置开始提取余下所有的字符
echo ${distro/free/more}    	# 将free替换为more
echo ${distro/free}         	# 删除free
echo ${distro//-}           	# 删除所有破折号，需要双斜杠
echo ${distro^^}            	# 小写转大写
echo ${distro,,}            	# 大写转小写
echo ${distro^}             	# 仅首字母大写
echo ${distro,}             	# 仅首字母小写
echo ${distro^^[jn]}        	# 指定字母大写
```

# 数组

```bash
array_name=(1,2,3)              # 一次性定义数组

array_name[0]=1                 # 单独定义数组
array_name[1]=1
array_name[2]=1

array_name+=("2")               # 追加数组元素
echo ${array_name[0]}           # 读取数组指定元素
unset array_name[0]             # 删除数组元素
length=${#array_name[*]}        # 获取数组长度
length=${#array_name[@]}        # 获取数组长度
length=${array_name[@]}         # 获取数组中的所有元素
length=${array_name[*]}         # 获取数组中的所有元素
length=${#array_name[n]}        # 获取单个元素长度
length=${#array_name[n]}        # 获取单个元素长度
declare -A array_name=(["googole"]="1234" ["googole"]="1234")   # 关联数组
${!array_name}                  # "!" 获取数组所有键
```

# 运算符

- 算数运算符
```bash
expr $a + $b                    # 加法
expr $a - $b                    # 减法
expr $a \* $b                   # 乘法
expr $a / $b                    # 除法
expr $a % $b                    # 取余
```
- 关系运算符
```bash
-eq 		                    # 等于为真 =
-ne 		                    # 不等于为真 <>
-gt 		                    # 大于为真 >
-ge 		                    # 大于等于为真 >=
-lt 		                    # 小于为真 <
-le 		                    # 小于等于为真 <=
== 			                    # 全等
!= 			                    # 不等
```
- 布尔运算符
```bash
[ !false ]                      # 非运算,表达式为True则返回False
[ -o ]                          # 或运算,有一个表达式为True则返回True
[ -a ]                          # 与运算,两个都为True则返回True
```
- 逻辑运算符("[[]]"为布尔运算)
```bash
[[ && ]]                        # 逻辑AND,两个为True返回True否则为False
[[ || ]]                        # 逻辑OR,一个为True返回True否则为True
```
- 字符运算符("[]"为布尔运算)
```bash
[ -Z $a ]                       # 字符串为0则返回True
[ -n $a ]                       # 字符串不为0则返回False
[ $a ]                          # 字符串不为空则返回True
[ $a == $b ]                    # 相等
[ $a = $b ]                     # 相等
[ $a != $b ]                    # 不相等
```
- 文件测试运算符
```bash
[ -b file ]                     # 检测文件为块设备则True
[ -c file ]                     # 检测文件为字符设备则True
[ -d file ]                     # 检测文件为目录则True
[ -f file ]                     # 检测文件为普通文件则True
[ -g file ]                     # 检测文件设置SGID位则True
[ -k file ]                     # 检测文件设置粘着位则True
[ -p file ]                     # 检测文件是有名管道则True
[ -u file ]                     # 检测文件设置SUID位则True
[ -r file ]                     # 检测文件可读则True
[ -w file ]                     # 检测文件可写则True
[ -x file ]                     # 检测文件可执行则True
[ -s file ]                     # 检测文件为空则True
[ -e file ]                     # 检测目录存在则True
```

# 逻辑符号

- 逻辑运算
```bash
;                               # 所有命令依次执行
&& 			                    # 逻辑与，前面执行成功，后面才执行
|| 			                    # 逻辑或，前面执行失败后面才执行
```
- 通配符
```bash
?                               # 单个任意字符
*                               # 匹配0到任意个 
[]                              # 匹配任意一个
[-]                             # 取范围
[^]                             # 取反
^行首限定符
$行尾限定符
root@devtest:~# cat /etc/ssh/sshd_config | grep -n Use[a-z]
root@devtest:~# cat /etc/ssh/sshd_config | grep -n Use*
root@devtest:~# cat /etc/ssh/sshd_config | grep -n Use[^a-z]
```

# bc
任意精度的计算器, expr let无法进行小数运算
```bash
val=$(echo "scale=2;3.14/2" | bc)
echo $val
```

# printf
字符串格式化输出
```bash
printf "%-10s %d %-10.2f " xiaomin 2 2.55   # 格式化输出 
printf "%-10s"                  # "-"表示左对齐,默认右对齐
printf "%+10s"                  # "+"输出加号
printf "%0.2f"                  # "m.n"表示浮点数格式位
printf "%10s"                   # "10"表示字符串格式位,表示该字符串需要使用几个位置
printf "%%"                     # 输出"%"
printf "\r"                     # 输出回车
printf "\t"                     # 制表
printf "\n"                     # 换行
```

# test
检查命令
```bash
test $[a] -eq $[b]
test $str1==$str2
test -e /bin/sh
```

# 脚本参数

向脚本传递参数
```bash
$0 		                        # 表示执行文件名（包含路径）
$1 $2 ... 	                    # 表示第几个参数
$# 		                        # 传递到脚本的参数个数
$* 		                        # 以一个单字符串显示所有向脚本传递的参数。如"$*"用「"」括起来的情况、以"$1 $2 … $n"的形式输出所有参数。
$$ 		                        # 脚本运行的当前进程ID号
$! 		                        # 后台运行的最后一个进程的ID号
$@ 		                        # 与$*相同，但是使用时加引号，并在引号中返回每个参数。如"$@"用「"」括起来的情况、以"$1" "$2" … "$n" 的形式输出所有参数。
$? 		                        # 显示最后命令的退出状态。0表示没有错误，其他任何值表明有错误。
./test.sh 1 2 3 ...
```

# if 判断

```bash
if condition
then
    command1 
    command2
    ...
    commandN
else
    command
fi

if condition1
then
    command1
elif condition2 
then 
    command2
else
    commandN
fi

if [ $str1 -eq $str2 ];then if

if [[ $a = 1 || $b = 2 ]]   	# 双方括号可以使用|| && 和计算符号<> < > = != + - / * ^
if [ $a -a $b ]             	# 单方括号需要使用-a -o ! -eq等
if [ $a ] || [ $b ]          	# 使用|| &&
if (( "$a" < "$b" ))        	# (())内可以使用计算符号<> < > = != + - / * ^
if (( )) || (())

if test $[] -eq $[]
then
 echo ""
else
 echo ""
fi
#字符串测试
= 等于为真
!= 不等于为真
-z 字符长度为零为真
-n 字符长度不为零为真

if test $[] = $[]
then
 echo ""
else
 echo ""
fi

if test -e ./bash
then
    echo '文件已存在!'
else
    echo '文件不存在!'
fi
#Shell 还提供了与( -a )、或( -o )、非( ! )三个逻辑操作符用于将测试条件连接起来，其优先级为： ! 最高， -a 次之， -o 最低
if [ test -e ./notFile -o -e ./bash ]
then
    echo '至少有一个文件存在!'
else
    echo '两个文件都不存在'
fi

if [ $(ps -ef | grep -c "ssh") -gt 1 ]; then echo "true"; fi

if [ "$a" -gt "$b" ]; then #if else 的 [...] 判断语句中大于使用 -gt，小于使用 -lt
    ...
fi

if (( a > b )); then #如果使用 ((...)) 作为判断语句，大于和小于可以直接使用 > 和 <
    ...
fi
#if注意项：[]内的变量或字符需要双引号;单独只用<>需要转义符；<>=等计算符号使用(( ))包含;
[ str1 -a str2 ]
[ str1 -o str2 ]
[] || []
[] && []
```

# case 判断

```bash
echo '输入 1 到 4 之间的数字:'
echo '你输入的数字为:'
read aNum
case $aNum in
    1 )  
    echo '你选择了 1'
    ;;
    2 )  
    echo '你选择了 2'
    ;;
    3 )  
    echo '你选择了 3'
    ;;
    4 )  
    echo '你选择了 4'
    ;;
    * )  
    echo '你没有输入 1 到 4 之间的数字'
    ;;
esac
``` 

# for 循环
items可以是列表,数组,命令结果等
```bash
for var in item1 item2 ... itemN
do
    command1
    command2
    ...
    commandN
done

for var in item1 item2 ... itemN; do command1; command2… done;
for ((i = 0 ; i < 10 ; i++));do echo "hello!";done
```

# while 循环

- 基础用法
```bash
int=1
while(( $int<=5 ))              # condition为True则command执行,直到condition为False时则停止
do
    echo $int
    let "int++"
done

echo '按下 <CTRL-D> 退出'
echo -n '输入你最喜欢的网站名: '
while read FILM
do
    echo "是的！$FILM 是一个好网站"
done

while :                         # 无限循环
do
    command
done

while true                      # 无限循环
do
    command
done

for (( ; ; ));do                # 无限循环

done
```
- break用法
```bash
#!/bin/bash
while :
do
    echo -n "输入 1 到 5 之间的数字:"
    read aNum
    case $aNum in
        1|2|3|4|5) echo "你输入的数字为 $aNum!"
        ;;
        *) echo "你输入的数字不是 1 到 5 之间的! 游戏结束"
            break                   # 跳出所有循环
        ;;
    esac
done
```
- continue用法
```bash
#!/bin/bash
while :
do
    echo -n "输入 1 到 5 之间的数字: "
    read aNum
    case $aNum in
        1|2|3|4|5) echo "你输入的数字为 $aNum!"
        ;;
        *) echo "你输入的数字不是 1 到 5 之间的!"
            continue                # 跳出当前循环
            echo "游戏结束"
        ;;
    esac
done
```

# until 循环

```bash
until condition                     # 先执行command,直到condition条件为True时停止
do
    command
done
```

# 函数

```bash
[function] funWithReturn(){
    local a="2"     # 局部变量
    echo "这个函数会对输入的两个数字进行相加运算..."
    echo "输入第一个数字: "
    read aNum
    echo "输入第二个数字: "
    read anotherNum
    echo "两个数字分别为 $aNum 和 $anotherNum !"

    return $(($aNum+$anotherNum))
}
funWithReturn
echo "输入的两个数字之和为 $? !"

funWithParam(){
    echo "第一个参数为 $1 !"
    echo "第二个参数为 $2 !"
    echo "第十个参数为 $10 !"
    echo "第十个参数为 ${10} !"
    echo "第十一个参数为 ${11} !"
    echo "参数总数有 $# 个!"
    echo "作为一个字符串输出所有参数 $* !"
}

funWithParam 1 2 3 4 5 6 7 8 9 34 73    # 调用函数

#shell文件包含
. filename   # 注意点号(.)和文件名中间有一空格
source filename
```

# 注释

```bash
#注释
:<<EOF

EOF
```

# 环境变量

```bash
env

#定义环境变量，仅当前会话有效
export variable_name=value

#个人文件持久化变量
vi ~/.bashrc 
source .bashrc

#全局变量
export GLOBAL_VARIABLE="This is a global variable"
/etc/environment
/etc/profile
```

# 运行shell

- 子shell方式运行
```bash
./shell.sh
/bin/sh shell.sh
```
- 全局shell方式运行
```bash
source shell.sh
. shell.sh
```

---

# Shell 命令篇

---

# grep
grep (global regular expression) 命令用于查找文件里符合条件的字符串或正则表达式。
```bash
grep -l "root" /etc/passwd /etc/shadow              # 查找文件中包含"root"的行
grep -n "root" /etc/passwd                          # 显示行号
grep -v "root" /etc/passwd                          # 反选，不包含"root"的行
grep -r "root" /etc                                 # 在文件夹和子文件夹的文件中递归搜索"root"
grep -i "root" /etc                                 # 忽略大小写
grep -w "root" /etc                                 # 仅匹配单词
grep -x "root" /etc                                 # 仅匹配行
grep -e "root" -e "nobody" /etc                     # 搜索多个关键字
grep -f a.txt /root                                 # 从文件中获取参数搜索
grep -m 2 "root" /etc/passwd                        # 最大寻找数
grep -E "^root" /etc/passwd                         # 使用扩展正则表达式
```

# sed

```bash
a 新增 当前行的下一行
c 取代 替换
d 删除 删除整行
i 插入 当前行的上一行
s 取代 正则表达式
//  匹配
/^/ 行首
/$/ 行尾
-e 多次执行
-i 保存

sed -i s/#PermitRootLogin/PermitRootLogin/ /etc/ssh/sshd_config
sed '1,2a\newline' /etc/passwd                      # 当前行的
sed '/linux/,/Linux/i\newline' /etc/passwd
sed 's/linux/Linux/3g' /etc/passwd                  # g表示多次匹配
sed '/linux/c\newline' /etc/passwd
```

# awk

```bash

```

# split
split命令用于将一个文件分割成数个, 默认情况下将按照每1000行切割成一个文件
```bash
split -1000 test.txt test.txt                       # "-1000"按行数分割, 设置切割后的前置文件名split会自动加编号
split -b 20  test.txt                               # "-b 20"按照字节分割
split -C 20  test.txt                               # "-C 20"按照直接分割,但是尽量维持每行的完整性
```

# wc
wc命令用于计算字数, 利用wc指令我们可以计算文件的Byte数、字数、或是列数，若不指定文件名称、或是所给予的文件名为"-", 则wc指令会从标准输入设备读取数据
```bash
wc -l test.txt                                      # 显示行数
wc -w test.txt                                      # 显示单词数
wc -c test.txt                                      # 显示字节数
```

# uniq
uniq 命令用于检查及删除文本文件中重复出现的行列，一般与 sort 命令结合使用
```bash
uniq -c test.txt                                    # 删除重复行并统计每行出现的次数
uniq -d test.txt                                    # 仅显示重复出现的行
uniq -f test.txt                                    # 忽略比较指定的栏位
uniq -s test.txt                                    # 忽略比较指定的字符
uniq -u test.txt                                    # 仅显示出一次的行列
uniq -w test.txt                                    # 指定要比较的字符

注: 当重复的行并不相邻时，uniq 命令是不起作用的, 需配合 sort 命令使用
```

# join

```bash

```

# sort
sort 可针对文本文件的内容, 以行为单位, 按照ASCII码的次序排序
```bash
sort -r test.txt                                    # 反向排序
sort -u test.txt                                    # 排序同时输出去重的结果
sort -o ./test1.txt test.txt                        # 指定输出文件
sort -b test.txt                                    # 忽略每行前面开始出的空格字符
sort -c test.txt                                    # 检查文件是否已经按照顺序排序
sort -f test.txt                                    # 排序时，将小写字母视为大写字
sort -t , test.txt                                  # 指定排序时所用的栏位分隔字
sort -n test.txt                                    # 依照数值的大小排
sort -k 2 test.txt                                  # 按指定的列进行排序
```

# 

```bash

```

# 

```bash

```

# 

```bash

```

# 

```bash

```


