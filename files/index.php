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

<nav>

Mnemonic

</nav>

<div id=control>

<div id=file>Files</div>
<div id=dir>Folder</div>
<div id=move>Rename</div>
<div id=del>Delete</div>

</div>

<div id=progress>
<div>10% of 1024 MB used</div>
</div>

<div id=files>

<?php

function main() {
    $db = new PDO("sqlite:../files.db");
    $isql = "SELECT * FROM Files";
    $sel = $db -> prepare($isql);
    //var_dump($sel);
    $lel = $sel -> execute();
    //var_dump($lel);
    $files = $sel -> fetchAll();
    //var_dump($files);

    $totalSize = 0;

    for ($i = 0; $i < count($files); $i++) {
        $fname = $files[$i]["fname"];
        $fhash = $files[$i]["fhash"];
        $ftype = $files[$i]["ftype"];
        $fsize = $files[$i]["fsize"];
        $fdate = $files[$i]["fdate"];
        echo "<div class=file>
                <span class=chck><input type=checkbox></span>
                <img class=thumb src=/up/$fhash.$ftype>
                <a href=/up/$fhash.$ftype>$fname</a>
                <span class=size>$fsize</span>
                <span class=date>$fdate</span>
              </div>";

        $totalSize += $fsize;
    }

    echo ceil($totalSize / pow(1024, 3) * 100) . "% of 1 GiB used.";
}


main();

?>

</div>

</div>

</main>

<footer>Design by <span>Benedict Aas</span></footer>

</body>

</html>
