# CentOS7搭建KMS服务器
1.解压

    tar -zxvf binaries.tar.gz
    mv ./binaries/Linux/intel/static/vlmcsd-x64-musl-static /usr/kms/vlmcsd
3.配置为服务

    vi /usr/lib/systemd/system/kms.service
内容如下：

    [Unit]
    Description=vlmcsd - service
    After=network.target

    [Service]
    Type=forking
    ExecStart=/usr/kms/vlmcsd
    ExecReload=/bin/kill -s HUP $MAINPID
    ExecStop=/bin/kill -s QUIT $MAINPID

    [Install]
    WantedBy=multi-user.target
重载生效

    systemctl daemon-reload
5.常用命令

    systemctl start kms
    systemctl enable kms
    systemctl disable kms
6.查看是否运行

    netstat -lnp | grep 1688
7.开启防火墙

    systemctl start firewalld
    firewall-cmd --list-services
    firewall-cmd --zone=public --list-ports
    firewall-cmd --reload
    firewall-cmd --zone=public --add-port=80/tcp --permanent
    firewall-cmd --zone= public --remove-port=80/tcp --permanent
    
所用软件来自https://github.com/Wind4/vlmcsd
