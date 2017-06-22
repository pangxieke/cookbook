[php]
<?php
header("Content-type:text/html;charset=utf-8");
function is_valid_credit_card($s){
	//删除非数字并反序排列
	$s = strrev(preg_replace('/[^\d]/', '', $s));
	
	//计算检测结果
	$sum = 0;
	$j = strlen($s);
	for($i = 0; $i < $j; $i++){
		//偶数位原封不动
		if(($i%2) == 0 ){
			$val = $s[$i];
		}else{
			//奇数位乘以2，如大于9再减9
			$val = $s[$i] * 2;
			if($val > 9){
				$val -= 9;
			}
		}
		$sum += $val;
		
	}	
		//如果和是10的倍数，则号码有效
	return (($sum % 10) == 0);
	
}
$_POST['credit_card'] = '4111 1111 1111 1111';
if(! is_valid_credit_card($_POST['credit_card'])){
	echo 'Sorry, that card number is invalid.';
}else{
	echo 'That card number is valid.';
}
?>
[/php]
为了避免意外差错，信用卡使用了Luhn算法，也就是上面上面函数中的算法。
分别对信用卡号码的每一位数字进行处理，然后会得到卡号是否有效的结论。

如果是语义层面的验证，就需要更多的技巧，如"4111 1111 1111 1111"这样的信用卡号虽然能够轻易通过上函数的验证，
但它却是无效的，它是一个众所周知的类似Visa卡号的测试号码。
完整的信用卡验证少不了外部验证这一环，也就是说，需要将卡号和账户信息，提交给付款机，并确认认可