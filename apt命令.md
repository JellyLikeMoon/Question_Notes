# Debian Ubuntu apt-get apt 命令

## 常用命令
      update      - 重新获取软件包列表  
      upgrade     - 进行更新  
      install     - 安装新的软件包  
      remove      - 移除软件包  
      autoremove  - 自动移除全部不使用的软件包  
      --purge       - 移除软件包和配置文件  
      source      - 下载该包源代码
      build-dep   - 为源码包配置编译依赖  
      dist-upgrade - 升级系统
      dselect-upgrade - 依照 dselect 的选择更新  
      clean       - 清除无用包  
      autoclean   - 清除旧的的已下载的归档文件  
      check       -检验是否有损坏的依赖
## 
      apt-cache depends 了解使用依赖
      apt-cache search  搜索
## 常用选项
      -h 本帮助文件。  
      -q 输出到日志 - 无进展指示  
      -qq 不输出信息，错误除外  
      -d 仅下载 - 不安装或解压归档文件  
      -s 不实际安装。模拟执行命令  
      -y 假定对所有的询问选是，不提示  
      -f 尝试修正系统依赖损坏处  
      -m 如果归档无法定位，尝试继续  
      -u 同时显示更新软件包的列表  
      -b 获取源码包后编译  
      -V 显示详细的版本号 
## apt
      apt list          -列出包
      apt edit-sources  -编辑源列表
      apt search        -搜索包
      apt show          -显示安装细节
      apt full-upgrade  -自动处理依赖
      apt purge         -卸载包及配置文件 
