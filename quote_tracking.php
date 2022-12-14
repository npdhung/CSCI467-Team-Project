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
    <h2>Quote Tracking</h2>
    <nav>
        <ul>
            <li><a href="main.php">Main Page</a></li>
            <li><a href="quote_tracking_edit.php">Edit Quote</a></li>
            <li><a href="quote_tracking_remove.php">Remove Quote</a></li>
            <li><a href="quote_finalizing.php">Finalize Quote</a></li>
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

        echo "<br>";
        echo "<h3>Associate creates new Quote.</h3>";
        $first = $_SESSION["assoc_first"];
        $last = $_SESSION["assoc_last"];
        echo "<h4>Plan Repair Services Portal welcomes Associate $first $last
          </h4>";
        
        echo "<form action=\"quote_tracking_new.php\" method = GET>";
        echo "<label for='Name'>Select customer: </label>";
        echo "<select id='Name' name='cid'>";
        $res = $pdo->query("SELECT name, id FROM customers");
        while($fet = $res->fetch(PDO::FETCH_ASSOC)){
              $name = $fet["name"];
              $cid = $fet["id"];
              echo "<option value=".$cid.">".$name."</option>";
        }
        echo "</select>";
        echo " <input type='submit' value='New Quote'> </form>";
        
        $res = $pdo->query("SELECT COUNT(*) FROM customers");
        $count = $res->fetchColumn();
        echo "$count current customers.";
        echo "<h4>Click on the link above to skip Adding New Quote and go to
        Edit Quote or Remove a Quote.</h4>";
        echo "<h4>List of current quotes for Associate $first $last:</h4>";
        
        $assoc_id = $_SESSION["assoc_id"];
        $pdo_local = new PDO($local_dbname, $lc_user, $lc_pass);
        // ItemNumber -> AssocId
        $res = $pdo_local->query("SELECT QuoteId, CustomerId, ItemNumber, 
          ItemDescription, Quantity, ItemPrice 
            FROM LineItems, Quotes 
            WHERE LineItems.QuoteId = Quotes.Id 
            AND Quotes.AssociateId = $assoc_id");
        
        echo "<table border=0 cellpadding=5 align=center>";
        echo "<tr><th>QuoteID</th><th>CustomerID</th><th>Item ID</th>
          <th>Item Description</th><th>Quantity</th><th>Item Price</th></tr>";
        while($fet = $res->fetch(PDO::FETCH_ASSOC)){
            echo"<tr>";
            $quote_id = $fet["QuoteId"];
            $cust_id = $fet["CustomerId"];
            $part_id = $fet["ItemNumber"];
            $desc = $fet["ItemDescription"];
            $qty = $fet["Quantity"];
            $price = $fet["ItemPrice"];
            echo "
            <td>$quote_id</td>
            <td>$cust_id</td>
            <td>$part_id</td>
            <td>$desc</td>
            <td>$qty</td>
            <td>$price</td>";
            echo "</tr>";
        }
        echo "</table>";

        echo "<br><br>";
        // modify count distinc quote id
        $res = $pdo_local->query("SELECT COUNT(DISTINCT Id) FROM Quotes");
        $count = $res->fetchColumn();
        $_SESSION["max_quote_id"] = $count;

    }
    catch(PDOexception $e) { // handle that exception
        echo "Connection to database failed: " . $e->getMessage();
    }
    ?>
</body>
</html>
