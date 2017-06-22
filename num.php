检查变量中是否包含一个有效的数字 is_numeric
[php]
var_dump(is_numeric(5));			//true
var_dump(is_numeric('5'));			//true
var_dump(is_numeric("05"));			//true
var_dump(is_numeric('five'));		//false
var_dump(is_numeric(0xDECAFBAD));	//true
var_dump(is_numerIc("10e200"));		//true
var_dump(is_numeric('5.1'));		//true
var_dump(is_numeric('5,000'));		//false	

[/php]

对于含有千位分隔符的数字5,000这个函数返回false，所以必须在调用is_numeric()函数之前先用str_replace()函数替换千位分隔符

比较浮点型数字

浮点型数字在计算机中以二进制表示时，只用有限的位数保留尾数和指数。当超出既定的位数时，就会发生溢出
要检查2个数相等，要确保2个数的浮动范围在一个非常小的范围，然后用abs()取绝对值
[php]
$delta = 0.00001;
$a = 1.00000001;
$b = 1.00000000;
if(abs($a-$b) < $delta){
	echo 'same';
	//$a 和 $b 相等
}

[/php]

取整，或者带若干小数位的数
[php]
echo round(2.6);	//2	四舍五入取整
echo ceil(2.4);		//3	向上取整
echo floor(2.4);	//2	向下取整

[/php]
range — 建立一个包含指定范围单元的数组 
mt_rand()	随机数
[php]

//有偏随机数
function pc_rand_weighted($numbers){
	$total = 0;
	foreach($numbers as $number => $weight){
		$total += $weight;
		$distribution[$number] = $total;
	}
	
	$rand = mt_rand(0, $total - 1);
	
	foreach($distribution as $number => $weights){
		if($rand < $weights){
			return $number;
		}
	}
	
}
$ads = array(
	'ford' => 12234,
	'att'  => 33424,
	'ibm'  => 16823
);
var_dump(pc_rand_weighted($ads));

// 取对数log(), log10(),只针对大于0的数而设计，如不是，返回NAN
var_dump(log(-1));	//float(NAN) 
var_dump(log('-1'));//float(NAN) 

// 计算某数的e次幂，exp()
// 计算任何次幂，pow()
// 内置常量M_E是一个近似e的值

// 格式化数字 number_format()
// localeconv() 本地化数据，也包含数字格式化信息

//setlocale(LC_ALL, 'zh_CN');
echo '<pre>';
print_r(localeconv());


//格式化货币值 money_format() 这个函数使用Unix底层的strfmon()系统函数，所以对windows系统无效

//需要处理PHP内置的极大或极小的浮点型数，使用BCMatch或者GMP库

// 不同进制之间转换 base_convert()

[/php]
非十进制数的计算
[php]
for($i = 0x1; $i < 0x10; $i++){
	echo "$i\n";			//默认是以10进制输出
	echo dechex($i)."\n";	//以16进制输出
}

[/php]
web安全色的特别之处在于，RR, GG, BB 都必须是(00, 33, 66, 99, CC, FF)之一
[php]
for($rr = 0; $rr <= 0xFF; $rr += 0x33){
	for($gg = 0; $gg <= 0xFF; $gg += 0x33){
		for($bb = 0; $bb <= 0xFF; $bb += 0x33){
			printf("%02X%02X%02X\n", $rr, $gg, $bb);
		}
	}
}

[/php]
计算球面坐标2点之间的距离
[php]
// 第五个参数为球面半径
function pc_sphere_distance($lat1, $lon1, $lat2, $lon2, $radius = 6378.135){
	$rad = doubleval(M_PI/180.0);
	
	$lat1 = doubleval($lat1)*$rad;
	$lon1 = doubleval($lon1)*$rad;
	$lat2 = doubleval($lat2)*$rad;
	$lon2 = doubleval($lon2)*$rad;
	
	$theta = $lon2 - $lon1;
	$dist = acos(sin($lat1)*sin($lat2) + cos($lat1)*cos($lat2)*cos($theta));
	if($dist < 0){
		$dist += M_PI;
	}
	
	return $dist = $dist * $radius;	//default is earth equatorial radius in kilometers
}
echo printf("%0.2f", pc_sphere_distance(40.858704, -73.928532, 37.758434, -122.435126)); //4138.787
// 地球不是严格的球体，误差上限 0.5%