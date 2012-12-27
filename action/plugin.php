<?php

if(isset($_GET["search"])) {
	$search = htmlspecialchars(trim($_GET["search"]), ENT_QUOTES, "UTF-8");
	$sql = doquery("SELECT id,title,catalog,proizvoditel,articul,cost FROM catalog_elements WHERE title LIKE '%".$search."%' or articul LIKE '%".$search."%' ORDER BY id");
	if(dorows($sql) > 0) {
		$content = doarray($sql);
	}
}

?>