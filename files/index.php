
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
            <label class=fldr>
                <input class=chck type=checkbox>
                <span class=thmb>
                    <img style=background-image:url(/icons/folder.png)>
                </span>
                <span class=link>
                    <a href=javascript:;>$fname</a>
                </span>
                <span class=size>$fsize</span>
                <span class=date>$fdate</span>
            </label>\n");
}

for ($i = 0; $i < count($files); $i++) {
    $fname = $files[$i]["fname"];
    $fhash = $files[$i]["fhash"];
    $ftype = $files[$i]["ftype"];
    $fsize = $files[$i]["fsize"];
    $fdate = $files[$i]["fdate"];
    $fpath = $files[$i]["fpath"];

    if (preg_match("/jpe?g|png|gif/", $ftype))
        $img = "/up/$fhash";
    else
        $img = "/icons/empty.png";

    if (pathEq($fpath, $cpath))
        array_push($fileDivs, "
            <label class=file>
                <input class=chck type=checkbox>
                <span class=thmb>
                    <img style=background-image:url($img)>
                </span>
                <span class=link>
                    <a href=/up/$fhash download=\"$fname\">$fname</a>
                </span>
                <span class=size>$fsize</span>
                <span class=date>$fdate</span>
            </label>\n");

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

