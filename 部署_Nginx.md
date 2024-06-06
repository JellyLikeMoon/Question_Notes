---
title: 部署_Nginx
date: 2024-02-21T08:53:17Z
lastmod: 2024-03-08T12:10:19Z
---

# 1. QAQ答疑

### Nginx如何处理php请求

Nginx本身不能处理PHP，当接收到PHP请求时，把请求发给fastcgi管理进程处理，fastcgi管理进程选择cgi子进程处理结果并返回给nginx，nginx返回给客户端。

### 为什么访问nginx出现permission denial

apt安装php-fpm的缺省user为www-data，apt默认安装nginx的缺省user为www-data，编译安装nginx的缺省user为nginx
需要修改nginx配置文件/etc/ngin/ngin.conf
user www-data

### 什么是PHP-FPM

PHP-FPM是一个PHP Fastcgi管理器，仅用于php，官方地址：http://php-fpm.org/
编译安装PHP时，添加-enable-fpm可以直接集成php-fpm，或者直接apt install php8.1-fpm php8.1-cli

### php-fpm配置略解

apt安装php-fpm配置文件位于/etc/php/8.1/fpm/pool.d/www.conf

```bash
listen.owner = www-data
listen.group = www-data
listen = /run/php/php8.1-fpm.sock 		//此为默认配置，采用sock方式连接，适用于单实例，即php+php-fpm同一台服务器
listen = 127.0.0.1:9000 				//此为采用tcp连接，适用于多实例，即php+php-fpm处于不同服务器，速度受限于tcp
```

cat /etc/shadow若不存在上述用户和组则创建

```bash
groupadd www-data
useradd -g www-data www-data
```

---

# 2. 源码编译安装Nginx

### 源码包

```bash
wget http://nginx.org/download/nginx-1.23.1.tar.gz
tar -zxvf nginx-1.23.1.tar.gz -C /opt/
mkdir /etc/nginx
mkdir /usr/local/nginx
```

### 依赖包

* Centos方式

```bash
dnf install -y gcc openssl* zlib* pcre* make
```

* Ubuntu方式

```bash
apt install gcc libpcre3 libpcre3-dev zlib1g-dev openssl libssl-dev make libgd-dev libgeoip-dev
```

### 编译安装

```bash
cd /opt/nginx-1.23.1

./configure
--prefix=/usr/local/nginx
--pid-path=/run/nginx.pid
--conf-path=/etc/nginx/nginx.conf
--http-log-path=/var/log/nginx/access.log
--error-log-path=/var/log/nginx/error.log
--lock-path=/var/lock/nginx.lock
--pid-path=/run/nginx.pid<br />--with-compat
--with-debug
--with-pcre-jit
--with-http_addition_module
--with-http_stub_status_module
--with-http_realip_module
--with-http_auth_request_module
--with-http_v2_module
--with-http_slice_module
--with-http_dav_module
--with-threads
--with-http_geoip_module=dynamic
--with-http_sub_module
--with-http_ssl_module
--with-stream=dynamic
--with-stream_ssl_module
--with-http_gunzip_module
--with-http_gzip_static_module
--with-http_image_filter_module=dynamic

make && make install
```

### 配置Nginx服务

/lib/systemed/system/nginx.service
 ~~/usr/lib/systemd/system/nginx.service~~

```bash
[Unit]
Description=Nginx Web Service
Documentation=https://nginx.org/en/docs
After=network.target
[Service]
Type=forking
PIDFile=/run/nginx.pid
ExecStartPre=/usr/local/nginx/sbin/nginx  -t -c /etc/nginx/nginx.conf
ExecStart=/usr/local/nginx/sbin/nginx
ExecReload=/usr/local/nginx/sbin/nginx  -s reload
ExecStop=/usr/local/nginx/sbin/nginx  -s stop
PrivateTmp=true
[Install]
WantedBy=multi-user.target

systemctl daemon-reload

注意：
若编译时未指定PIDFile，默认目录/usr/local/nginx/logs/nginx.pid
apt安装nginx PIDFile文件目录位于/run/nginx.pid
apt安装存在/etc/init.d/nginx，若使用则需要修改当前值

PATH=:/usr/local/nginx/sbin/
DAEMON=/usr/local/nginx/sbin/nginx
PID= /etc/nginx/nginx.conf
```

