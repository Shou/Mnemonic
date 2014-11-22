
<?php

require("../server.php");


function main() {
    $db = opendb("../users.db", $authTables);

    $args = authArgs($_POST);

    if ($args) {
        $code = login($db, $args["user"], $args["pass"]);

        echo $code;

    } else echo 1;
}

main();

?>

