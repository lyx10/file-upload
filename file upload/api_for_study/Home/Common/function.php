<?php

$salt = 'JS++';

function trim_space($str){
	$search = array(" ","　","\n","\r","\t");
    $replace = array("","","","","");
    return str_replace($search, $replace, $str);
}

function get_random($len) {
	$chars_array = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", );
	$charsLen = count($chars_array) - 1;
	$outputstr = "";
	for ($i = 0; $i < $len; $i++) {
		$outputstr .= $chars_array[mt_rand(0, $charsLen)];
	}
	return $outputstr;
}