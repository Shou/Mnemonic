
<?php

require("../server.php");

$fdb = opendb("../files.db", $fileTable);
$adb = opendb("../users.db", $authTables);

$files = userFiles($fdb, $adb);
$fileDivs = [];

$totalSize = 0;

for ($i = 0; $i < count($files); $i++) {
    $fname = $files[$i]["fname"];
    $fhash = $files[$i]["fhash"];
    $ftype = $files[$i]["ftype"];
    $fsize = $files[$i]["fsize"];
    $fdate = $files[$i]["fdate"];

    array_push($fileDivs, "
        <div class=file>
            <span class=chck><input type=checkbox></span>
            <img class=thumb src=/up/$fhash.$ftype>
            <a href=/up/$fhash.$ftype>$fname</a>
            <span class=size>$fsize</span>
            <span class=date>$fdate</span>
        </div>\n");

    $totalSize += $fsize;
}

$total = ceil($totalSize / pow(1024, 3) * 100);

?>

<!DOCTYPE html>

<head>

<title>Files &#x25AA; Mnemonic</title>

<meta charset="UTF-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1">

<link rel="stylesheet" type="text/css" href="/style.css">

</head>

<body>

<header>

<a href="/files/">Mnemonic</a>

<nav>
Guest
<div>
    <a href="/">Log out</a>
</div>
</nav>

</header>

<main>

<div>

<div>

<nav>

Mnemonic

</nav>

<div id=control>

<input type="button" name="file" value="Files">
<input type="button" name="dir" value="Folder">
<input type="button" name="move" value="Rename">
<input type="button" name="del" value="Delete">

</div>

<style type="text/css">
#progress
{ background-image: linear-gradient( 90deg, #00bcd4 <?php echo $total; ?>%
                                   , #f7f7f7 <?php echo $total + 1; ?>%)
}
</style>
<div id=progress>
<?php echo $total . "%"; ?>
</div>

</div>

<div id=files>

<?php

for ($i = 0; $i < count($fileDivs); $i++) {
    echo $fileDivs[$i];
}

?>

</div>

</div>

</div>

</main>

<footer>Design by <span>Benedict Aas</span></footer>

</body>

</html>

