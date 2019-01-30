<?php
	if(isset($_GET['hostname']))
		die(`hostname`);

    /*
    Ensure the following variables are defined in /var/www or outside the document root.

    $database_hostname = "blah.asdf.us-east-1.rds.amazonaws.com";
    $database_username = "root";
    $database_password = "password";
    $database = "myapp";
    */

    require("../credentials.php");

    function m_error($link)
    {
        echo "Error, count not perform query!<br />";
        echo "Error Message: " . mysqli_error($link);
    }

	$connection = mysqli_init();

    if(!mysqli_real_connect($connection, $database_hostname, $database_username, $database_password, $database, 3306, NULL, MYSQLI_CLIENT_SSL))
    {
        echo "Could not connect to database!<br />";
        echo "Error Number: " . mysqli_connect_errno() . "<br />";
        echo "Error Message: " . mysqli_connect_error();

        exit;
    }

    if(isset($_POST) && isset($_POST['message']))
    {
        $message = mysqli_real_escape_string($connection, $_POST['message']);
        $query = "INSERT INTO `records` (`message`) VALUES('$message')";

        if(!mysqli_query($connection, $query))
        {
            m_error($connection);
            exit;
        }
    }

    $query = "SELECT * FROM `records`";

    if(!($result = mysqli_query($connection, $query)))
    {
        m_error($connection);
        exit;
    }
?>

<html>
<head>
  <title>Hello!</title>
</head>
<body style="font-family: Verdana,Tahoma,sans-serif">
    <h2>Welcome to MyApp! Post a message to the board using the form at the bottom!</h2>
    <div style="border: 1px solid #BBB; padding: 5px; font-weight: bold; font-size: small">
        Message Board
    </div>
    <div style="border-left: 1px solid #BBB; border-right: 1px solid #BBB; border-bottom: 1px solid #BBB; padding: 5px; font-size: small">
    <?php
        if(mysqli_num_rows($result) == 0)
            echo "No records in database!";
        else
        {
            while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
                echo "{$row['message']}<br />";
        }
    ?>
    </div>

    <form name="message" action="index.php" method="POST" style="padding-top: 15px">
        Message:<br />
        <input type="text" name="message" style="length: 300px" />
        <input type="submit" name="submit" value="Submit" />
    </form>

	<div style="font-size: small; font-size: small; font-color: #DDD">
		Served from <?php echo `hostname`; ?>.
	</div>
</body>
</html>
