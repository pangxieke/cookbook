<?php
set_time_limit(0);
$filename = 'gene-ck-hs.txt';
$content = file_get_contents($filename);

$arr = explode('//', $content);

$data = [];
foreach($arr as $val){
    if(empty($val)){
        continue;
    }
    trim($val);
    $lineArr = explode("\n", $val);
    //var_dump($lineArr);
    $realLine = [];
    
    foreach($lineArr as $line){

        if(!empty($line)){
           
            $realLine[] = $line;
        }
    }
    $data[] = $realLine;
}


$mysqli = new mysqli('localhost', 'root', '', 'test');
$mysqli->set_charset('utf8');

$error = [];
$i = 0;
foreach($data as $val){
    $i ++;
    foreach($val as $val2){
        if(!empty($val2)){
            $larr = explode(' ', $val2);
            $temp = [];

            foreach($larr as $key=>$val){
                $temp[] = '"' . htmlspecialchars($val) . '"';
            }
            
            
            $str = "$i" . ', ' . implode(',', $temp);
            //echo $str;
            //var_dump($larr);
            $sql = "insert into data (`field1`,`field2`, `field3`, `field4`, `field5`, `field6`, `field7`, `field8`, `field9`, `field10`) values($str)";
             // echo $sql . '<br>';
            $res = $mysqli->query($sql);
            if(!$res){
                var_dump($mysqli->error);exit;
                echo $sql . '<br>';
                $error[] = $sql;
               
            }
            
        }
    }
}
 file_put_contents("error.txt",$error);