### 配置环境变量

/etc/profile

```bash
export PATH=$PATH:/usr/local/nginx/sbin/

source /etc/profile
ln -s /usr/local/nginx/sbin/nginx /usr/local/sbin/
```

### 操作指令

```bash
./usr/local/nginx/sbin/nginx  				//启动
./usr/local/nginx/sbin/nginx -s stop 		//停止
./usr/local/nginx/sbin/nginx -s reload 		//重载
./usr/local/nginx/sbin/nginx -s quit 		//退出
./usr/local/nginx/sbin/nginx  -t 			//检查配置文件格式
./usr/local/nginx/sbin/nginx  -V 			//查看编译信息
./usr/local/nginx/sbin/nginx  -v 			//查看版本信息
```

### 配置文件（支持PHP）

* /etc/nginx/nginx.conf

```bash
user  www-data;
worker_processes  auto;
pid /run/nginx.pid;

events {
worker_connections  1024;
}

http {
include       mime.types;
default_type  application/octet-stream;
sendfile        on;
tcp_nopush     on;
keepalive_timeout  65;
gzip  on;

server {
listen       80;
server_name  localhost;

location / {
root   /var/www/html;
index  index.php index.html index.htm;
try_files $uri $uri/ /index.html;
}

error_page   500 502 503 504  /50x.html;
location = /50x.html {
root   /var/www/html;
}

location ~ \.php$ {

<div>
<!-- ##    root      /var/www/html;  
#    fastcgi_pass   127.0.0.1:9000;  
#   fastcgi_index  index.php;  
#    fastcgi_param  SCRIPT_FILENAME  /scripts$fastcgi_script_name;  
#    include        fastcgi_params; --> //此种方式对应php-fpm中listen = 127.0.0.1:9000使用
</div>

<div>
<!-- fastcgi_pass unix:/run/php/php-fpm.sock;  
include fastcgi-php.conf; --> //此种方式对应php-fpm中listen = /run/php/php8.1-fpm.sock使用
</div>

}
}
}
```

* /etc/nginx/fastcgi-php.conf

```bash
fastcgi_split_path_info ^(.+?\.php)(/.*)$;
try_files $fastcgi_script_name =404;
set $path_info$fastcgi_path_info;
fastcgi_param PATH_INFO $path_info;
fastcgi_index index.php;
include fastcgi.conf;
```

* /etc/nginx/fastcgi.conf

```bash
fastcgi_param  SCRIPT_FILENAME    $document_root$fastcgi_script_name;
fastcgi_param  QUERY_STRING       $query_string;
fastcgi_param  REQUEST_METHOD     $request_method;
fastcgi_param  CONTENT_TYPE       $content_type;
fastcgi_param  CONTENT_LENGTH     $content_length;
fastcgi_param  SCRIPT_NAME        $fastcgi_script_name;
fastcgi_param  REQUEST_URI        $request_uri;
fastcgi_param  DOCUMENT_URI       $document_uri;
fastcgi_param  DOCUMENT_ROOT      $document_root;
fastcgi_param  SERVER_PROTOCOL    $server_protocol;
fastcgi_param  REQUEST_SCHEME     $scheme;
fastcgi_param  HTTPS              $https if_not_empty;
fastcgi_param  GATEWAY_INTERFACE  CGI/1.1;
fastcgi_param  SERVER_SOFTWARE    nginx/$nginx_version;
fastcgi_param  REMOTE_ADDR        $remote_addr;
fastcgi_param  REMOTE_PORT        $remote_port;
fastcgi_param  SERVER_ADDR        $server_addr;
fastcgi_param  SERVER_PORT        $server_port;
fastcgi_param  SERVER_NAME        $server_name;
fastcgi_param  REDIRECT_STATUS    200;
```

* /etc/nginx/fastcgi_params

