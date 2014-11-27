
<?php

require("../server.php");

$fdb = opendb("../files.db", $fileTable);
$adb = opendb("../users.db", $authTables);

$nick = cookieAuth($adb)["fuser"];

$paths = selectFile("Paths", $fdb, $adb);
$pathDivs = [];

$files = selectFile("Files", $fdb, $adb);
$fileDivs = [];

$totalSize = 0;

for ($i = 0; $i < count($paths); $i++) {
    $fpath = $paths[$i]["fpath"];
    $fsize = $paths[$i]["fsize"];
    $fdate = $paths[$i]["fdate"];

    array_push($pathDivs, "
        <div class=file>
            <span class=chck><input type=checkbox></span>
            <img class=thumb src=/icons/folder.png>
            <a href=/files/#$fpath>$fpath</a>
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

<?php require("../top.html"); ?>

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

