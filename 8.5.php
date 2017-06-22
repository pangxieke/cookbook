问题：想基于用户所用浏览器的能力生成相应的内容
方案：更加get_browser()返回的对象来判断浏览器的能力

get_browser() 函数返回用户浏览器的性能。
该函数通过查阅用户的 browscap.ini 文件，来测定用户浏览器的性能。
若成功，则该函数返回包含用户浏览器信息的一个对象或一个数组，若失败，则返回 false。

get_browser(user_agent,return_array)
user_agent：可选。规定 HTTP 用户代理的名称。默认是 $HTTP_USER_AGENT 的值。
您可以通过设置 NULL 绕过该参数。
return_array：可选。如果该参数设置为 true，本函数会返回一个数组而不是对象。
[php]
<?php
echo $_SERVER['HTTP_USER_AGENT'] . "<br /><br />";
$browser = get_browser(null,true);
print_r($browser);
?> 
[/php]

get_browser()函数会检测环境变量(由web服务器设定)并将其与外部的浏览器能力进行比较。
PHP没有自带浏览器能力文件，browscap.ini这个文件，
所以直接使用这个函数会有错误提示，需要在<a href="http://browsers.garykeith.com/downloads.asp" style="color: rgb(121, 121, 121); text-decoration: none; ">http://browsers.garykeith.com/downloads.asp</a>下载php_browscap.ini，然后在 php.ini 中指定php_browscap.ini的绝对路径，盘符可以省略

在php.ini中设置browscap指令
browscap=/usr/local/lib/php_browscap.ini

get_browser对于javascript或cookie之类的用户可以配置的能力，get_browser()只能告诉浏览器是否支持这些功能，
而不会告诉你用户是否关闭了该功能

platform	浏览器运算的操作系统
version		浏览器的完整版本号
majorver	浏览器的主版本号
minorver	浏览器的次要版本号
frames		浏览器支持框架，为1
tables		浏览器支持表格，为1
cookier		浏览器支持cookie，为1


国外有个叫<a href="http://www.mavrick.id.au/" marget="_black">mavrick</a>的网站，上面有关于浏览器的项目，一直更新所写的Browser 类，我最新看到的这个类可以获取包括iPhone、BlackBerry、win、mac、linux、OS、BeOS等平台上的浏览器信息，功能可以说是十分强大