
<?php

require("../server.php");


function poster($auth) {
    $args = safeArgs($_POST, ["n", "p"]);

    if ($args) {
        $fdb = opendb("../files.db", $fileTable);

        // TODO reject files instead
        $name = str_replace("/", "", $args["n"]);

        $isql = "INSERT INTO Paths (fname, fpath, fuser, fsize, fdate)
                 VALUES (:name, :path, :user, :size, :date);";
        $sqlargs = array( "path" => $args["p"]
                        , "name" => $name
                        , "user" => $auth["fuser"]
                        , "size" => 0
                        , "date" => time()
                        );

        $stmt = $fdb -> prepare($isql);
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

