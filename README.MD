# 合同后端

部署方式为部署在阿里云服务器上

使用的阿里云服务器为，centos7的Linux服务器，上面已经把项目部署并运行了

## 数据库

服务器上安装有mysql数据库，要查看数据库内容需要使用navicat连接数据库，查看contract_test数据库

## 架构

项目架构为controller，service和model三层

controlle层，通过url被访问，controller访问service

注意在与数据库交互时，参数要写对

### model层

注意tableName的格式{{%name}}

注意rule函数里要写清楚表属性的各个内容