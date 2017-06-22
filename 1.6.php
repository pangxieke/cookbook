[php]
<?php
//把制表符转换为空格符
function pc_tab_expand($text){
	while(strstr($text, "\t")){
		$text = preg_replace_callback('/^([^\t\n]*)(\t+)/m', 'pc_tab_expand_helper', $text);
	}
	return $text;
}

function pc_tab_expand_helper($matches){
	$tab_stop = 8;
	$repeat = strlen($matches[2]) * $tab_stop - (strlen($matches[1]) % $tab_stop);
	return $matches[1] . str_repeat(' ', $repeat);

}

//把空格符替换成制表符
function pc_tab_unexpand($text){
	$tab_stop = 8;
	$lines = explode("\n", $text);
	foreach($lines as $i => $line){
		//把所有制表符扩展为空格符
		$line = pc_tab_expand($line);
		$chunks = str_split($line, $tab_stop);
		$chunkCount = count($chunks);
		//扫描除最后一个字符段之外的所有字符段
		for($j = 0; $j < $chunkCount - 1; $j++){
			$chunks[$j] = preg_replace('/ {2,}$/', "\t", $chunks[$j]);
		}
		
		//如果最后一个字符段是相当于一个制表位的空格符
		//将其转换为制表符；否则，不作任何处理
		if($chunks[$chunkCount - 1] == str_repeat(' ', $tab_stop)){
			$chunks[$chunkCount - 1] = "\t";
		}
		//重组所有字符段
		$lines[$i] = implode('', $chunks);
	}
	//重组所有行
	return implode("\n", $lines);
}

?>
[/php]
这2个函数都假定8个字符设置一个制表位
函数pc_tab_expand()中的正则表达式既可以匹配一组制表符，也可以匹配一行中位于这组制表符之前的所有文本。之所有需要匹配这组制表符之前的文本，是因为那些文本的长度会影响到这组制表符应该用多少个空格来替换，才能使后面的文本与下一个制表位对齐。这个函数不仅仅只是将每个制表符都替换为8个空格符，它还调整制表符后面的文本与制表位对齐

同样，pc_tab_unexpand()函数也不仅仅是寻找8个联系的空格符，然后用一个制表符将其替换掉那么简单。它会把每一行都分隔成8个字符一组的字符段，然后把这些字符段末尾处的空白(至少两个空格)替换成制表符。这样，不仅可以保持文本与制表符对齐，而且可以保留字符串中的空格。