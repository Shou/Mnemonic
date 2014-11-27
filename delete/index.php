
<?php

require("../server.php");


function poster($auth) {
    $args = safeArgs($_POST, ["file"]);

    if ($args) {
        $db = opendb("../files.db", $fileTable);

        

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

