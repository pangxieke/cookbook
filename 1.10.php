<?php
header("Content-type:text/html;charset=utf-8");
$sales = array(
	array('Northeast', '2005-01-01', 12.54),
	array('Northwast', '2005-01-01', 12.54),
	array('Southeast', '2005-01-01', 12.54),
	array('All/reguibs', '2005-01-01', 12.54),
);
$fh = fopen('sales.csv', 'w') or die("Can't open sales.csv");
foreach($sales as $sales_line){
	if(fputcsv($fh, $sales_line) === false){
		die("Can't write CSV line");
	}
}

fclose($fh) or die("Can't close sales.csv");
?>
[/php]

如果想输出CSV格式的数据而不是将其写入到一个文件中，可以使用特殊的输出流 php://output
[php]
<?php
$sales = array(
	array('Northeast', '2005-01-01', 12.54),
	array('Northwast', '2005-01-01', 12.54),
	array('Southeast', '2005-01-01', 12.54),
	array('All/reguibs', '2005-01-01', 12.54),
);
$fh = fopen('php://output', 'w');
foreach($sales as $sales_line){
	if(fputcsv($fh, $sales_line) === false){
		die("Can't write CSV line");
	}
}
fclose($fh);
?>
[/php]

可以把csv格式的数据放到一个字符串中，而不是输出或写如到文件

[php]
<?php
$sales = array(
	array('Northeast', '2005-01-01', 12.54),
	array('Northwast', '2005-01-01', 12.54),
	array('Southeast', '2005-01-01', 12.54),
	array('All/reguibs', '2005-01-01', 12.54),
);
ob_start();
$fh = fopen('php://output', 'w') or die("Can't open php://output");
foreach($sales as $sales_line){
	if(fputcsv($fh, $sales_line) === false){
		die("Can't write CSV line");
	}
}
fclose($fh);
$output = ob_get_contents();
ob_end_clean();
//echo $output;
?>
[/php]

解析逗号分隔的数据
[php]
<?php
$fp = fopen('sales.csv', 'r') or die("can't open file");
echo "<table>\n";
while($csv_line = fgetcsv($fp)){
	echo '<tr>';
	$j = count($csv_line);
	for($i = 0; $i < $j; $i++){
		echo '<td>' . htmlentities($csv_line[$i]) . '</td>';
	}
	echo '</tr>';
}
echo "<table>\n";
fclose($fp);
?>
[/php]
fgetcsv()第二个参数，如果不指定这个参数，会读取一整行数据。但平均的行长度超过8192字节时，
如果你指定一个明确的行长度，而不是让PHP自己去计算的话，那么程序运行速度会加快
fgetcsv()第三个参数，这个参数可以代替逗号作为数据的分隔符。
不要试图绕过fgetcsv()函数，只想读取一行然后使用explode()按照逗号进行解析。CSV的实际情况要比这种函数能够处理的格式更复杂，必然说某些字段中包含逗号直接量时，不应该将字段中包含的逗号当成hi字段的分隔符。使用fgetcsv()可以抱着你的代码避免这些不明显的错误