```bash
fastcgi_param  QUERY_STRING       $query_string;
fastcgi_param  REQUEST_METHOD     $request_method;
fastcgi_param  CONTENT_TYPE       $content_type;
fastcgi_param  CONTENT_LENGTH     $content_length;
fastcgi_param  SCRIPT_NAME        $fastcgi_script_name;
fastcgi_param  REQUEST_URI        $request_uri;
fastcgi_param  DOCUMENT_URI       $document_uri;
fastcgi_param  DOCUMENT_ROOT      $document_root;
fastcgi_param  SERVER_PROTOCOL    $server_protocol;
fastcgi_param  REQUEST_SCHEME     $scheme;
fastcgi_param  HTTPS              $https if_not_empty;
fastcgi_param  GATEWAY_INTERFACE  CGI/1.1;
fastcgi_param  SERVER_SOFTWARE    nginx/$nginx_version;
fastcgi_param  REMOTE_ADDR        $remote_addr;
fastcgi_param  REMOTE_PORT        $remote_port;
fastcgi_param  SERVER_ADDR        $server_addr;
fastcgi_param  SERVER_PORT        $server_port;
fastcgi_param  SERVER_NAME        $server_name;
fastcgi_param  REDIRECT_STATUS    200;
```

---

# 3. apt安装Nginx

### 安装

```bash
apt install nginx
```

### 配置文件（支持PHP）

/etc/nginx/sites-enabled/default

```bash
location ~ \.php$ {

<div>
<!--    root      /var/www/html;  
fastcgi_pass   127.0.0.1:9000;  
fastcgi_index  index.php;  
fastcgi_param  SCRIPT_FILENAME  /scripts$fastcgi_script_name;  
include        fastcgi_params; --> //此种方式对应php-fpm中listen = 127.0.0.1:9000使用
</div>

<div>
<!--    fastcgi_pass unix:/run/php/php-fpm.sock;  
include fastcgi-php.conf; --> //此种方式对应php-fpm中listen = /run/php/php8.1-fpm.sock使用
</div>

}
```

### 操作

```bash
systemctl start nginx 			//启动
systemctl restart nginx 		//重启
systemctl stop nginx 			//停止
systemctl enable nginx 			//开机自启
systemctl disable nginx 		//取消开机自启
systemctl reload nginx 			//重载配置文件
```

---

### 生成证书

```bash
//生成虚拟CA机构
openssl genrsa -des3 -out server.key 2048
openssl rsa -in server.key -out server.key 															//除去密码
openssl req -new -x509 -key server.key -out ca.crt -days 365 										//生成CRT证书
openssl req -x509 -new -nodes -sha256 -days 365 -key myCA.key  -out myCA.crt 						//生成CRT证书

openssl genrsa -des3 -out ca.key 4096
openssl req -x509 -new -nodes -sha512 -days 3650
-subj "/C=CN/ST=Beijing/L=Beijing/O=example/OU=Personal/CN=yourdomain.com"
-key ca.key
-out ca.crt 																						//生成以域名的证书，CN为域名

//生成网站证书
openssl genrsa -out server.key 1024
openssl req -new -key server.key -out server.csr 													//生成csr请求文件
openssl x509 -req -in server.csr -CA ca.crt -CAkey ca.key -set_serial 01 -out server.crt -days 365 	//生成crt证书文件

openssl genrsa -out yourdomain.com.key 4096
openssl req -sha512 -new
-subj "/C=CN/ST=Beijing/L=Beijing/O=example/OU=Personal/CN=yourdomain.com"
-key yourdomain.com.key
-out yourdomain.com.csr 																			//生成csr请求文件
openssl x509 -req -sha512 -days 3650
-CA ca.crt -CAkey ca.key -CAcreateserial
-in yourdomain.com.csr
-out yourdomain.com.crt 																			//生成以域名的证书，CRT文件，CN为域名，并且域名作为私钥和公钥名

openssl x509 -in myCA.crt -noout -text  															//查看证书
```

---

### 启用https

