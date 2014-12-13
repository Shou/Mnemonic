
<?php

require("../server.php");


// TODO sqlargs, type
function poster($auth) {
    $args = safeArgs($_POST, ["n", "nf", "p"]);

    if ($args) {
        $user = $auth["fuser"];

        $fdb = opendb("../files.db", $fileTable);

        $sqlargs = array( "user" => $user );

        if (! $args["t"]) {
            $sqlargs["name"] = $args["n"];
            $sqlargs["hash"] = $args["nf"];

            $isql = "UPDATE Files
                     SET fname=:name, fpath=:path
                     WHERE fhash=:hash AND fuser=:user";

        } else {
            $sqlargs["oldpath"] = $pargs["n"];
            $sqlargs["newpath"] = $pargs["nf"];

            $isql = "UPDATE Paths
                     SET fname=:newpath
                     WHERE fname=:oldpath AND fuser=:user";
        }

        $stmt = $fdb -> prepare($isql);

        $res = $stmt -> execute($sqlargs);

        var_dump($res);

        echo 0;

    } else echo 1;

    exit();
}

function geter($auth) {
    if (! $auth) redirect("/");
}

function main() {
    $adb = opendb("../users.db", $authTables);

    $auth = cookieAuth($adb);

    if ($_SERVER["REQUEST_METHOD"] === "POST") poster($auth);

    else geter($auth);
}

main();

?>

