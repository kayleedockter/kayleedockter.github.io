<?php
	session_start();
	include("ConnectionInfo.php");
	
	
    function redirect()
	{
		header("Location: hangmangame.php");
		exit;
	}
	
	$passmatch = FALSE; //If passwords match, this flag is set to true and inserts into db
	$userexist = FALSE; //If user exists, do not insert into db
	$redirect = FALSE;
	$user = $_POST["user"];
		
	//If create account has been clicked
	if (isset($_POST["submit"])) {

		if($_POST["pass"] === $_POST["rpass"]) {
			$passmatch = TRUE; 
			$pass = $_POST["pass"];
		} else {
			echo "Passwords do not match!";
		}
	//create connection
		$conn = new mysqli($servername, $username, $password, $dbname);
	//check connection
		if($conn->connect_error){
			die("Connection failed: " . $conn->connect_error);
		}
		//check if usre exists
		$selectSQL = "SELECT * FROM Users WHERE `Username` = '$user'";
		$userResult = $conn->query($selectSQL);
		if (mysqli_num_rows($userResult) > 0 && $user != "") {
			$userexist = TRUE;
			echo "User already exists!";
		} else { //If not, flag is set
			$userexist = FALSE;
		}
	}
	$rand = uniqid(mt_rand(), true);
    $salt=  substr($rand, 0, 5);
    $hashed = hash(sha512, $pass);
    $saltHashPass = $salt . $hashed;
	//echo "username: " . $user .  "<br> password?: " . $saltHashPass . "<br> salt: " . $salt . "<br> hash: " . $hashed . "<br>" ;
	
     //Passwords match and user exists condition
    if($passmatch === TRUE && $userexist === FALSE) {
		$randWordID = rand(1, 20);
		$sqlWord = "SELECT `Word` FROM `Words` WHERE `ID` = '$randWordID'";
		$word = "";
		$getWord = $conn->query($sqlWord); //Getting random word
		if ($getWord->num_rows > 0) {
			while ($row = $getWord->fetch_assoc()) {
				$word = $row["Word"];
				//echo "word " . $word . "<br>";
			}
		}

		$wordLength = strlen($word);
		$_SESSION["word"] = $word;
		$_SESSION["wordLength"] = $wordLength; //I don't think I need this
		$_SESSION["attempt"] = 0;
		$_SESSION["correct"] = 100;
		$_SESSION["attempt"] = 0; //Setting attempts to 0
		$_SESSION["correct"] = 100; //Setting correct count to large number
		$_SESSION["user"] = $user;
        $sql = "INSERT INTO Users (Username, Password, salt) VALUES ('$user', '$saltHashPass' , '$salt')";
		$result = $conn->query($sql);
		$conn->close();
		$redirect = TRUE;
		}

	//Redirects user directly to game
	if ($redirect == TRUE) {
  //Redirecting to game
	redirect();
	}
	
?>
<!DOCTYPE html>
<html>
<body>
<h1>Hangman Game</h1>
<h3>Sign Up</h3>
  <form action="signup.php" method="post">
    <p>Create Username: <input type="text" name="user"/></p>
    <p>Create Password: <input type="text" name="pass"/></p>
	<p>Re-enter Password: <input type="text" name="rpass"/></p>
    <input type="submit" name = "submit" value="Create Account"/>
  </form>
	<p>Already have an account? <a href = "hangmanlogin.php">Login</a></p>
</body>
</html>