```bash
server{
listen 443;
listen 443 ssl;
server_name localhost;
ssl on;
ssl_certificate .crt;
ssl_certificate_key .key;
ssl_session_timeout 5m;
ssl_session_cache shared:SSL:1m;
ssl_protocols SSLv2 SSLv3 TLSv1.2 TLSv1.3;
ssl_ciphers
ssl_prefer_server_ciphers  on;

location / {
	root html;
	index index.html index.htm;
}
}

server { //80跳转443
	listen 80;
	server_name lzxjack.top;
	rewrite ^(.*)$https://$host$1;
	location /{
	
	}
}
```

---

### 普通用户启动Nginx

```bash
setcap 'cap_net_bind_service=+eip' /usr/local/nginx/sbin/nginx
setcap -r nginx 												//清除附加权限

chown -R www-data:www-data /usr/local/nginx/sbin/nginx
chown -R www-data:www-data /var/log/nginx
chmod u+s /usr/local/nginx/sbin/nginx

vi /etc/nginx/ngin.conf
#user## www-data;
```

### 配置虚拟主机

/etc/nginx/sites-available/default

```bash
server {
            listen   80;      							#设置监听地址192.168.72.10
            server_name   localhost;                      
            charset   utf-8;
            #access_log#   logs/www.yuji.access.log;    #设置日志名
            location / {
                   root   /var/www/html/yuji;     		#设置80的工作目录#
                   index   index.html   index.php;
            }
            error_page   500 502 503 504 /50x.html;
            location = 50x.html {
                  root   html;
            }
       }
 
server {
            listen   8080;       						#设置监听地址192.168.72.20#
            server_name   localhost;                      
            charset   utf-8;
            #access_log#   logs/www.nan.access.log;     #设置日志名#
            location / {
                   root   /var/www/html/nan;          	#设置8080的工作目录
                   index   index.html   index.php;
            }
            error_page   500 502 503 504 /50x.html;
            location = 50x.html {
                  root   html;
            }
}
```

### Nginx配置文件

```bash
events{}                                                # 告诉nginx如何处理链接


                                                
http{
       include /etc/nginx/mime.types                    # 引入文件

       upstream back-servers {                          # "upstream"表示负载均衡,"back-servers"为自定义的访问地址名称
              server localhost:3000 weight=2;           # "server"表示可访问的URL地址,"weight"表示权重,数值越大被访问的概率越大
              server localhost:3001 weight=6;
       }

       server{                                          # 服务器
              listen 80;                                # 监听端口
              server_name localhost;                    # 服务器名称
              root /var/www/html;                       # 网站根目录
              index index.html;                         # 指定默认页面
              error_page 404 /404.html;                 # 重写错误页面

              location /app {                          # "/app" 表示访问文件 " /app/ "表示访问的URL;匹配路径中包含 " app " 字符的URL和文件路径

              }

              location ~ /app/index[1-2].html {        # " ~ " 表示启用正则表达式; " = " 表示精准匹配
                     root /var/www/html;
              }

              rewrite /temp /app/index.html;            # 重写路径,访问/temp会转到/app/index.html

              location / {
                     try_files $uri $uri/ =404;
              }

              location /next1 {
                     proxy_pass http://localhost:3000;  # "proxy_pass" 表示反向代理
              }

              location /next2 {
                     proxy_pass http://localhost:3001;
              }

              
       }
}
```

```bash
events{}                                                # 告诉nginx如何处理链接

http{
       include /etc/nginx/mime.types;                   # 引入文件

       upstream localhost {                             # "upstream"表示负载均衡,"localhost"为自定义的访问地址名称
              server localhost:81 weight=1;             # "weight"表示权重,数值越大被访问的概率越大
              server localhost:82 weight=3;
       }

       server{                                          # 服务器
              listen 81;                                # 监听端口
              server_name localhost;                    # 服务器名称
              root /var/www/html/site1;                 # 网站根目录
              index index.html;                         # 指定默认页面

              location / {
                        try_files $uri $uri/ =404;
              }
       }

        server{
                listen 82;
                server_name localhost1;
                root /var/www/html/site2;
                index index.html;

                location / {
                        try_files $uri $uri/ =404;
                }
        }

        server{
                listen 80;
                server_name mylocalhost;

                location / {
                        proxy_pass http://localhost;
                }
        }
}
```