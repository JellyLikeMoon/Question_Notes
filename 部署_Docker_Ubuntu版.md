---
title: Ubuntu22.04_Docker_install
date: 2024-03-05T23:15:03Z
lastmod: 2024-04-09T23:15:06Z
---

### Docker官网

> [https://www.docker.com/](https://www.docker.com/)

### Docker安装文档

> [https://docs.docker.com/engine/install/ubuntu/](https://docs.docker.com/engine/install/ubuntu/ "docker")

### Docker-Compose安装文档

> [https://docs.docker.com/compose/install/linux/](https://docs.docker.com/compose/install/linux/)

### 安装脚本

```bash
curl -fsSL https://get.docker.com | bash -s docker --mirror Aliyun && sudo systemctl enable docker && sudo systemctl start docker && docker version

touch /etc/docker/daemon.json
echo "{\"registry-mirrors\": [\"https:\/\/docker.mirrors.ustc.edu.cn\"]}" > /etc/docker/daemon.json

sudo systemctl daemon-reload
sudo systemctl restart docker

sudo docker run hello-world
```

### Docker镜像源

​`/etc/docker/daemon.json`​

```bash
https://mirror.baidubce.com
https://docker.mirrors.sjtug.sjtu.edu.cn
https://docker.nju.edu.cn
https://docker.mirrors.ustc.edu.cn
```

### 安装docker-compose

```bash
sudo apt-get update
sudo apt-get install docker-compose-plugin					# docker-compose以docker插件的方式安装
sudo apt install docker-compose								# docker-compose以apt软件包的方式安装
```
