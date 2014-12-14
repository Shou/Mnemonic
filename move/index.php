
<?php

require("../server.php");


// TODO sqlargs, type
function poster($auth) {
    $args = safeArgs($_POST, ["n", "nf", "p"]);

    if ($args) {
        $fdb = opendb("../files.db", $fileTable);

        $user = $auth["fuser"];
        $sqlargs = array( "user" => $user );

        if (! $args["t"]) {
            $sqlargs["name"] = $args["nf"];
            $sqlargs["hash"] = $args["n"];
            $sqlargs["path"] = $args["p"];

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
        var_dump($stmt);
        var_dump($sqlargs);
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

