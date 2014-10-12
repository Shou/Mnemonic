
<?php

// 10 MiB
$max_filesize = pow(1024, 2) * 10;

$db = new SQLite3("files.db");

$db->exec("CREATE TABLE IF NOT EXISTS Files (filename varchar(255), filesha1 varchar(255), filetype varchar(255))");

$tmps = $_FILES["files"]["tmp_name"];
$sizes = $_FILES["files"]["size"];
$names = $_FILES["files"]["name"];

$len = count($tmps);

for ($i = 0; $i < $len; $i++) {
    if ($_FILES["files"]["size"][$i] > $max_filesize) {
        echo $sizes[$i] . " > " . $max_filesize;

        continue;
    }

    $exts = explode(".", $names[$i]);

    $hash= sha1_file($tmps[$i]);

    if (count($exts) > 1) {
        $name = $hash. "." . implode(".", array_slice($exts, 1));

    } else {
        $name = $hash;
    }

    echo $name . "\n";

    move_uploaded_file($tmps[$i], "up/" . $name) . "\n";
}

?>

