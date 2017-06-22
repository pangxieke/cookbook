<?php
/**
 字符串：
 PHP中字符串指的是字符的序列。PHP字符串是二进制安全的（例如，字符串中可以包含空字节），
 而且可以随意加长或者缩短。对字符串大小的唯一限制就是PHP可用的内存数量。
 
 警告：通常情况下，PHP字符串是ASCII字符串，对于像UTF-8等字符编码这样一些非ASCII数据，
 则必须做一些额外的工作。
 
 PHP字符串可以通过三种方式来初始化
 1.单引号
 2.双引号
 3.heredoc形式（”here document")
 
 单引号字符串中，字符串中需要转译的特殊字符只有反斜杠和单引号本身
 因为PHP不会检查单引号字符串中的插入变量及任何转义序列，所有用这种方式定义字符串不仅直观而且速度快
 
 */
 print 'I have gone to te store.';	//输出 I have gone to te store.
 print 'I\'ve gone to the store.';	//输出 I've gone to the store.
 //输出 Would you pay $1.75 for 8 ounces of tap water?
 print 'Would you pay $1.75 for 8 ounces of tap water?';
 //输出 In double-quoted strings, newline is represented by \n
 print 'In double-quoted strings, newline is represented by \n';
 
 /**
 双引号虽然不能识别转义的单引号，但是能够识别插入的变量和下标中的转义序列
 
 转义序列	字符
 \n			换行符（ASCII码 10）
 \r			回车符（ASCII码 13）
 \t			制表符（ASCII码 9）
 \\			反斜杠
 \$			美元符号
 \"			双引号
 \0至\777	八进制数值
 \x0至\xFF	十六进制数值
 
 */
 echo '<br>';
 print "I've gone to the store.";	//I've gone to the store.
 print "The sauce cost \$10.25.";	//The sauce cost $10.25.
 $cost = '$10.25';
 print "The sauce cost $cost.";		//同上
 print "The sauce cost \$\061\060.\x32\x35.";//同上
 
 /**
 最后一行，在ASCII字符集中
 字符1，用十进制49或八进制061表示
 字符0，用十进制48或八进制060表示
 字符2，用十进制50或十六进制32表示
 字符5，用十进制53或十六进制35表示
 
 
 由heredoc定义的字符串可以识别所有的插入变量以及双引号字符串能够识别的转义序列，
 却不要求对双引号进行转义
 Heredoc以符号<<<加一个记号(例如END)（不能使用空行或者带有空格后缀）来定义字符串的开始
 并以该记号后跟一个很好（如必要的话）来表示字符串的结尾，以结束heredoc定义
 
 */
 echo '<br>';
 // END标记后不能有空格等,也不能有注释，其前面可以有空格
 print <<<    END
 It's funny when signs say things like:
	Original "Root" Beer
	"Free" Gift
	Shoes cleaned while "you" wait
 or have other misquoted words.
END;
//END结束标记，前面不能有空格，必须顶格写，后面也不能有任何东西.
//；后必须换行，及时后面没有东西，
//区分大小写，通常全部大写

//另外一种写法
$html = <<< END
	"hello world"
END
	. ' good boye';
	echo $html;
//表达式要延续到下一行，不需要使用分号，，但要注意的是，为了让PHP识别出字符串结尾的标示符，
//后面的字符串连接符，必须放在下一行

/*
 字符串中的个别位置上的字符可以通过方括号([])来引用，字符串中第一个字符的索引号为0
 */
 $neighbor = 'Hilda';
 print $neighbor [3];		//d
 
/*
 也可以通过大括号来表示 $neighbor{3},使用大括号能直观的分辨出为字符串索引，而不是数组索引
 《PHP经典案例》1.0概述
 */
