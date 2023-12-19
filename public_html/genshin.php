<html>
    <head>
        <title>Genshin Database</title>
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>

    <body>
        <div class="navbar">
            <a href="genshin.php">Home</a>
            <div class="dropdown">
                <button onclick="window.location.href='entity/character.php'" 
                        class="dropbtn active" >Entities
                </button>
                <div class="dropdown-content">
                    <a href="entity/character.php">Character</a>
                    <a href="entity/material.php">Material</a>
                    <a href="entity/ascensionMaterial.php">Ascension Material</a>
                    <a href="entity/consumable.php">Consumable</a>
                    <a href="entity/region.php">Region</a>
                    <a href="entity/subregion.php">Subregion</a>
                    <a href="entity/enemies.php">Enemies</a>
                    <a href="entity/boss.php">Boss</a>
                    <a href="entity/weapon.php">Weapon</a>
                    <a href="entity/artifactSet.php">ArtifactSet</a>
                    <a href="entity/artifact.php">Artifacts</a>
                    <a href="entity/stat.php">Stat</a>
                </div>
            </div> 
            <div class="dropdown">
                <button onclick="window.location.href='relationship/bossDrops.php'"
                    class="dropbtn" >Relationships 
                </button>
                <div class="dropdown-content">
                    <a href="relationship/bossDrops.php">Boss Drops</a>
                    <a href="relationship/enemyFoundAt.php">Enemy Location</a>
                    <a href="relationship/enemyDrops.php">Enemy Drops</a>
                    <a href="relationship/characterCanWield.php">Character Weapon Ranking</a>
                    <a href="relationship/characterArtifacts.php">Character Artifact Ranking</a>
                    <a href="relationship/requiredMaterialForWeapon.php">Weapon Materials</a>
                    <a href="relationship/materialsFoundAt.php">Material Location</a>
                    <a href="relationship/characterInteractions.php">Character Interactions</a>
                    <a href="relationship/battleInRegion.php">Battle Locations</a>
                    <a href="relationship/consumableBoosts.php">Consumable Boosts</a>
                    <a href="relationship/requiredMaterialForCharacter.php">Character Ascension Materials</a>
                    <a href="relationship/artifactEnhances.php">Artifact Stats</a>
                </div>
            </div>
            <a href="search.php">Search</a> 
        </div>


        <h2>Reset</h2>
        <p>If you wish to reset the table press on the reset button. If this is the first time you're running this page, you MUST use reset</p>

        <form method="POST" action="genshin.php">
            <!-- if you want another page to load after the button is clicked, you have to specify that page in the action parameter -->
            <input type="hidden" id="resetTablesRequest" name="resetTablesRequest">
            <p><input type="submit" value="Reset" name="reset"></p>
        </form>

        <hr />

        <?php
		//this tells the system that it's no longer just parsing html; it's now parsing PHP

        $success = True; //keep track of errors so it redirects the page only if there are no errors
        $db_conn = NULL; // edit the login credentials in connectToDB()
        $show_debug_alert_messages = False; // set to True if you want alerts to show you which methods are being triggered (see how it is used in debugAlertMessage())

        function debugAlertMessage($message) {
            global $show_debug_alert_messages;

            if ($show_debug_alert_messages) {
                echo "<script type='text/javascript'>alert('" . $message . "');</script>";
            }
        }

        function executePlainSQL($cmdstr) { //takes a plain (no bound variables) SQL command and executes it
            //echo "<br>running ".$cmdstr."<br>";
            global $db_conn, $success;
            echo $cmdstr . "\n";

            $statement = OCIParse($db_conn, $cmdstr);
            //There are a set of comments at the end of the file that describe some of the OCI specific functions and how they work

            if (!$statement) {
                echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
                $e = OCI_Error($db_conn); // For OCIParse errors pass the connection handle
                echo htmlentities($e['message']);
                $success = False;
            }

            $r = OCIExecute($statement, OCI_DEFAULT);
            if (!$r) {
                echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
                $e = oci_error($statement); // For OCIExecute errors pass the statementhandle
                echo htmlentities($e['message']);
                $success = False;
            }

			return $statement;
		}

        function executeBoundSQL($cmdstr, $list) {
            /* Sometimes the same statement will be executed several times with different values for the variables involved in the query.
		In this case you don't need to create the statement several times. Bound variables cause a statement to only be
		parsed once and you can reuse the statement. This is also very useful in protecting against SQL injection.
		See the sample code below for how this function is used */

			global $db_conn, $success;
			$statement = OCIParse($db_conn, $cmdstr);

            if (!$statement) {
                echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
                $e = OCI_Error($db_conn);
                echo htmlentities($e['message']);
                $success = False;
            }

            foreach ($list as $tuple) {
                foreach ($tuple as $bind => $val) {
                    //echo $val;
                    //echo "<br>".$bind."<br>";
                    OCIBindByName($statement, $bind, $val);
                    unset ($val); //make sure you do not remove this. Otherwise $val will remain in an array object wrapper which will not be recognized by Oracle as a proper datatype
				}

                $r = OCIExecute($statement, OCI_DEFAULT);
                if (!$r) {
                    echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
                    $e = OCI_Error($statement); // For OCIExecute errors, pass the statementhandle
                    echo htmlentities($e['message']);
                    echo "<br>";
                    $success = False;
                }
            }
        }

        function printResult($result) { //prints results from a select statement
            echo "<br>Retrieved data from table demoTable:<br>";
            echo "<table>";
            echo "<tr><th>ID</th><th>Name</th></tr>";

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row["ID"] . "</td><td>" . $row["NAME"] . "</td></tr>"; //or just use "echo $row[0]"
            }

            echo "</table>";
        }

        function connectToDB() {
            global $db_conn;

            // Your username is ora_(CWL_ID) and the password is a(student number). For example,
			// ora_platypus is the username and a12345678 is the password.
            $db_conn = OCILogon("", "", "");

            if ($db_conn) {
                debugAlertMessage("Database is Connected");
                return true;
            } else {
                debugAlertMessage("Cannot connect to Database");
                $e = OCI_Error(); // For OCILogon errors pass no handle
                echo htmlentities($e['message']);
                return false;
            }
        }

        function disconnectFromDB() {
            global $db_conn;

            debugAlertMessage("Disconnect from Database");
            OCILogoff($db_conn);
        }

        function handleUpdateRequest() {
            global $db_conn;

            $old_name = $_POST['oldName'];
            $new_name = $_POST['newName'];

            // you need the wrap the old name and new name values with single quotations
            executePlainSQL("UPDATE demoTable SET name='" . $new_name . "' WHERE name='" . $old_name . "'");
            OCICommit($db_conn);
        }

        function handleResetRequest() {
            global $db_conn;

            executePlainSQL("BEGIN FOR i IN (SELECT ut.table_name FROM USER_TABLES ut) LOOP EXECUTE IMMEDIATE 'drop table '|| i.table_name ||' CASCADE CONSTRAINTS '; END LOOP; END;");


            $sqlFile = './newSQL.sql';
            $sqlContent = file_get_contents($sqlFile);

            // Split SQL content by semicolons
            $sqlStatements = explode(');', $sqlContent);

            
            // Execute each SQL statement
            foreach ($sqlStatements as $sqlStatement) {
                if (trim($sqlStatement) != '') {
                    executePlainSQL($sqlStatement . ")");
                }
            }

            $sqlFile = './insertSQL.sql';
            $sqlContent = file_get_contents($sqlFile);

            // Split SQL content by semicolons
            $sqlStatements = explode('DUAL;', $sqlContent);

            // Execute each SQL statement
            foreach ($sqlStatements as $sqlStatement) {
                if (trim($sqlStatement) != '') {
                    executePlainSQL($sqlStatement . "DUAL");
                }
            }
            OCICommit($db_conn);
        }

        function handleInsertRequest() {
            global $db_conn;

            //Getting the values from user and insert data into the table
            $tuple = array (
                ":bind1" => $_POST['insNo'],
                ":bind2" => $_POST['insName']
            );

            $alltuples = array (
                $tuple
            );

            executeBoundSQL("insert into demoTable values (:bind1, :bind2)", $alltuples);
            OCICommit($db_conn);
        }

        function handleCountRequest() {
            global $db_conn;

            $result = executePlainSQL("SELECT Count(*) FROM demoTable");

            if (($row = oci_fetch_row($result)) != false) {
                echo "<br> The number of tuples in demoTable: " . $row[0] . "<br>";
            }
        }

        // HANDLE ALL POST ROUTES
	// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
        function handlePOSTRequest() {
            if (connectToDB()) {
                if (array_key_exists('resetTablesRequest', $_POST)) {
                    handleResetRequest();
                } else if (array_key_exists('updateQueryRequest', $_POST)) {
                    handleUpdateRequest();
                } else if (array_key_exists('insertQueryRequest', $_POST)) {
                    handleInsertRequest();
                }

                disconnectFromDB();
            }
        }

        // HANDLE ALL GET ROUTES
	// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
        function handleGETRequest() {
            if (connectToDB()) {
                if (array_key_exists('countTuples', $_GET)) {
                    handleCountRequest();
                }

                disconnectFromDB();
            }
        }

		if (isset($_POST['reset']) || isset($_POST['updateSubmit']) || isset($_POST['insertSubmit'])) {
            handlePOSTRequest();
        } else if (isset($_GET['countTupleRequest'])) {
            handleGETRequest();
        }
		?>
	</body>
</html>
