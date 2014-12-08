
<?php

require("../server.php");


// TODO unlink file if not used anywhere else in DB
// TODO move to server.php as `deleteFile'
// FIXME if file doesn't exist, should not return 0
function poster($auth) {
    $fargs = safeArgs($_POST, ["file"]);
    $pargs = safeArgs($_POST, ["path"]);

    if (!$fargs && !$pargs) echo 1;

    else {
        $db = opendb("../files.db", $fileTable);

        if ($fargs) {
            $stab = "Files";
            $cnam = "fhash";
            $file = $fargs["file"];

        } else if ($pargs) {
            $stab = "Paths";
            $cnam = "fpath";
            $file = $pargs["path"];
        }

        $isql = "DELETE FROM $stab WHERE fuser=:fuser AND $cnam=:fname";
        $sqlargs = array( "fuser" => $auth["fuser"]
                        , "fname" => $file
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

