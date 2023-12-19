<html>
    <head>
        <title>Genshin Relationship Database</title>
        <link rel="stylesheet" type="text/css" href="../style.css">
    </head>

    <body>
        <div class="navbar">
            <a href="../genshin.php">Home</a>
            <div class="dropdown">
                <button onclick="window.location.href='../entity/character.php'" 
                        class="dropbtn" >Entities
                </button>
                <div class="dropdown-content">
                    <a href="../entity/character.php">Character</a>
                    <a href="../entity/material.php">Material</a>
                    <a href="../entity/ascensionMaterial.php">Ascension Material</a>
                    <a href="../entity/consumable.php">Consumable</a>
                    <a href="../entity/region.php">Region</a>
                    <a href="../entity/subregion.php">Subregion</a>
                    <a href="../entity/enemies.php">Enemies</a>
                    <a href="../entity/boss.php">Boss</a>
                    <a href="../entity/weapon.php">Weapon</a>
                    <a href="../entity/artifactSet.php">ArtifactSet</a>
                    <a href="../entity/artifact.php">Artifacts</a>
                    <a href="../entity/stat.php">Stat</a>
                </div>
            </div> 
            <div class="dropdown">
                <button onclick="window.location.href='bossDrops.php'" 
                        class="dropbtn active" >Relationships 
                </button>
                <div class="dropdown-content">
                    <a href="bossDrops.php">Boss Drops</a>
                    <a href="enemyFoundAt.php">Enemy Location</a>
                    <a href="enemyDrops.php">Enemy Drops</a>
                    <a href="characterCanWield.php">Character Weapon Ranking</a>
                    <a href="characterArtifacts.php">Character Artifact Ranking</a>
                    <a href="requiredMaterialForWeapon.php">Weapon Materials</a>
                    <a href="materialsFoundAt.php">Material Location</a>
                    <a href="characterInteractions.php" class="active">Character Interactions</a>
                    <a href="battleInRegion.php">Battle Locations</a>
                    <a href="consumableBoosts.php">Consumable Boosts</a>
                    <a href="requiredMaterialForCharacter.php">Character Ascension Materials</a>
                    <a href="artifactEnhances.php">Artifact Stats</a>
                </div>
            </div>
            <a href="../search.php">Search</a> 
        </div>

        <div style="padding:20px; margin-top:30px">

            <h2>Insert Values into Character Interactions</h2>
            <form method="POST" action="characterInteractions.php"> <!--refresh page when submitted-->
                <input type="hidden" id="insertQueryRequest" name="insertQueryRequest">
                Character One: <input type="text" name="characterOneName"> <br /><br />
                Character Two: <input type="text" name="characterTwoName"> <br /><br />
                Battle: <input type="text" name="battle"> <br /><br />
                Contract: <input type="text" name="contract"> <br /><br />
                Friend Name: <input type="text" name="friendName"> <br /><br />

                <input type="submit" value="Insert" name="insertSubmit"></p>
            </form>

            <hr />

            <h2>Update Values in Character Interactions</h2>
            <p>The values are case sensitive and if you enter in the wrong case, the update statement will not do anything.</p>

            <form method="POST" action="characterInteractions.php"> <!--refresh page when submitted-->
                <input type="hidden" id="updateQueryRequest" name="updateQueryRequest">
                Old Character One Name: <input type="text" name="oldCharacterOneName"> <br /><br />
                New Character One Name: <input type="text" name="newCharacterOneName"> <br /><br />
                Old Character Two Name: <input type="text" name="oldCharacterTwoName"> <br /><br />
                New Character Two Name: <input type="text" name="newCharacterTwoName"> <br /><br />
                Old Battle: <input type="text" name="oldBattle"> <br /><br />
                New Battle: <input type="text" name="newBattle"> <br /><br />
                New Contract: <input type="text" name="newContract"> <br /><br />
                New Friend: <input type="text" name="newFriend"> <br /><br />

                <input type="submit" value="Update" name="updateSubmit"></p>
            </form>

            <hr />

            <h2>Count the Tuples in Character Interactions</h2>
            <form method="GET" action="characterInteractions.php"> <!--refresh page when submitted-->
                <input type="hidden" id="countTupleRequest" name="countTupleRequest">
                <input type="submit" name="countTuples"></p>
            </form>

            <hr />

            <h2>Print Tuples in Character Interactions</h2>
            <form method="GET" action="characterInteractions.php"> <!--refresh page when submitted-->
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

            $old_characterOneName = $_POST['oldCharacterOneName'];
            $new_characterOneName = $_POST['newCharacterOneName'];
            $old_characterTwoName = $_POST['oldCharacterTwoName'];
            $new_characterTwoName = $_POST['newCharacterTwoName'];
            $old_battle = $_POST['oldBattle'];
            $new_battle = $_POST['newBattle'];
            $new_contract = $_POST['newContract'];
            $new_friend = $_POST['newFriend'];


            $result = executePlainSQL("SELECT * FROM CharacterInteractions WHERE char1='" . $old_characterOneName . "' AND char2='" . $old_characterTwoName . "' AND battle='" . $old_battle . "'");

            while (($row = oci_fetch_row($result)) != false) {
                if ($new_characterOneName == NULL) {
                    $new_name = $row[0];
                }
                if ($new_characterTwoName == NULL) {
                    $new_name = $row[1];
                }
                if ($newBattle == NULL) {
                    $newBattle = $row[2];
                }
                if ($new_contract == NULL) {
                    $new_contract = $row[3];
                }
                if ($new_friend == NULL) {
                    $new_friend = $row[4];
                }
            }

            // you need the wrap the old name and new name values with single quotations
            $updateSQL = "UPDATE CharacterInteractions SET Char1='";
            $updateSQL .= $new_characterOneName . "', Char2='";
            $updateSQL .= $new_characterTwoName . "', battle='";
            $updateSQL .= $newBattle . "', contract='";
            $updateSQL .= $new_contract . "', friend='";
            $updateSQL .= $new_friend . "' WHERE char1='";
            $updateSQL .= $old_characterOneName . "' AND char2='";
            $updateSQL .= $old_characterTwoName . "' AND battle='";
            $updateSQL .= $old_battle . "'";
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
                ":bind1" => $_POST['characterOneName'],
                ":bind2" => $_POST['characterTwoName'],
                ":bind3" => $_POST['battle'],
                ":bind4" => $_POST['contract'],
                ":bind5" => $_POST['friendName'],

            );

            $alltuples = array (
                $tuple
            );

            executeBoundSQL("insert into CharacterInteractions values (:bind1, :bind2, :bind3, :bind4, :bind5)", $alltuples);
            if ($success) {
                echo "Successfully Added!";
            }
            OCICommit($db_conn);
        }

        function handleCountRequest() {
            global $db_conn;

            $result = executePlainSQL("SELECT Count(*) FROM CharacterInteractions");

            if (($row = oci_fetch_row($result)) != false) {
                echo "<br> The number of tuples in Character Interactions Table: " . $row[0] . "<br>";
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

            $result = executeBoundSQL("SELECT * FROM CharacterInteractions ORDER BY Char1, Char2, Battle OFFSET (:bind1)*10 ROWS FETCH NEXT 10 ROWS ONLY", $alltuples);
            printResult($result);
        }

        function printResult($result) {
            echo "<br>Retrieved data from Boss Drops Table:<br>";
            echo "<table>";
            echo "<tr>
                    <th>Character One Name</th>
                    <th>Character Two Name</th>
                    <th>Battle</th>
                    <th>Contract</th>
                    <th>Friend</th>
                    </tr>";

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                $table = "<tr><td>"; 
                $table .= $row["CHAR1"] . "</td><td>";
                $table .= $row["CHAR2"] . "</td><td>";
                $table .= $row["BATTLE"] . "</td><td>"; 
                $table .= $row["CONTRACT"] . "</td><td>"; 
                $table .= $row["FRIEND"] .  "</td><tr>";
                echo $table;
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