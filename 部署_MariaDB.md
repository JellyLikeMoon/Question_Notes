---
title: 部署_MariaDB
date: 2024-02-21T08:54:07Z
lastmod: 2024-03-08T12:09:08Z
---

# MariaDB安装

### 查看软件仓库

> [https://mariadb.org/download/?t=repo-config](https://mariadb.org/download/?t=repo-config)

### 配置软件源

```bash
sudo apt-get install apt-transport-https curl
sudo curl -o /etc/apt/trusted.gpg.d/mariadb_release_signing_key.asc 'https://mariadb.org/mariadb_release_signing_key.asc'
sudo sh -c "echo 'deb https://mirrors.aliyun.com/mariadb/repo/10.9/ubuntu jammy main' >>/etc/apt/sources.list"
```

### 安装MariaDB

```bash
sudo apt-get update
sudo apt-get install mariadb-server
```

### sources.list

```bash
## MariaDB 10.9 repository list - created 2022-12-18 07:52 UTC
## https://mariadb.org/download/
deb https://mirrors.aliyun.com/mariadb/repo/10.9/ubuntu jammy main
## deb-src https://mirrors.aliyun.com/mariadb/repo/10.9/ubuntu jammy main
deb https://mirrors.aliyun.com/mariadb/repo/10.9/ubuntu jammy main/debug'
```
