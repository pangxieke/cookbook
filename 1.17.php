<?php
	/*
		通过结合使用header()函数来改变在PHP程序中以fputcsv()函数输出的数据格式的内容类型（content type），
		可以实现将csv文件发送给浏览器的功能。浏览器接收到csv文件后，自动调用软件进行处理
	
	*/
	
	//可下载的csv文件
	// require_once 'DB.php';
	// $db = DB::connect('mysql://david:haxor@localhost/phpcookbook');
	// $sales_data = $db->getAll('SELECT region, start, end, amount FROM sales');
	
	//模拟数据库查询出来的数据
	$sales_data = array(
		array(
			'region' => 'region1',
			'start' => date('Y-m-d'),
			'end' => date('Y-m-d'),
			'amout' => 2,
		),
	
		array(
			'region' => 'region2',
			'start' => date('Y-m-d'),
			'end' => date('Y-m-d'),
			'amout' => 3,
		),
		
		array(
			'region' => 'region3',
			'start' => date('Y-m-d'),
			'end' => date('Y-m-d'),
			'amout' => 4,
		)
	
	);
	
	//为fputcsv()函数打开文件句柄
	$output = fopen('php://output', 'w') or die("Can't open php://output");
	$total = 0;
	
	header('Content-Type: application/csv');
	header('Content-Disposition: attachment; filename="sales.csv"');
	
	//输出表头
	fputcsv($output, array('Region', 'Start Date', 'End Date', 'Amount'));
	//输出每一行数据，并递增$total
	foreach( $sales_data as $sales_line ){
		fputcsv($output, $sales_line);
		$total += $sales_line['amout'];
	}
	//输出全部数据行，并关闭文件句柄
	fputcsv($output, array('ALL Regions', '--', '--', $total));
	fclose($output) or die("Can't close php://output");
	
	
	//如果想为同一数据提供不同的查看方式，可以在一个页面中组合使用格式化代码，并通过一个查询变了来决定生成何种格式
	
	$sales_data = array(
		array(
			'region' => 'region1',
			'start' => date('Y-m-d'),
			'end' => date('Y-m-d'),
			'amout' => 2,
		),
	
		array(
			'region' => 'region2',
			'start' => date('Y-m-d'),
			'end' => date('Y-m-d'),
			'amout' => 3,
		),
		
		array(
			'region' => 'region3',
			'start' => date('Y-m-d'),
			'end' => date('Y-m-d'),
			'amout' => 4,
		)
	
	);
	$total = 0;
	$column_headers = array('Region', 'Start Date', 'End Date', 'Amount');
	$format = $_GET['format'] == 'csv' ? : 'html';
	
	if($format == 'csv'){
		$output = fopen('php://output', 'w') or die("Can't open php://output");
		header('Content-Type: application/csv');
		header('Content-Disposition: attachment; filename="sales.csv"');
		fputcsv($output, $column_headers);
	}else{
		echo '<table><tr><th>';
		echo implode('</th><th>', $column_headers);
		echo '</th></tr>';
	}
	foreach( $sales_data as $sales_line ){
		if($format == 'csv'){
			fputcsv($output, $sales_line);
		}else{
			echo '<tr><td>' . implode('</td><td>', $sales_line) . '</td></tr>';
		}
		
		$total += $sales_line['amout'];
	}
	
	$total_line = array('All Regions', '--', '--', $total);
	
	if($format == 'csv'){
		fputcsv($output, $total_line);
		fclose($output) or die("Can't close php://output");
	}else{
		echo '<tr><td>' . implode('</td><td>', $total_line) . '</td></tr>';
		echo '</table>';
	}