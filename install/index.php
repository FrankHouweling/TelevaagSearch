<?php

/**
 *
 * Build script to insert the data in [telegraaf-data] into the newly created elasticsearch server.
 * Please make sure the /config.php configuration settings are correct. 
 *
 */

require_once "Kamervragen/Importer.php";

$importer = new KamervragenImporter();
$importer->runImport();

/*
require_once "Telegraaf/Importer.php";

// Import Telegraaf Articles

$importer = new TelegraafImporter();
$importer->runImport();
*/

?>