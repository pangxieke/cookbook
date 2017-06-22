问题：想直接访问post请求的主体，而不仅是使用PHP解析之后放在$_POST中的数据
例如，想处理一个web服务请求发送过来的xml文档

方案：从php://input流中读取

[php]
<?php
	$body = file_get_contents('php//input');
?>
[/php]
如果只需要访问提交的表单变量，使用自动全局数组$_POST会更合适，
如果想得到一个更大的请求主体，可以通过fread()为块为单位进行读取

如果配置指令always_populate_raw_post_data设置为on，
那么原始的post数据也会被保存到全局变量$HTTP_RAW_POST_DATA中

php://input流的文档 http://cn2.php.net/wrappers