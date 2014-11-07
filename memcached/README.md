memcached 安装
1.下载 memcached libevent
分别将 libevent-1.1a.tar.gz 和 memcached-1.1.12.tar.gz 解开包、编译、安装：
# tar -xzf libevent-1.1a.tar.gz
# cd libevent-1.1a
# ./configure --prefix=/usr
# make
# make install
# cd ..
# tar -xzf memcached-1.1.12.tar.gz
# cd memcached-1.1.12
# ./configure --prefix=/usr
# make
# make install
安装完成之后，memcached 应该在 /usr/bin/memcached。

2.如果是lnmp安装包的可以：
进入lnmp解压后的目录，执行：./memcached.sh
回车确认后就会自动安装memcache php扩展和memcached.

运行 memcached 守护程序
运行 memcached 守护程序很简单，只需一个命令行即可，不需要修改任何配置文件（也没有配置文件给你修改 ）：
/usr/bin/memcached -d -m 128 -l 192.168.1.1 -p 11211 -u httpd参数解释：
-d 以守护程序（daemon）方式运行 memcached；
-m 设置 memcached 可以使用的内存大小，单位为 M；
-l 设置监听的 IP 地址，如果是本机的话，通常可以不设置此参数；
-p 设置监听的端口，默认为 11211，所以也可以不设置此参数；
-u 指定用户，如果当前为 root 的话，需要使用此参数指定用户。
当然，还有其它参数可以用，man memcached 一下就可以看到了。

memcached 的工作原理
首先 memcached 是以守护程序方式运行于一个或多个服务器中，随时接受客户端的连接操作，客户端可以由各种语言编写，目前已知的客户端 API 包括 Perl/PHP/Python/Ruby/Java/C#/C 等等。
PHP 等客户端在与 memcached 服务建立连接之后，接下来的事情就是存取对象了，每个被存取的对象都有一个唯一的标识符 key，存取操作均通过这个 key 进行，保存到 memcached 中的对象实际上是放置内存中的，并不是保存在 cache 文件中的，这也是为什么 memcached 能够如此高效快速的原因。
注意，这些对象并不是持久的，服务停止之后，里边的数据就会丢失。

PHP 如何作为 memcached 客户端
有两种方法可以使 PHP 作为 memcached 客户端，调用 memcached 的服务进行对象存取操作。
第一种，PHP 有一个叫做 memcache 的扩展，Linux 下编译时需要带上 –enable-memcache[=DIR] 选项，Window 下则在 php.ini 中去掉 php_memcache.dll 前边的注释符，使其可用。
除此之外，还有一种方法，可以避开扩展、重新编译所带来的麻烦，那就是直接使用 php-memcached-client。
本文选用第二种方式，虽然效率会比扩展库稍差一些，但问题不大。

PHP memcached 应用示例
首先 下载 memcached-client.php，在下载了 memcached-client.php 之后，就可以通过这个文件中的类“memcached”对 memcached 服务进行操作了。其实代码调用非常简单，主要会用到的方法有 add()、get()、replace() 和 delete()，方法说明如下：
add ($key, $val, $exp = 0)
往 memcached 中写入对象，$key 是对象的唯一标识符，$val 是写入的对象数据，$exp 为过期时间，单位为秒，默认为不限时间；
get ($key)
从 memcached 中获取对象数据，通过对象的唯一标识符 $key 获取；
replace ($key, $value, $exp=0)
使用 $value 替换 memcached 中标识符为 $key 的对象内容，参数与 add() 方法一样，只有 $key 对象存在的情况下才会起作用；
delete ($key, $time = 0)
删除 memcached 中标识符为 $key 的对象，$time 为可选参数，表示删除之前需要等待多长时间。
下面是一段简单的测试代码，代码中对标识符为 'mykey' 的对象数据进行存取操作：

