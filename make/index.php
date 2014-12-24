
<?php

require("../server.php");


function poster($auth) {
    $args = safeArgs($_POST, ["n"]);

    if ($args) {
        $fdb = opendb("../files.db", $fileTable);

        $path = implode("/", array_filter(explode("/", $args["n"])));

        $isql = "INSERT INTO Paths (fpath, fuser, fsize, fdate)
                 VALUES (:path, :user, :size, :date);";
        $sqlargs = array( "path" => $path
                        , "user" => $auth["fuser"]
                        , "size" => 0
                        , "date" => time()
                        );

        $stmt = $fdb -> prepare($isql);
        $res = $stmt -> execute($sqlargs);

        var_dump($res);

    } else echo 1;

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

