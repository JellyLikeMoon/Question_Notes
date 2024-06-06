---
title: Ubuntu22.04_网络配置
date: 2024-03-04T23:37:20Z
lastmod: 2024-03-04T23:57:25Z
---

### 配置文件目录

/etc/netplan/00-install-config.yaml

```bash
network:
  renderer: networkd
  ethernets:
    ens33:
      addresses:
        - 192.168.1.247/24
      nameservers:
        addresses: [4.2.2.2, 8.8.8.8]
      routes:
        - to: default
          via: 192.168.1.1
	    dhcp4: false
  version: 2
```

### 重启服务

```bash
sudo netplan apply
```

### 查看网卡IP

```bash
ip addr show ens33
```

### 查看默认路由

```bash
ip route show
```

‍

‍

‍

‍

‍
