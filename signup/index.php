
<?php

require("../server.php");


function main() {
    $args = authArgs($_POST);

    if ($args) {
        $db = opendb("../users.db", $authTables);

        if (strlen($args["pass"]) < 8)
            echo "Please use 8 or more characters in your password.\n";

        else if (strlen($args["pass"]) > 255)
            echo "Please use less than 256 characters in your password.\n";

        else if (strlen($args["user"]) > 32)
            echo "Please use 32 or less characters in your username.\n";

        else register($db, $args["user"], $args["pass"]);

    } else echo "1";
}

main();

?>

