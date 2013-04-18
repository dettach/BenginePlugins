<?php
if(!defined("BENGINE")) {die ("Hacking!");}

function recurs_category_id($parent)
{
	$sql = doquery("SELECT id,child FROM category WHERE parent='".$parent."' ORDER BY id");
	$structure = doarray($sql);
	$return = "";
	foreach($structure as $k => $v)
	{
		$return .= $v["id"].",";
		if($v["child"] > 0) {
			$return .= recurs_category_id($v["id"]);
		}
	}
	return $return;
}

$sql = doquery("SELECT id,title,parent,child FROM category WHERE parent='0' ORDER BY id");
$content = doarray($sql);

foreach($content as $k => $v){
	if($v["child"] > 0) {
		$inid = recurs_category_id($v["id"]);
		$inid = mb_substr($inid,0,-1);
		if($inid != "") {
			$sql = doquery("SELECT id,title,image FROM product WHERE image!='' and category IN (".$inid.") ORDER BY id LIMIT 10");
			$content[$k]["product"] = doarray($sql);
		}
	}
}

?>