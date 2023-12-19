<html>
    <head>
        <title>Genshin Entity Database</title>
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

        <div style="padding:20px; margin-top:30px">
            <h2>Insert Values into AscensionMaterial</h2>
            <form method="POST" action="search.php"> <!--refresh page when submitted-->
                <input type="hidden" id="insertQueryRequest" name="insertQueryRequest">
                Name: <input type="text" name="name"> <br /><br />
                Tier: <input type="number" name="tier"> <br /><br />
                Type: <input type="text" name="type"> <br /><br />
                <input type="submit" value="Insert" name="insertSubmit"></p>
            </form>
        </div>

        <div style="padding:20px; margin-top:30px">
            <h2>Projection: Get a Character's Basic Information</h2>
            <form method="GET" action="search.php"> <!--refresh page when submitted-->
                <input type="hidden" id="projectionQueryRequest" name="projectionQueryRequest">
                <input type="submit" name="projectionSubmit"></p>
            </form>
        </div>

        <div style="padding:20px; margin-top:30px">
            <h2>Selection: Find character by elemental type</h2>
            <form method="GET" action="search.php"> <!--refresh page when submitted-->
                <input type="hidden" id="selectionQueryRequest" name="selectionQueryRequest">
                Type: <input type="text" name="elementalType"> <br /><br />
                <input type="submit" name="selectionSubmit"></p>
            </form>
        </div>

        <div style="padding:20px; margin-top:30px">
            <h2>Aggregation (Group By): Count Elemental Types</h2>
            <form method="GET" action="search.php"> <!--refresh page when submitted-->
                <input type="hidden" id="groupQueryRequest" name="groupQueryRequest">
                <input type="submit" name="groupSubmit"></p>
            </form>
        </div>

        <div style="padding:20px; margin-top:30px">
            <h2>Delete a material</h2>
            <form method="POST" action="search.php"> <!--refresh page when submitted-->
                <input type="hidden" id="deleteQueryRequest" name="deleteQueryRequest">
                Name: <input type="text" name="deleteTypeName"> <br /><br />
                <input type="submit" value="Delete" name="deleteSubmit"></p>
            </form>
        </div>

        <div style="padding:20px; margin-top:30px">
            <h2>Join Enemies, Region, EnemyFoundAt, Stat</h2>
            <p>Display statistics of every enemies in a region</p>
            <form method="GET" action="search.php"> <!--refresh page when submitted-->
                <input type="hidden" id="joinQueryRequest" name="joinQueryRequest">
                Location: <input type="text" name="region_name"> <br /><br />
                <input type="submit" name="joinSubmit"></p>
            </form>
        </div>

        <div style="padding:20px; margin-top:30px">
            <h2>Aggregation (Having)</h2>
            <p>Find materials needed by more than 1 character to ascend over some quantity</p>
            <form method="GET" action="search.php"> <!--refresh page when submitted-->
                <input type="hidden" id="aggregateHavingRequest" name="aggregateHavingRequest">
                Quantity: <input type="number" name="quantityNumber"> <br /><br />
                <input type="submit" name="aggregateHavingSubmit"></p>
            </form>
        </div>

        <div style="padding:20px; margin-top:30px">
            <h2>Update some artifact stat ID</h2>
            <form method="POST" action="search.php"> <!--refresh page when submitted-->
                <input type="hidden" id="updateQueryRequest" name="updateQueryRequest">
                Stat ID: <input type="text" name="updateStatID"> <br /><br />
                Artifact: <input type="text" name="updateArtifactName"> <br /><br />
                <input type="submit" value="Update" name="updateSubmit"></p>
            </form>
        </div>

        <div style="padding:20px; margin-top:30px">
            <h2>Division</h2>
            <p>Find the name of Enemies that are in every region</p>
            <form method="GET" action="search.php"> <!--refresh page when submitted-->
                <input type="hidden" id="divisionQueryRequest" name="divisionQueryRequest">
                <input type="submit" name="divisionSubmit"></p>
            </form>
        </div>

        <div style="padding:20px; margin-top:30px">
            <h2>Nested Aggregation (Having)</h2>
            <p>Find weapon type, such that weapons of such type have higher mean attack stats than overall mean</p>
            <form method="GET" action="search.php"> <!--refresh page when submitted-->
                <input type="hidden" id="nestedAggregationRequest" name="nestedAggregationRequest">
                <input type="submit" name="nestedAggregationSubmit"></p>
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

            $row = OCI_Fetch_Array($statement, OCI_BOTH);
            $num_rows = oci_num_rows($statement);
            if (!empty($row) || $num_rows > 0) {
                echo "Successful query";
                $statement = OCIParse($db_conn, $cmdstr);
                foreach ($list as $tuple) {
                    foreach ($tuple as $bind => $val) {
                        //echo $val;
                        //echo "<br>".$bind."<br>";
                        OCIBindByName($statement, $bind, $val);
                        unset ($val); //make sure you do not remove this. Otherwise $val will remain in an array object wrapper which will not be recognized by Oracle as a proper datatype
                    }
                    $r = OCIExecute($statement, OCI_DEFAULT);
                }
                return $statement;
            } else {
                echo "An error occured. No data is modified (if a modification request is sent) or no data found.";
            }
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

        function handleInsertRequest() {
            global $db_conn;
            $tuple = array (
                ":name" => sanitizeInput($_POST['name']),
                ":tier" => sanitizeInput($_POST['tier']),
                ":type" => sanitizeInput($_POST['type']),
            );
            $alltuples = array(
                $tuple
            );

            $result = executeBoundSQL("
            INSERT INTO AscensionMaterial (
                Name, 
                Tier, 
                AscensionType
            ) 
            VALUES (
                :name, 
                :tier, 
                :type)
            ", $alltuples);

            printInsertResult();
            OCICommit($db_conn);
        }

        function printInsertResult() {
            $result = executePlainSQL("SELECT * FROM AscensionMaterial");
            echo "<table>";
            echo "<tr>
                    <th>Name</th>
                    <th>Tier</th>
                    <th>Ascention Type</th>
                    </tr>";

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                $table = "<tr><td>";
                $table .= $row[0] . "</td><td>";
                $table .= $row[1] . "</td><td>";
                $table .= $row[2] . "</td></tr>";
                echo $table;
            }

            echo "</table>";
        }

        function handleProjectionRequest() {
            global $db_conn;
            
            $result = executePlainSQL("SELECT Name, ElementalType FROM Character");
            
            printProjectionRequest($result);
        }

        function printProjectionRequest($result) {
            echo "<br>Retrieved data from Character table:<br>";
            echo "<table>";
            echo "
				<tr>
					<th>Name</th>
                    <th>Elemental Type</th>
				</tr>
			";

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                $table = "<tr><td>";
                $table .= $row[0] . "</td><td>";
                $table .= $row[1] . "</td></tr>";
                echo $table;
            }

            echo "</table>";
        }

        function handleSelectionRequest() {
            global $db_conn;

            $tuple = array (
                ":elemental_type" => sanitizeInput($_GET['elementalType']),
            );

            $alltuples = array (
                $tuple
            );

            $result = executeBoundSQL("
            SELECT *
            FROM Character
            WHERE ElementalType = :elemental_type
            ", $alltuples);

            printSelectionResult($result);
        }

        function printSelectionResult($result) {
            echo "<br>Retrieved data from Character table:<br>";
            echo "<table>";
            echo "
				<tr>
                    <th>Name</th>
					<th>ElementalType</th>
                    <th>Gender</th>
                    <th>WeaponType</th>
                    <th>Tier</th>
                    <th>Description</th>
                    <th>StatID</th>
                    <th>RegionName</th>
				</tr>
			";

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                $table = "<tr><td>";
                $table .= $row[0] . "</td><td>";
                $table .= $row[1] . "</td><td>";
                $table .= $row[2] . "</td><td>";
                $table .= $row[3] . "</td><td>";
                $table .= $row[4] . "</td><td>";
                $table .= $row[5] . "</td><td>";
                $table .= $row[6] . "</td><td>";
                $table .= $row[7] . "</td></tr>";
                echo $table;
            }

            echo "</table>";
        }

        function handleGroupRequest() {
            global $db_conn;

            $result = executePlainSQL("
                SELECT ElementalType, COUNT(*)
                FROM Character
                GROUP BY ElementalType
            ");

            printGroupRequest($result);
        }
        
        function printGroupRequest($result) {
            echo "<br>Retrieved data from Character table:<br>";
            echo "<table>";
            echo "
				<tr>
                    <th>Element</th>
					<th>Number of element types</th>
				</tr>
			";

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                $table = "<tr><td>";
                $table .= $row[0] . "</td><td>";
                $table .= $row[1] . "</td></tr>";
                echo $table;
            }

            echo "</table>";
        }

        function handleDeleteRequest() {
            global $db_conn;

            $tuple = array (
                ":name" => sanitizeInput($_POST['deleteTypeName']),
            );

            $alltuples = array (
                $tuple
            );

			executeBoundSQL("
                DELETE
                FROM Material M
                WHERE M.Name = :name
			", $alltuples);

			printDeleteRequestResult();
            
            OCICommit($db_conn);
        }

        function printDeleteRequestResult() {
            echo "<br>Retrieved data from Material table:<br>";
            $result = executePlainSQL("SELECT * FROM Material");
            echo "<table>";
            echo "<tr>
                    <th>Name</th>
                    <th>Description</th>
                    </tr>";

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                $table = "<tr><td>";
                $table .= $row[0] . "</td><td>";
                $table .= $row[1] . "</td></tr>";
                echo $table;
            }

            echo "</table>";

            echo "<br>Retrieved data from Consumable table:<br>";
            $result2 = executePlainSQL("SELECT * FROM Consumable");
            echo "<table>";
            echo "<tr>
                    <th>Name</th>
                    <th>Recipe</th>
                    </tr>";

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                $table = "<tr><td>";
                $table .= $row[0] . "</td><td>";
                $table .= $row[1] . "</td></tr>";
                echo $table;
            }

            echo "</table>";
        }

        function handleJoinRequest() {
            global $db_conn;

            $tuple = array (
                ":region_name" => sanitizeInput($_GET['region_name']),
            );

            $alltuples = array (
                $tuple
            );

            $result = executeBoundSQL("
            SELECT E.Name, S.Defense, S.AttackDMG, S.HP
            FROM Enemies E, EnemyFoundAt F, Stat S
            WHERE E.Name = F.EnemyName
                AND E.StatID = S.ID
                AND F.RegionName = :region_name
            ", $alltuples);

            printJoinResult($result);
        }

        function printJoinResult($result) {
            echo "<br>Retrieved data from Enemies, Region, EnemyFoundAt, Stat table:<br>";
            echo "<table>";
            echo "
				<tr>
					<th>Enemy</th>
					<th>DEF</th>
                    <th>ATK</th>
                    <th>HP</th>
				</tr>
			";

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                $table = "<tr><td>";
                $table .= $row[0] . "</td><td>";
                $table .= $row[1] . "</td><td>";
                $table .= $row[2] . "</td><td>";
                $table .= $row[3] . "</td></tr>";
                echo $table;
            }

            echo "</table>";
        }

        function handleAggregateHavingRequest() {
            global $db_conn;

            $tuple = array (
                ":quantityNumber" => sanitizeInput($_GET['quantityNumber']),
            );

            $alltuples = array (
                $tuple
            );

            $result = executeBoundSQL("
            SELECT M.AscensionMaterialName
            FROM RequiredMaterialForCharacter M
            WHERE M.Quantity > :quantityNumber
            GROUP BY M.AscensionMaterialName
            HAVING COUNT(*) > 1
            ", $alltuples);
            echo $result;
            printAggregateHavingRequest($result);
        }

        function printAggregateHavingRequest($result) {
            echo "<br>Retrieved data from RequiredMaterialForCharacter table:<br>";
            echo "<table>";
            echo "
				<tr>
					<th>Material</th>
				</tr>
			";

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo $row[0];
                $table = "<tr><td>";
                $table .= $row[0] . "</td></tr>";
                echo $table;
            }

            echo "</table>";
        }

        function handleUpdateRequest() {
            global $db_conn;

            $tuple = array (
                ":stat_id" =>sanitizeInput($_POST['updateStatID']),
                ":artifact_name" => sanitizeInput($_POST['updateArtifactName']),
            );

            $alltuples = array (
                $tuple
            );

            $result = executeBoundSQL("
            UPDATE Enhances
            SET StatID= :stat_id
            WHERE ArtifactName = :artifact_name
            ", $alltuples);

            printUpdateResult();
            OCICommit($db_conn);
        }

        function printUpdateResult() {
            $result = executePlainSQL("SELECT * FROM Enhances");
            echo "<table>";
            echo "<tr>
                    <th>Stat ID</th>
                    <th>Artifact Name</th>
                    </tr>";

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                $table = "<tr><td>";
                $table .= $row[0] . "</td><td>";
                $table .= $row[1] . "</td></tr>";
                echo $table;
            }
            
            echo "</table>";
        }

        function handleDivisionRequest() {
            global $db_conn;

            $result = executePlainSQL("
            SELECT E.Name
            FROM Enemies E
            WHERE NOT EXISTS (
                SELECT RegionName
                FROM EnemyFoundAt
                MINUS
                SELECT RegionName
                FROM EnemyFoundAt E2
                WHERE E2.EnemyName = E.Name
            )
            ");

            printDivisionResult($result);
        }

        function printDivisionResult($result) {
            echo "<br>Retrieved data from Enemies table:<br>";
            echo "<table>";
            echo "
				<tr>
					<th>Enemy Name</th>
				</tr>
			";

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                $table = "<tr><td>";
                $table .= $row[0] . "</td></tr>";
                echo $table;
            }

            echo "</table>";
        }
        
        function handleNestedAggregationRequest() {
            global $db_conn;

            $result = executePlainSQL("
            SELECT W1.WeaponType
            FROM Weapon W1
            GROUP BY W1.WeaponType
            HAVING AVG(W1.Dmg) >= (SELECT AVG(W2.Dmg) FROM Weapon W2)
            ");

            printNestedAggregationResult($result);
        }

        function printNestedAggregationResult($result) {
            echo "<br>Retrieved data from Weapon table:<br>";
            echo "<table>";
            echo "
				<tr>
					<th>Weapon Type</th>
				</tr>
			";

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                $table = "<tr><td>";
                $table .= $row[0] . "</td></tr>";
                echo $table;
            }

            echo "</table>";
        }

        function sanitizeInput($input) {
            return preg_replace('/;/', '', $input);
        }
        
        // HANDLE ALL GET ROUTES
	// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
        function handleRequest() {
            if (connectToDB()) {
                if (array_key_exists('insertQueryRequest', $_POST)) {
                    handleInsertRequest();
                } else if (array_key_exists('projectionQueryRequest', $_GET)) {
                    handleProjectionRequest();
                } else if (array_key_exists('selectionQueryRequest', $_GET)) {
                    handleSelectionRequest();
                } else if (array_key_exists('groupQueryRequest', $_GET)) {
                    handleGroupRequest();
                } else if (array_key_exists('deleteQueryRequest', $_POST)) {
                    handleDeleteRequest();
                } else if (array_key_exists('joinQueryRequest', $_GET)) {
                    handleJoinRequest();
                } else if (array_key_exists('aggregateHavingRequest', $_GET)) {
                    handleAggregateHavingRequest();
                } else if (array_key_exists('updateQueryRequest', $_POST)) {
                    handleUpdateRequest();
                } else if (array_key_exists('divisionQueryRequest', $_GET)) {
                    handleDivisionRequest();
                } else if (array_key_exists('nestedAggregationRequest', $_GET)) {
                    handleNestedAggregationRequest();
                }

                disconnectFromDB();
            }
        }
        handleRequest();
		?>
	</body>
</html>
