<?php
if(!file_exists('list.txt'))
	file_put_contents('list.txt',"\n");

$file = file_get_contents('list.txt');
$port = 9999;
$key = 1;
if(isset( $_GET["name"])){
	$name = $_GET["name"];
	if(isset( $_GET["port"]))
		$port = $_GET["port"];
	if(isset( $_GET["key"]))
		$key = $_GET["key"];

	$strings = "$name;".$_SERVER["REMOTE_ADDR"].";$port;$key;".time().";\n";

	if(preg_match("/".$name.";.*?\n/",$file))
	{
		$file = preg_replace("/".$name.";.*?\n/",$strings,$file);
	}
	else
	{
		$file .= $strings;
	}
}

if(preg_match_all("/;(\d{9,11});/",$file,$matches))
{
	$time = time()-1800;
	foreach ($matches[1] as $value)
	{
		if($value<$time)
		{
			$file = preg_replace("/\n.*?".$value.".*?\n/","\n",$file);
		}
	}
}
file_put_contents('list.txt',$file);

print($file);

if (!function_exists('file_put_contents')) {
	function file_put_contents($n,$d) {
		$f=@fopen($n,"w");
		if (!$f) {
			return false;
		} else {
			fwrite($f,$d);
			fclose($f);
			return true;
		}
	}
}
?>