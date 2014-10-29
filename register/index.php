
<?php

function opendb() {
    $db = new PDO("sqlite:../users.db");

    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

    $create = $db->prepare("CREATE TABLE IF NOT EXISTS Users (nick varchar(255), pass varchar(255))");

    $create->execute();
}

function parseArgs($post) {
    if ($post) {
        if ($post["nick"] && $post["pass"]) {
            return $post;
        }
    }
}

function register($db, $nick, $pass) {
    $query = $db->prepare("SELECT nick FROM Users");

    $query->execute();

    foreach ($query as $row) {
        var_dump($row);
    }

    // TODO check if username is taken
    if (true) {
        $input = $db->prepare("INSERT INTO Users (nick, pass) VALUES (:nick, :pass)");

        $input->execute(array("nick" => $nick, "pass" => $pass));
    }
}

function main() {
    $args = parseArgs($_POST);

    $args = array("nick" => "Test", "pass" => "password");

    if ($args !== null) {
        $nick = $args["nick"];
        $pass = $args["pass"];

        $db = opendb();

        register($db, $nick, $pass);

    } else echo "ERROR LOL";
}

main();

?>

