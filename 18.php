<?php
/*
 安全和加密
 很多web应用程序中的安全问题都是由于轻信第三方提供的数据造成的，比如对于输入数据，在对其进行验证之前都应该将其视为嫌疑数据。如果把嫌疑数据发送给用户浏览器，就有可能导致跨站脚本（XSS）问题。如果把嫌疑数据用于SQL查询，就有可能造成SQL注射问题。
 在使用第三方提供的数据，包括你的用户提供的数据时，首先检验其合法性非常重要。这个过程叫做过滤。
 与安全密切相关的，能够增强你的应用程序安全性的强大手段是加密。
 加密的本质是扰乱数据，某些不可恢复的数据扰乱，称为单向加密或者散列算法。另一种双向加密方式既能对数据加密，而且也能对加密后的数据进行解密。
 php提供了通过加密来保障数据安全的很多工具，如md5()函数，属于PHP的基本函数。而其他一些扩展工具（如mcrypt，mhash和cRUL）则需要在PHP编译时明确包含进来。
 mcrypt是一种功能更全的加密库，它提供了多种不同的算法和加密模式，它支持多种不同的加密方式，特别适合与其他系统或者非PHP程序加密数据。
 虽然PHP为我们提供了对数据进行有些加密的各种工具，但加密只不过是安全蓝图的一个环节而已。加密的数据可以通过秘钥（key）进行解密，所以包含秘钥非常重要。如果非授权用户能够访问到你的秘钥（比如，秘钥保存在WEB服务器能够访问的文件或者其他用户能够访问的共享主机环境中），那么不论你选择的加密算法有多可靠，你的数据同样面临安全问题的威胁。
 对于敏感的数据，不仅需要在服务器端提供保护，而且当在服务器与用户之间传送数据时也需要保护。通过常规的HTTP发送数据对于处在你的服务器和用户之间网络中任何一个节点的任何人来说都是可见的，可以通过SSL（安全套接字协议层）避免网络窃取程序注意到你所传送的数据。要全面了解有关PHP程序安全问题的内容，可以阅读Chris Shiflett著的《PHP applications》
 
 18.1 预防Session定制
 
 问题：你想确保用户的session标示符不会由第三方提供，例如劫持了用户session的攻击者
 
 方案：
 只要用户的授权范围改变，如登陆成功后，就通过session_regenerate_id()来重新生成session标示符
 [php]
 session_regenerate_id();
 $_SESSION['logged_in'] = true;
 [/php]
 通过session实现在不同请求之间的会话状态持续，为了保证session有效，用户的每一次请求都必须包含一个能够唯一标示一次会话的session标示符。
 在默认情况下，PHP可以接受来自cookie或URL中session标示符。攻击者啃了个会欺骗受害人点击一个包含session标示符并指向你应用程序的链接：
 [php]
 <a href="http://example.org/login.php?PHPSESSID=1234">Click Here!</a>
 [/php]
 点击了改链接的用户，其session标示符会被重置为1234.因此，攻击者在知道了这个用户的session标示符后，就可以通过使用相同的session标示符来尝试劫持用户的会话。
 因此，通过确保只要改变用户的授权范围就重新生成session标示符，可以有限地清除session定制攻击。由于PHP会自动更新存储的session数据并传送新的session标示符，所以必须在适当的时候调用这个函数
 
 
 18.2 防止表单提交骗术
 问题：你想确保表单的提交是合法的也是有意识的。
 方案：
 向表单中添加一次性记号，并见记号保存在用户的session中
 [php]
 <?php
 session_start();
 $_SESSION['token'] = md5(uniqid(mt_rand(),true));
 ?>
 <form action="buy.php" method="POST">
 <input type="hidden" name="token" value="<php echo $_SESSION['token'];?>" />
 <input type="submit" value="Buy stocks" />
 </form>
 [/php]
 当你得到提交表单的请求时，检查该记号并确保匹配：
 [php]
 <?php
 session_start();
 if($_POST['token'] != $_SESSION['token'] || !isset($_SESSION['token'])){
	//提示用户输入密码
}else{
	//继续
}
?>
这种技术会防止一组被称为伪造跨站点请求(CSRF,Cross-site request forgeries)的攻击。这些攻击都是在受骗者不知情的情况下，让受骗者向某个目标站点发送请求来达到攻击目的。通常，受骗者都拥有对目标站点一定程度的授权，所以这些攻击能够实现攻击者以其他方式无法实施的动作。

18.3确保过滤输入
问题：你需要确保在使用所有输入数据之前先进行过滤
方案：
初始化一个空苏州用来保存过滤后的数据。在验证输入有效后，将输入保存在这个数组中：
[php]
<?php
//初始化一个用于保存过滤后数据的数组
$clean = array();

//允许名字中包含字母
if(ctype_alpha($_POST['name'])){
	$clean['name'] = $_POST['name'];
}else{
	//错误
}
[/php]
通过使用严格的命名约定，可以更容易的保持过滤后数据的合法性。而始终都使用初始化的空数组$clean,确保了数据无法被注射到数组中--因为只有你才能明确的添加数组元素

18.6 将密码置于站点文件外部
问题：你需要使用密码连接到一个数据库，但不想把这个密码放在你使用的站点中的PHP文件里，以防止由于那些文件暴露而丢失密码

方案：
将密码保存在web服务器启动时加载的某个文件中的环境变量中，然后，只需要在代码中引用那个环境变量即可
[php]
<?php
mysql_connect('localhost', $_SERVER['DB_USER'], $_SERVER['DB_PASSWORD']);
[/php]
虽然这样从页面的代码中移走了密码，但却把密码放到了其他需要保护的地方。最重要的是，要保证能被公众查看的页面不会调用phpinfo（）函数。因为phpinfo()会显示所以的环境变量，同意也不能以其他函数暴露$_SERVER,比如print_f()函数。
还有，特别是当你使用共享主机时，要保证环境变量设置只对你的虚拟主机才有效，而不是对所有用户都有效。
在使用Apache服务器时，可以通过主配置文件分离的文件中设置相应的变量做到这一点：
setEnv DB_USER "susannah"
setEnv DB_PASSWORD "y23"
在主配置文件中(httpd.conf)中针对你站点的<VirtualHost>指令，像下面这样包含上面那个单独的文件:
Include "usr/local/apache/database-passwords"
要保证这个包含密码的独立文件不会被除了管理虚拟主机之外的任何用户读到。

18.9使用散列码验证数据
问题：你想要保证用户不会修改你通过cookie发给他们或者放在表单元素中的数据。
方案：
在发送或者设置这些数据的同时，也发送并设置使用salt对这些数据进行MD5处理后的散列码。但接收到返回的数据时，再以相同的salt计算收到数据的MD5散列码。如果两者不匹配，则说明用户修改了数据。
[php]
<?php
	//定义salt
	define('SALT', 'flying');
	$id = 1337;
	$idcheck = md5(SALT . $id);
?>
<input type="hidden" name="id" value="<?php echo $id; ?> />
<input type="hidden" name="idcheck" value="<php echo $idcheck; ?> />
[/php]
接收数据,验证隐藏字段中的数据
[php]
<?php
	//初始化一个保存过来数据的数组
	$clean = array();
	
	//定义salt
	define('SALT', 'flying');
	
	if(md5(SALT . $_POST['id']) == $_POST['idcheck']){
		$clean['id'] = $_POST['id'];
	}else{
		//error
	}
[/php]
在处理提交的表单数据时，以相同的salt计算$_POST['id']值的MD5散列码。如果计算结果匹配，说明$_POST['id']没有被修改

在为cookie添加MD5散列码时，可以使用implode()函数
[php]
<?php
	//定义salt
	define('SALT', 'flying');
	
	$name = 'Ellen';
	$namecheck = md5(SALT . $name);
	
	setcookie('name', implide('|', array($name, $namecheck)));
[/php]
在解析cookie值中的散列码时使用explide();
[php]
<?php
	//定义salt
	define('SALT', 'flying');
	
	list($cookie_value, $cookie_check) = explode('|', $_COOKIE['name'], 2);
	
	if(md5(SALT . $cookie_value) == $cookie_check){
		$clean['name'] = $cookie_value;
	}else{
		//error
	}
[/php]
在表单或者cookie使用散列码对数据验证，明显依赖与salt，如果恶意用户发现了你的salt，那么散列码就失去了保护作用。频繁的更换salt也是不错的方案。为了额外增加一层保护，可以使用不同的salt，即基于某些$id属性值（如通过$id%10选择十个不同单词)选择一个特殊的salt用于计算散列码。
如果安装了mhash模式，也可以不局限与使用MD5散列码。mhash支持许多不同的hash算法