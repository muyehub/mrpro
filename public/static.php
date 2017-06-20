<?php
function st(){
	static $i = 0;
	echo $i;
	if( $i < 3 ){
		$i++;
		st();
	}
	return $i;
}
$a = st();
echo $a;