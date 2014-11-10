
<?php

// TODO
// - Logged-in upload
//      - Cookies

function opendb() {
// | Create or open "files.db" SQLite3 database.
    $db = new PDO("sqlite:../files.db");

    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

    $create = $db->prepare("CREATE TABLE IF NOT EXISTS Files (filename varchar(255), filesha1 varchar(255), filetype varchar(255))");

    $create->execute();
}

function storeFiles($db) {
    $tmps = $_FILES["files"]["tmp_name"];
    $sizes = $_FILES["files"]["size"];
    $names = $_FILES["files"]["name"];

    $total_filesize = 0;

    $len = count($tmps);

    // | Check the total filesize, get file extension, generate SHA1 for file,
    //   move file to "up" folder.
    for ($i = 0; $i < $len; $i++) {
        $total_filesize += $sizes[$i];

        if ($total_filesize > pow(1024, 2) * 10) {
            echo "File(s) too large.\n";

            break;
        }

        $exts = explode(".", $names[$i]);

        $hash = sha1_file($tmps[$i]);

        if (count($exts) > 1) {
            $name = $hash. "." . implode(".", array_slice($exts, 1));

        } else {
            $name = $hash;
        }

        $moved = move_uploaded_file($tmps[$i], "../up/" . $name) . "\n";

        if ($moved) echo $name . "\n";
        else echo "File move error.\n";
    }
}

function main() {
    $db = opendb();

    storeFiles($db);
}

main();

?>

