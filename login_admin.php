<html>
    <head>
        <title>Quote System - Group 2B</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>
<body>
    <h1>Quote System</h1>
    <h2>Admin Login</h2>
    <nav>
        <ul>
            <li><a href="main.php">Main Page</a></li>
        </ul>
    </nav>
    <hr>
    <?php
    session_start();
    $local_dbname = "mysql:host=courses;dbname=z1924897";
    $lc_user = "z1924897";
    $lc_pass = "1979Jan05";
    try {
        $pdo_local = new PDO($local_dbname, $lc_user, $lc_pass);
        echo "
        <h2>Try AydeF & Assoc5</h2><br>
        ";

        echo "
        <form action=\"login_admin.php\" method = GET>
        <label for='usrname'>Username</label>
        <input type='text' size='8' id='usrname' name='usrname' required/>
        <label for='psw'>Password</label>
        <input type='password' size='8' id='psw' name='psw' required />
        <input type='submit' value='Login'>
        </form>";

        if($_GET != NULL){
          // echo $_GET['usrname']; echo $_GET['psw'];
          // Search database for username and Password
          $username_in = $_GET["usrname"];
          $res = $pdo_local->query("SELECT Id, FirstName, LastName, Password ,Role 
              FROM Associates WHERE UserName = '$username_in'");
          while($fet = $res->fetch(PDO::FETCH_ASSOC))
          {
            $assoc_id = $fet["Id"];
            $assoc_first = $fet["FirstName"];
            $assoc_last = $fet["LastName"];
            $pass = $fet["Password"];
            $rol=$fet["Role"];
           }

          if (($_GET["psw"] == $pass) && ($rol=="admin" || $rol=="manager")){
            $_SESSION["assoc_id"] = $assoc_id;
            $_SESSION["assoc_first"] = $assoc_first;
            $_SESSION["assoc_last"] = $assoc_last;
            header('location: home.php');
          }
          else{ echo "Invalid username and/or password. Try again!";}
        }
    }

    catch(PDOexception $e) {
        echo "Connection to database failed: " . $e->getMessage();
    }
    ?>
</body>
<style>
input[type=text], input[type=password] {
  width: 100%;
  padding: 12px 20px;
  margin: 8px 0;
  display: inline-block;
  border: 1px solid #ccc;
  box-sizing: border-box;
}

input[type=submit] {
  background-color: #04AA6D;
  color: white;
  padding: 14px 20px;
  margin: 8px 0;
  border: none;
  cursor: pointer;
  width: 100%;
}

button:hover {
  opacity: 0.8;
}

.cancelbtn {
  width: auto;
  padding: 10px 18px;
  background-color: #f44336;
}



.container {
  padding: 16px;
}

span.psw {
  float: right;
  padding-top: 16px;
}
@media screen and (max-width: 300px) {
  span.psw {
     display: block;
     float: none;
  }
  .cancelbtn {
     width: 100%;
  }
}
</style>
</html>
