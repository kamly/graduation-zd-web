# 毕设 PC 前后端

## 生产环境

- ubuntu 16.06 PHP 7.0.16 MYSQL 5.7 REDIS 4.0.0 
- elasticsearch kibana filebeat logstash
- supervisor composer
- laravel 5.4.36

搭建lnmp环境[参考](https://github.com/kamly/automated-operation)

## 介绍

1. 基于 laravel 5.4.36 构建前台和后台
2. 模板渲染， 权限判断， 邮件发送
3. 使用 redis消息队列， elasticsearch搜索引擎， des,aes加密方式
4. 模型查询
5. 自定义命令行


## 表结构

## 结构目录

~~~
.
├── app
│   ├── Admin
│   │   └── Controllers
│   ├── Console
│   │   ├── Commands
│   │   └── Kernel.php
│   ├── Exceptions
│   │   └── Handler.php
│   ├── Helpers
│   │   └── commons.php
│   ├── Http
│   │   ├── Controllers
│   │   ├── Kernel.php
│   │   └── Middleware
│   ├── Jobs
│   │   ├── ElasticsearchTodo.php
│   │   ├── ElasticsearchTodoJob.php
│   │   └── SendNoticeMessage.php
│   ├── Models
│   │   ├── AdminPermission.php
│   │   ├── AdminRole.php
│   │   ├── AdminUser.php
│   │   ├── Base.php
│   │   ├── Comment.php
│   │   ├── Fan.php
│   │   ├── Image.php
│   │   ├── Notice.php
│   │   ├── PasswordReset.php
│   │   ├── Post.php
│   │   ├── PostTopic.php
│   │   ├── Topic.php
│   │   ├── User.php
│   │   └── Zan.php
│   ├── Policies
│   │   └── PostPolicy.php
│   ├── Providers
│   │   ├── AppServiceProvider.php
│   │   ├── AuthServiceProvider.php
│   │   ├── BroadcastServiceProvider.php
│   │   ├── EventServiceProvider.php
│   │   ├── HashEloquentUserProvider.php
│   │   └── RouteServiceProvider.php
│   └── libraries
├── artisan
├── bootstrap
├── composer.json
├── composer.lock
├── config
│   ├── app.php
│   ├── auth.php
│   ├── broadcasting.php
│   ├── cache.php
│   ├── database.php
│   ├── filesystems.php
│   ├── mail.php
│   ├── myConfig.php
│   ├── queue.php
│   ├── services.php
│   ├── session.php
│   └── view.php
├── database
├── package.json
├── phpunit.xml
├── public
│   ├── adminlte
│   ├── css
│   ├── favicon.ico
│   ├── fonts
│   ├── index.php
│   ├── js
│   ├── robots.txt
│   └── web.config
├── readme.md
├── resources
│   ├── assets
│   │   ├── js
│   │   └── sass
│   ├── lang
│   │   └── en
│   └── views
│       ├── admin
│       ├── emails
│       ├── forget
│       ├── layout
│       ├── login
│       ├── notice
│       ├── post
│       ├── register
│       ├── topic
│       ├── user
│       └── welcome.blade.php
├── routes
│   ├── admin.php
│   ├── api.php
│   ├── channels.php
│   ├── console.php
│   └── web.php
├── server.php
├── storage
├── tests
├── vendor
└── webpack.mix.js
~~~

## 程序启动

> 前提，配置lnmp环境，elkf，域名解析，ssl证书，composer

```shell
cd /data/www
git clone https://github.com/kamly/graduation-zd-app.git
cd graduation-zd-app

vim .env

composer install

chown www:www -R storage
chown www:www -R bootstrap/cache

php artisan queue:work

php artisan SupperRoot:init

curl https://xxxxxx/login
```
