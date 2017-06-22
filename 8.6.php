问题：
	想要构造一个在查询字符串中包含名/值对的链接
	
方案:
	使用http_build_query()函数
	
[php]
<?php
$vars = array(
	'name' => 'Oscar the Grouch',
	'color' => 'green',
	'favorite_punctuation' => '#'
);

$query_string = http_build_query($vars);
$url = 'muppet/select.php?' . $query_string;

// muppet/select.php?name=Oscar+the+Grouch&color=green&favorite_punctuation=%23
echo $url;
	
?>
[/php]
	在其查询字符串中，空格被编码为"+",像"#"这样的特殊字符也按十六进制编码为"%23"
	
	虽然http_build_query()通过自动编码的确能够防止变量名或值中的特殊字符破坏url，
	但如果变量名是以HTML实体作为开头，那么问题仍然不可避免。
	
	考虑下面这个url片段
	/stereo.php?speakers=12&cdplayer=5&amp=10
	&符号的HTML实体是&amp;,所以浏览器可能会把这段url解析为
	/stereo.php?speakers=12&cdplayer=52&=10
	
	为了避免嵌入的实体破坏rul，我们有三种选择
	
	第一种，选择不会与实体混淆的变量名，如_amp而不是amp
	第二种，在输出url之前把带有HTML实体等价物的字符串转换为相应的实体，使用htmlentities()
	[php]
<?php
$url = 'muppet/select.php?' . htmlentities($query_string);
//muppet/select.php?name=Oscar+the+Grouch&amp;color=green&amp;favorite_punctuation=%23
echo $url;
?>
	[/php]
	
	第三种，通过arg_separator.input配置指令设置为&amp;来把分隔参数的符号由&改为&amp;
	设置改指令后，http_build_query()函数就会用&amp;来组合不同的name=value形式的参数了