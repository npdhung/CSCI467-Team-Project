<?php
session_start();
?>
<html>
    <head>
        <title>Quote System - Group 2B</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>
<body>
    <h1>Quote System</h1>
    <h2>Quote Sanctioning</h2>
    <nav>
        <ul>
            <li><a href="main.php">Main Page</a></li>
            <li><a href="quote_processing.php">Process Quote</a></li>
        </ul>
    </nav>
    <hr>
    <?php
    $dbname = "mysql:host=blitz.cs.niu.edu:3306;dbname=csci467";
    $user = "student";
    $pass = "student";
    
    // Get the credentials to connect to local server or hopper
    include 'local_cred.php';
    
    try { // connect to the database
        $pdo = new PDO($dbname, $user, $pass);
        $pdo_local = new PDO($local_dbname, $lc_user, $lc_pass);
        
        echo "<br>";
        echo "<h3>Sanctioned Quote are Finalized Quote that is accepted by 
            Customer.</h3>";
        
        echo "<form action=\"quote_sanctioning.php\" method = GET>";
        
        echo "<label for='Name'>Select Quote ID to Sanction: </label>";
        echo "<select id='Name' name='qsa_id'>";
        $res = $pdo_local->query("SELECT Id FROM Quotes 
            WHERE Status = 'finalized'");
        while($fet = $res->fetch(PDO::FETCH_ASSOC)){
              $qsa_id = $fet["Id"];
              echo "<option value=".$qsa_id.">".$qsa_id."</option>";
        }
        echo "</select>";
        echo " <input type='submit' value='Select this Quote'> </form>";
        echo "(Only quotes with finalized status are available to be Sanctioned)";
        
        $pdo_local = new PDO($local_dbname, $lc_user, $lc_pass);
        
        // Unfinalize the quote
        if (isset($_GET["qsa_id"]))
        {
            $_SESSION["quote_id"] = $_GET["qsa_id"];
            $qid = $_SESSION["quote_id"];
            
            // Update status and total price
            $res = $pdo_local->exec("UPDATE Quotes
                SET Status = 'sanctioned'
                WHERE Id = $qid;");
            
            echo "<h4>Quote $qid status change to sanctioned,";
            echo " and will be ready to be process.</h4>";
        }
        
        echo "<h4>List of quote status:</h4>";

        // Print Quote status table
        $res = $pdo_local->query("SELECT Id, Status, TotalPrice, AssociateId
            FROM Quotes");
        echo "<table border=0 cellpadding=5 align=center>";
        echo "<tr><th>Quote ID</th><th>Status</th><th>TotalPrice</th><th>Associate ID</th>
            </tr>";
        while($fet = $res->fetch(PDO::FETCH_ASSOC)){
            echo"<tr>";
            $quote_id = $fet["Id"];
            $status = $fet["Status"];
            $total = $fet["TotalPrice"];
            $assoc_id = $fet["AssociateId"];
            echo "
            <td>$quote_id</td>
            <td>$status</td>
            <td>$total</td>
            <td>$assoc_id</td>";
            echo "</tr>";
        }
        echo "</table>";
    
    }
    catch(PDOexception $e) { // handle that exception
        echo "Connection to database failed: " . $e->getMessage();
    }
    ?>
</body>
</html>
    
        