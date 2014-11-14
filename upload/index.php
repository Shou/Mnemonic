
<?php

// TODO
// - Logged-in upload
//      - Cookies

// opendb :: IO PDO
function opendb() {
    // Create or open "files.db" SQLite3 database.
    $db = new PDO("sqlite:../files.db");

    // Prepare statements in the SQL database.
    //$db -> setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    // Raise hell, and exceptions.
    //$db -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $itable = "CREATE TABLE IF NOT EXISTS Files
               (fname TEXT, fuser TEXT, fpath TEXT, fhash TEXT, ftype TEXT,
                fsize INTEGER, fdate INTEGER);";

    $db -> exec($itable);

    return $db;
}

// storeFiles :: PDO -> IO ()
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

        $name = $names[$i];

        $user = null;

        $path = null;

        $exts = explode(".", $name);
        $extn = "";

        $hash = sha1_file($tmps[$i]);

        if (count($exts) > 1) {
            $extn = implode(array_slice($exts, 1));
            $filen = $hash . "." . $extn;

        } else {
            $filen = $hash;
        }

        $time = time();

        $size = $sizes[$i];

        if (! file_exists("../up/" . $path . $filen))
            $moved = move_uploaded_file($tmps[$i], "../up/" . $filen) . "\n";

        if ($moved) echo $path . $filen . "\n";
        else echo "File move error.\n";

        $iquery = "INSERT INTO Files (fname, fuser, fpath, fhash, ftype, fsize, fdate)
                   VALUES (:fname, :fuser, :fpath, :fhash, :ftype, :fsize, :fdate);";
        $stmt = $db -> prepare($iquery);
        var_dump($stmt);
        $sqlargs = array( "fname" => $name
                        , "fuser" => $user
                        , "fpath" => $path
                        , "fhash" => $hash
                        , "ftype" => $extn
                        , "fsize" => $size
                        , "fdate" => $time
                        );
        $res = $stmt -> execute($sqlargs);
        var_dump($res);

        $stmt = $db -> prepare("SELECT * FROM Files");
        var_dump($stmt);
        $res = $stmt -> execute();
        var_dump($res);
        $dat = $stmt -> fetchAll();
        var_dump($dat);
    }
}


function main() {
    $db = opendb();

    storeFiles($db);
}

main();

?>

