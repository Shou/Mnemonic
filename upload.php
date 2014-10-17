
<?php

// TODO
// - Logged-in upload
//      - Cookies

// | Maximum filesize as compared to the sum of all the files' sizes.
// max_filesize :: Int
$max_filesize = pow(1024, 2) * 10;
$total_filesize = 0

function opendb() {
// | Create or open "files.db" SQLite3 database.
    $db = new PDO("files.db");

    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

    $create = $db->prepare("CREATE TABLE IF NOT EXISTS Files (filename varchar(255), filesha1 varchar(255), filetype varchar(255))");

    $create->exacute();
}

$tmps = $_FILES["files"]["tmp_name"];
$sizes = $_FILES["files"]["size"];
$names = $_FILES["files"]["name"];

$len = count($tmps);

// | Check the total filesize, get file extension, generate SHA1 for file,
//   move file to "up" folder.
for ($i = 0; $i < $len; $i++) {
    $total_filesize += $sizes[$i]
    if ($total_filesize > $max_filesize) {
        echo "File(s) too large.";

        break;
    }

    $exts = explode(".", $names[$i]);

    $hash = sha1_file($tmps[$i]);

    if (count($exts) > 1) {
        $name = $hash. "." . implode(".", array_slice($exts, 1));

    } else {
        $name = $hash;
    }

    echo $name . "\n";

    move_uploaded_file($tmps[$i], "up/" . $name) . "\n";
}

function main() {
}

main();

?>

