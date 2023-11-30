<?php

require_once "Compiler.php";
require_once "Checks/CheckEmpty.php";
require_once "Checks/Tables.php";

require_once "Commands/InstallMainSQLCommandline.php";
require_once "Commands/InstallMenu.php";
require_once "Commands/InstallPagePug.php";
require_once "Commands/Import.php";

require_once "CMSMiddleware/Init.php";
require_once "Middlewares/Middleware.php";
require_once "Routes/SetupHandshake.php";
require_once "Routes/JsLoader.php";
require_once "Routes/Ping.php";
require_once "Routes/AppendPublicKey.php";
require_once "Routes/SyncRemote.php";
require_once "Routes/Image.php";
require_once "Routes/RemoveVoterReference.php";
require_once "Routes/SyncBlockedVoters.php";
require_once "Routes/Decrypt.php";
require_once "Routes/Count.php";
require_once "Routes/KeyUpload.php";
require_once "Routes/State.php";
require_once "Routes/SaveSettings.php";