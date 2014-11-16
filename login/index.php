
<?php

// opendb :: String -> IO PDO
function opendb($path) {
    $db = new PDO("sqlite:" . $path);

    $itable = "CREATE TABLE IF NOT EXISTS Logins
               (fuser TEXT, fkey TEXT UNIQUE, fdate INT);
               CREATE TABLE IF NOT EXISTS Users
               (fuser TEXT UNIQUE, fhash TEXT);";

    $db -> exec($itable);

    return $db;
}

// cookieMatch :: PDO -> String -> IO Bool
function cookieMatch($db, $key) {
    $query = $db -> prepare("SELECT * FROM Logins
                             WHERE fkey=:fkey;");

    $sqlargs = array( "fkey" => $key );

    $res = $query -> execute($sqlargs);

    $keys = $query -> fetchAll();
    var_dump($keys);

    return count($keys) === 1;
}

// makeCookie :: PDO -> String -> IO Bool
function makeCookie($db, $user) {
    $expire = time() + (365 * 24 * 60 * 60);

    // oh no there's a n / 374144419156711147060143317175368453031918731001856
    // chance of a collision :(
    $rkey = base64_encode(openssl_random_pseudo_bytes(21));

    $query = $db -> prepare("INSERT INTO Logins (fuser, fkey, fdate)
                             VALUES (:fuser, :fkey, :fdate);");


    $sqlargs = array( "fuser" => $user
                    , "fkey" => $rkey
                    , "fdate" => $expire
                    );

    $res = $query -> execute($sqlargs);

    if ($res) {
        // please don't intercept my cookies
        setcookie("pass", $rkey, $expire, "/", "", false, true);

        return 0;

    } else return 3;
}

// login :: PDO -> String -> String -> IO Int
function login($db, $user, $key) {
    $cook = $_COOKIE["pass"];
    var_dump($cook);

    if (cookieMatch($db, $cook)) return 2;

    $query = $db -> prepare("SELECT * FROM Users
                             WHERE fuser=:fuser;");

    $sqlargs = array( "fuser" => $user );

    $res = $query -> execute($sqlargs);

    $fuser = $query -> fetchAll();

    if (count($fuser) === 1) {
        $hash = $fuser["fhash"];

        if (password_verify($key, $hash))
            return makeCookie($db, $user);

        else return 4;

    } else return 5;
}

// parseArgs :: Obj String String -> Maybe (Obj String String)
function parseArgs($post) {
    if ($post) {
        if ($post["user"] && $post["pass"]) {
            return $post;

        } else return null;
    }
}


function main() {
    $db = opendb("../users.db");

    $args = parseArgs($_POST);

    if ($args) {
        $code = login($db, $args["user"], $args["pass"]);

        echo $code;

    } else echo 1;
}

main();

?>

