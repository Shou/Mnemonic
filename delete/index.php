
<?php

require("../server.php");


// TODO unlink file if not used anywhere else in DB
// TODO move to server.php as `deleteFile'
// FIXME if file doesn't exist, should not return 0
function poster($auth) {
    $args = safeArgs($_POST, ["n", "p"]);

    if (!$args) echo 1;

    else {
        $db = opendb("../files.db", $fileTable);

        if ($args["t"]) {
            $stab = "Paths";
            $cnam = "fname";

        } else {
            $stab = "Files";
            $cnam = "fhash";
        }

        $file = $args["n"];
        $path = $args["p"];

        $isql = "DELETE FROM $stab
                 WHERE fuser=:fuser AND $cnam=:fname AND fpath=:fpath";
        $sqlargs = array( "fuser" => $auth["fuser"]
                        , "fname" => $file
                        , "fpath" => $path
                        );

        $sel = $db -> prepare($isql);
        $exe = $sel -> execute($sqlargs);

        var_dump($exe);
        if ($exe) echo 0;
    }

    exit();
}

function geter() {
    exit();
}

function main() {
    $adb = opendb("../users.db", $authTables);

    $auth = cookieAuth($adb);

    if (! $auth) redirect("/");

    else if ($_SERVER["REQUEST_METHOD"] === "POST") poster($auth);

    else geter();
}

main();

?>

