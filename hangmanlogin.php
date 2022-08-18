
<!DOCTYPE html>
<html>
<h1>Hangman Game</h1>
<body>
	<?php
	session_start();
	include("ConnectionInfo.php");
	$_SESSION["started"] = 1;
    $user = $_POST["user"];
    $pass = $_POST["pass"];
		
		//Redirect function
	function redirect()
	{
		header("Location: hangmangame.php");
		exit;
	}

	if (isset($_POST["login"])) {
		//create connection
		$conn = new mysqli($servername, $username, $password, $dbname);
		//check connection
		if($conn->connect_error){
			die("Connection failed: " . $conn->connect_error);
		}
		//retrieve the salt for this user from the db
		$sqlSalt = "SELECT `salt` FROM Users WHERE `Username` = '$user'";
		$getSalt = $conn->query($sqlSalt);
		if($getSalt->num_rows > 0){
			while($row = $getSalt->fetch_assoc()){
				$salt = $row["salt"];
			}
		}
		$hashed = hash(sha512, $pass);
		//echo "hashenteredpass: " . $hashed . "<br>";
		$saltHashPass = $salt . $hashed;
		//echo "enteredpass: " . $saltHashPass . "<br>";
		
		//generate random number to grab random word ID with
		$randWordID = rand(1, 20);
		$sqlWord = "SELECT `Word` FROM `Words` WHERE `ID` = '$randWordID'";
		$wordPass = "";
		//check to make sure user and pass entered are in the db
		$sql = "SELECT * FROM `Users` WHERE `Username` = '$user' AND `Password` = '$saltHashPass'";
		$result = $conn->query($sql);
		if(mysqli_num_rows($result) == 0){
			echo "Sorry... Username and/or Password not found!";
		} else {
			$getWord = $conn->query($sqlWord);//Getting random word
			if($getWord->num_rows > 0){
				while($row = $getWord->fetch_assoc()){
				 $wordPass = $row["Word"];
				  echo "word " . $wordPass . "<br>";
				}
			}
			$wordLengthPass = strlen($wordPass);
			$_SESSION["user"] = $user;
			$_SESSION["word"] = $wordPass;
			$_SESSION["wordLength"] = $wordLengthPass;
			$_SESSION["attempt"] = 0;
			$_SESSION["correct"] = 100;
			redirect();
		}	
		$conn->close();
	}
    
	?>
	<h3>Login</h3>
  <form action="hangmanlogin.php".php" method="post">
    <p>Username: <input type="text" name="user"/></p>
    <p>Password: <input type="text" name="pass"/></p>
    <input type="submit" name = "login" value="Login"/>
  </form>
  <p>Need an account? <a href = "signup.php">Sign up</a></p>
</body>
</html>