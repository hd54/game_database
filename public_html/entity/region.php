<html>
    <head>
        <title>Genshin Entity Database</title>
        <link rel="stylesheet" type="text/css" href="../style.css">
    </head>

    <body>
        <div class="navbar">
            <a href="../genshin.php">Home</a>
            <div class="dropdown">
                <button onclick="window.location.href='character.php'" 
                        class="dropbtn active" >Entities
                </button>
                <div class="dropdown-content">
                    <a href="character.php">Character</a>
                    <a href="material.php">Material</a>
                    <a href="ascensionMaterial.php">Ascension Material</a>
                    <a href="consumable.php">Consumable</a>
                    <a href="region.php" class="active">Region</a>
                    <a href="subregion.php">Subregion</a>
                    <a href="enemies.php">Enemies</a>
                    <a href="boss.php">Boss</a>
                    <a href="weapon.php">Weapon</a>
                    <a href="artifactSet.php">ArtifactSet</a>
                    <a href="artifact.php">Artifacts</a>
                    <a href="stat.php">Stat</a>
                </div>
            </div> 
            <div class="dropdown">
                <button onclick="window.location.href='../relationship/bossDrops.php'" 
                        class="dropbtn" >Relationships 
                </button>
                <div class="dropdown-content">
                    <a href="../relationship/bossDrops.php">Boss Drops</a>
                    <a href="../relationship/enemyFoundAt.php">Enemy Location</a>
                    <a href="../relationship/enemyDrops.php">Enemy Drops</a>
                    <a href="../relationship/characterCanWield.php">Character Weapon Ranking</a>
                    <a href="../relationship/characterArtifacts.php">Character Artifact Ranking</a>
                    <a href="../relationship/requiredMaterialForWeapon.php">Weapon Materials</a>
                    <a href="../relationship/materialsFoundAt.php">Material Location</a>
                    <a href="../relationship/characterInteractions.php">Character Interactions</a>
                    <a href="../relationship/battleInRegion.php">Battle Locations</a>
                    <a href="../relationship/consumableBoosts.php">Consumable Boosts</a>
                    <a href="../relationship/requiredMaterialForCharacter.php">Character Ascension Materials</a>
                    <a href="../relationship/artifactEnhances.php">Artifact Stats</a>
                </div>
            </div>
            <a href="../search.php">Search</a> 
        </div>

        <div style="padding:20px; margin-top:30px">

            <h2>Insert Values into Region</h2>
            <form method="POST" action="region.php"> <!--refresh page when submitted-->
                <input type="hidden" id="insertQueryRequest" name="insertQueryRequest">
                Name: <input type="text" name="name"> <br /><br />
                Lore: <input type="text" name="lore"> <br /><br />

                <input type="submit" value="Insert" name="insertSubmit"></p>
            </form>

            <hr />

            <h2>Update Name in Region</h2>
            <p>The values are case sensitive and if you enter in the wrong case, the update statement will not do anything.</p>

            <form method="POST" action="region.php"> <!--refresh page when submitted-->
                <input type="hidden" id="updateQueryRequest" name="updateQueryRequest">
                Name: <input type="text" name="oldName"> <br /><br />
                New Name: <input type="text" name="newName"> <br /><br />
                New Lore: <input type="text" name="newLore"> <br /><br />

                <input type="submit" value="Update" name="updateSubmit"></p>
            </form>

            <hr />

            <h2>Count the Tuples in Region</h2>
            <form method="GET" action="region.php"> <!--refresh page when submitted-->
                <input type="hidden" id="countTupleRequest" name="countTupleRequest">
                <input type="submit" name="countTuples"></p>
            </form>

            <hr />

            <h2>Print Tuples in Region</h2>
            <form method="GET" action="region.php"> <!--refresh page when submitted-->
                <input type="hidden" id="printTupleRequest" name="printTupleRequest">
                Page Number: <input type="text" name="pageNum"> <br /><br />
                <input type="submit" value="Print" name="printTuples"></p>
            </form>
        </div>

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

            $statement = OCIParse($db_conn, $cmdstr);
            //There are a set of comments at the end of the file that describe some of the OCI specific functions and how they work

            if (!$statement) {
                echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
                $e = OCI_Error($db_conn); // For OCIParse errors pass the connection handle
                echo htmlentities($e['message']);
                $success = False;
                return;
            }

            $r = OCIExecute($statement, OCI_DEFAULT);
            if (!$r) {
                echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
                $e = oci_error($statement); // For OCIExecute errors pass the statementhandle
                echo htmlentities($e['message']);
                $success = False;
                return;
            }
            $success = True;
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
                return;
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
                return;
                }
            }
            $success = True;
            return $statement;
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
            global $db_conn, $success;

            $old_name = $_POST['oldName'];
            $new_name = $_POST['newName'];
            $new_lore = $_POST['newLore'];

            $result = executePlainSQL("SELECT * FROM Region WHERE name='" . $old_name . "'");

            while (($row = oci_fetch_row($result)) != false) {
                if ($new_name == NULL) {
                    $new_name = $row[0];
                }
                if ($new_lore == NULL) {
                    $new_lore = $row[1];
                }
            }

            // you need the wrap the old name and new name values with single quotations
            $updateSQL = "UPDATE Region SET name='";
            $updateSQL .= $new_name . "', lore='";
            $updateSQL .= $new_lore . "' WHERE name='";
            $updateSQL .= $old_name . "'";
            $result = executePlainSQL($updateSQL);
            if ($success) {
                echo "Successfully updated!";
            }
            OCICommit($db_conn);
        }
        function handleInsertRequest() {
            global $db_conn, $success;

            //Getting the values from user and insert data into the table
            $tuple = array (
                ":bind1" => $_POST['name'],
                ":bind2" => $_POST['lore']
            );

            $alltuples = array (
                $tuple
            );

            executeBoundSQL("insert into Region values (:bind1, :bind2)", $alltuples);
            if ($success) {
                echo "Successfully Added!";
            }
            OCICommit($db_conn);
        }

        function handleCountRequest() {
            global $db_conn;
            OCITerminate($db_conn);
            $result = executePlainSQL("SELECT Count(*) FROM Region");

            if (($row = oci_fetch_row($result)) != false) {
                echo "<br> The number of tuples in Regions: " . $row[0] . "<br>";
            }
        }

        function handlePrintRequest() {
            global $db_conn;

            $tuple = array (
                ":bind1" => $_GET['pageNum'] - 1
            );

            $alltuples = array (
                $tuple
            );

            $result = executeBoundSQL("SELECT * FROM Region ORDER BY Name OFFSET (:bind1)*10 ROWS FETCH NEXT 10 ROWS ONLY", $alltuples);
            printResult($result);
        }

        function printResult($result) {
            echo "<br>Retrieved data from Region Table:<br>";
            echo "<table>";
            echo "<tr>
                    <th>Name</th>
                    <th>Lore</th>
                    </tr>";

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row["NAME"] . "</td><td>" . $row["LORE"] . "</td></tr>";
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
                } else if (array_key_exists('printTuples', $_GET)) {
                    handlePrintRequest();
                }
                disconnectFromDB();
            }
        }

		if (isset($_POST['reset']) || isset($_POST['updateSubmit']) || isset($_POST['insertSubmit'])) {
            handlePOSTRequest();
        } else if (isset($_GET['countTupleRequest']) || isset($_GET['printTuples'])) {
            handleGETRequest();
        }
		?>
	</body>
</html>
