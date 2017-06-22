函数：
1、被调用的函数不一定在调用位置之前声明，因为php是解析完整个文件后才开始执行

2、在函数内部，无论传入的值是什么类型的变量，都可以一视同仁的使用原型中定义的参数来引用他们的值

3、除非另有所指，否则所有被传入函数或者由函数返回的非对象变量所传递的都是变量的值，
而不是对变量的引用，（在默认情况下，传递对象都是传递引用），这意味着，PHP会复制相应的值，
并提供对该副本的访问和操作，对副本的任何改动都不会影响原先变量中保存的值

4、虽然在PHP中传递引用的速度会更快点，但与传递值相比所差无几

5、函数参数的默认值，必须是常量，例如字符串或者数字，而不能是变量

6、传递引用（&)，如果想把一个变量传递给一个函数，并且希望保留在函数内部对该变量值的修改
[php]
function wrap_html_tag(&$string, $tag = 'b'){
	$string = "<$tag>$string</$tag>";
}

//echo wrap_html_tag(1);	//Fatal error: Only variables can be passed by reference 

$html = 1;
wrap_html_tag($html);
echo $html;		// 粗体 1

[/php]
6、通过给函数传递变量的引用，省去了返回变量值并指定该原始变量的步骤。单需要一个函数返回true或false的布尔值并仍然希望通过函数来修改变量值时，就要使用传递引用的方式。
如果给参数声明为接受变量的引用，就不能再给这个参数传递一个变量字符串或数字值，否则，php会出致命错误

7、创建可以接受个数可变的参数的函数
func_num_args(), func_get_arg();
[php]
// 求平均数函数
function mean(){
	$sum = 0;
	
	$size = func_num_args();
	
	for($i = 0; $i < $size; $i++){
		$sum += func_get_arg($i);
	}
	
	$average = $sum / $size;
	
	return $average;
}


//使用func_get_arg()比传递数组的方法速度更快

[/php]
8、返回变量的引用，可以减少一个变量的副本
返回变量引用的语法与传递变量引用的语法都使用&,不过，返回变量引用不是吧&放在参数前面,
而是把它放在函数名的前面
当调用这个函数时，也必须使用 =&赋值操作符，而不是纯=操作符
从函数返回变量的引用后，在操作该引用时就等于是在操作该变量的原始值
当函数返回引用时，必须返回一个对变量的引用，而不能返回一个字符串
[php]
function &pc_array_find_value($needle, &$haystack){
	foreach($haystack as $key=>$value){
		if($needle == $value){
			return $haystack[$key];
		}
	}
	//return "$needle is not found";	//引用返回一个非变量，会触发警告
}
$minnesota = array('Bob', 'Scott', 'Price', 'Charles');
$prince =& pc_array_find_value('Price', $minnesota);
$prince = '0(+>';
print_r($minnesota);	//Array ( [0] => Bob [1] => Scott [2] => 0(+> [3] => Charles ) 

[/php]
9、跳跃选择返回的值
[php]
function time_parts($time){
	return explode(':', $time);
}
list(, $minute, ) = time_parts('12:34:56');	//34,看上去好像错误，实际是有效的php代码

[/php]
10、想要更加变量的值来调用不同的函数，使用 call_user_func(), call_user_func_array()
[php]
function get_file($filename){
	return file_get_contents($filename);
}
$function = 'get_file';
$filename = 'test.php';

//调用get_file($filename);
$contents = call_user_func($function, $filename);

// 如果函数能够接受不同个数的参数，可以用call_user_func_array()

function put_file($filename, $data){
	return file_put_contents($filename, $data);
}
$function = '';

$action = $_GET['action'];
if($action == 'get'){
	$function = 'get_file';
	$args = array('test.php');
}else if($action == 'put'){
	$function = 'put_file';
	$args = array('test.php', 1111);
}
echo call_user_func_array($function, $args);

//当需要在一个能接受很多参数的函数中调用回调函数时，使用call_user_func_array()就很方便
//可以通过func_get_args()来处理这些参数
//vsprintf()是sprintf()的另一个版本，可以接受一个参数数组

[/php]
11.在函数内部访问全局变量
使用global关键字，或者直接在$GLOBALS全局数组中引用这个变量
在函数内，对global关键字使用unset(),变量只会在函数中清除
要想在全局作用域中清除这些变量，必须对$GLOBALS数组中的相应元素调用unsert()

12.创建动态函数
想要在程序运行中创建并定义一个函数，使用create_function()
第一个参数是一个字符串，包含创建函数的参数
第二个参数是函数体
使用create_function()动态创建函数的速度比较慢，最好还是预先定义函数
在实际中，最频繁使用create_function()的地方是usort()和array_wal()创建自定义排序函数
[php]
$add = create_function('$i, $j', 'return $i+$j;');
echo $add(1,1);	//2

$files = array(2,4,2);
usort($files, create_function('$a, $b', 'return strnatcmp($b, $a);'));
print_r($files);	//Array ( [0] => 4 [1] => 2 [2] => 2 )
[/php]