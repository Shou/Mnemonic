
<?php

require("../server.php");


function poster($auth) {
    $args = safeArgs($_POST, ["path"]);

    if ($args) {
        $fdb = opendb("../files.db", $fileTable);

        $stmt = $fdb -> prepare("INSERT INTO Paths (fpath, fuser, fsize, fdate)
                                 VALUES (:fpath, :fuser, :fsize, :fdate);");

        $sqlargs = array( "fpath" => $args["path"]
                        , "fuser" => $auth["fuser"]
                        , "fsize" => 0
                        , "fdate" => time()
                        );

        $res = $stmt -> execute($sqlargs);

        var_dump($res);

    } else echo "1";

    exit();
}

function geter() {
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

