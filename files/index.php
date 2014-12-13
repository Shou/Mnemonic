
<?php

require("../server.php");


$cpath = $_GET["path"];
if (! $cpath) $cpath = "/";

$fdb = opendb("../files.db", $fileTable);
$adb = opendb("../users.db", $authTables);

$nick = cookieAuth($adb)["fuser"];

$paths = selectFile("Paths", $fdb, $nick);
$pathDivs = [];

$files = selectFile("Files", $fdb, $nick);
$fileDivs = [];

$totalSize = 0;

for ($i = 0; $i < count($paths); $i++) {
    $fname = $paths[$i]["fname"];
    $fpath = $paths[$i]["fpath"];
    $fsize = $paths[$i]["fsize"];
    $fdate = $paths[$i]["fdate"];

    if (pathEq($fpath, $cpath))
        array_push($pathDivs, "
            <div class=fldr>
                <span class=chck><input type=checkbox></span>
                <img class=thmb src=/icons/folder.png>
                <a href=javascript:;>$fname</a>
                <span class=size>$fsize</span>
                <span class=date>$fdate</span>
            </div>\n");
}

for ($i = 0; $i < count($files); $i++) {
    $fname = $files[$i]["fname"];
    $fhash = $files[$i]["fhash"];
    $ftype = $files[$i]["ftype"];
    $fsize = $files[$i]["fsize"];
    $fdate = $files[$i]["fdate"];
    $fpath = $files[$i]["fpath"];

    if (pathEq($fpath, $cpath))
        array_push($fileDivs, "
            <div class=file>
                <span class=chck><input type=checkbox></span>
                <img class=thmb src=/up/$fhash>
                <a href=/up/$fhash download=\"$fname\">$fname</a>
                <span class=size>$fsize</span>
                <span class=date>$fdate</span>
            </div>\n");

    $totalSize += $fsize;
}

$total = ceil($totalSize / pow(1024, 3) * 100);

?>

<?php require("../top.html"); ?>

<main>

<div>

<div>

<nav>

Mnemonic

</nav>

<div id=control>

<input type="button" name="path" value="Folder">
<input type="button" name="rename" value="Rename">
<input type="button" name="move" value="Move">
<input type="button" name="delete" value="Delete">

</div>

<style type="text/css">
#progress
{ background-image: linear-gradient( 90deg, #00bcd4 <?php echo $total; ?>%
                                   , #2d2d2d <?php echo $total + 1; ?>%)
}
</style>
<div id=progress>
<?php echo $total . "%"; ?>
</div>

</div>

<div id=files>

<?php

for ($i = 0; $i < count($pathDivs); $i++) {
    echo $pathDivs[$i];
}

for ($i = 0; $i < count($fileDivs); $i++) {
    echo $fileDivs[$i];
}

?>

</div>

</div>

</div>

</main>

<?php require("../bottom.html"); ?>

