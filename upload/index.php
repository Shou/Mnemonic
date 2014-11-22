
<?php

require("../server.php");

function main() {
    $fdb = opendb("../files.db", $fileTable);
    $adb = opendb("../users.db", $authTables);

    storeFiles($fdb, $adb);
}

main();

?>

