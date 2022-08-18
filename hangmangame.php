<!DOCTYPE html>
<html>	
<body>
    <h1>Hangman Game</h1>
    <br>
    
    <?php
    session_start();
    include("ConnectionInfo.php");
	
	$user = $_SESSION["user"];
    $word = $_SESSION["word"];
	$wordLength = strlen($_SESSION["word"]);
	$score = 0;
	
    $winCondition = FALSE;
    $loseCondition = FALSE;
	
		//STANDARD GAMEPLAY CONDITION
	if ($loseCondition == FALSE && $winCondition == FALSE) {
		
		//Storing the amount of attempts
		if (isset($_SESSION["attempt"])) {
			//get it
			$attempt = $_SESSION["attempt"];
		} else {
			//set a default value if not isset
			$attempt = 0;
		}

		//Storing the amount of correct letters
		if (isset($_SESSION["correctCount"])) {
			//get it
			$correctCount = $_SESSION["correctCount"];
		} else {
			//set a default value if not isset
			$correctCount = $wordLength; //This decrements every time a correct letter is guess, used for win condition
		}
		//Displays letter guessed
		if (isset($_GET['LetterGuessed'])) {
			$CurrentGuess = $_GET['LetterGuessed'];
			$_SESSION["guesses"] .= $CurrentGuess;
			echo "<p>You guessed the letter <b>" . $CurrentGuess . "<br>";

			//increasing session attempts
			$attempt++;
			$_SESSION["attempt"] = $attempt;
		}

		//Displays all letters guessed
		if (isset($_GET['OldLettersGuessed'])) {
			$AllLettersGuessed = $_GET['OldLettersGuessed'] . $_GET['LetterGuessed'];
			echo "<p>All guessed letters: <b>" . $AllLettersGuessed . "<br>";
		}

		//Generates underscores for length of word
		for ($i = 0; $i < $wordLength; $i++) {
			//Converting guessed letters into an array of characters
			$chars = str_split($AllLettersGuessed);
			if (in_array($word[$i], $chars)) { //checks letter matches a letter in word
				echo $word[$i]; //if letter exists, fill it
				$correctCount--;
				$_SESSION["correct"] = $correctCount;
			} else {
				echo "_"; //if not, place an underscore
			}
			echo " "; //space for readability
		}
		
	}

	echo "<br> <br> <br>";
	echo "ATTEMPTS: " . $_SESSION["attempt"] . "/12";
	
	$loseCondition = LossCheck($attempt);
	$winCondition = WinCheck($correctCount);
	
	//LOSE CONDITION
	if ($loseCondition == TRUE) {
		echo "<br>";
		echo "Sorry, the word was $word. Please play again!";
		$_SESSION["attempt"] = 0; //Resetting attempts
		echo "<br> <br>";
		echo "<h3>**SCOREBOARD**</h3>";
		echo "----------------------------------------";
		DisplayScores($servername, $username, $password, $dbname);
		echo "----------------------------------------";
		
	}
	//WIN CONDITION
	if ($winCondition == TRUE) {
		echo "<br>";
		echo "The word is $word <br>";
		echo "YOU WIN! GREAT JOB! <br>";
		$score = (12 - $attempt) + $wordLength;
		echo "SCORE: " . $score . "<br>";

		//call function to input score into table
		InputScore($servername, $username, $password, $dbname, $score, $user);
		echo "<br> <br>";
		echo "<h3>**SCOREBOARD**</h3>";
		echo "----------------------------------------";
		DisplayScores($servername, $username, $password, $dbname); //already created above
		echo "----------------------------------------";
		
	}
		
	
   
   
	//**FUNCTIONS**
	 //Loss and Win functions
    function LossCheck($attempt) {
        if ($attempt > 12){
            return TRUE;
        }
        else {
            return FALSE;
        }
    }

    function WinCheck($correct) {
        if ($correct == 0) {
            return TRUE;
        }
        else {
            return FALSE;
        }
    }
	//Input Scores function
	function InputScore($servername, $username, $password, $dbname, $score, $user)
    {
        //create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        //check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $insertSQL = "INSERT INTO Scoreboard (Player, Score) VALUES ('$user', '$score')"; //Inserts Player and Score into db
        $sqlResult = $conn->query($insertSQL);
        $conn->close();
    }
	//Display Scores function
    function DisplayScores($servername, $username, $password, $dbname)
    {
		echo "<br>";
		$_SESSION["attempt"] = 0;
		$_SESSION["correct"] = 100;
        //create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        //check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $sqlDisplay = "SELECT Player, Score FROM Scoreboard ORDER BY Score DESC LIMIT 10"; //Selects Top 10 Players and Scores from db
        $resultDisplay = $conn->query($sqlDisplay);
        if ($resultDisplay->num_rows > 0) {
            // output data of each row
            while($row = $resultDisplay->fetch_assoc()) {
              echo "Player: " . $row["Player"]. " - Score: " . $row["Score"] . "<br>";
            }
          } else {
            echo "No Top Scores Found!";
          }
		 echo "<br>";
	
	}
	//Play Again Function-- generate new word and play again
	function playAgain($servername, $username, $password, $dbname) 
	{
		//create connection
		$conn = new mysqli($servername, $username, $password, $dbname);
		//check connection
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		$randWordID = rand(1, 20);
		$sqlWord = "SELECT `Word` FROM `Words` WHERE `ID` = '$randWordID'";
		$getWord = $conn->query($sqlWord);//Getting random word
		$word2 = "";
		if($getWord->num_rows > 0){
			while($row = $getWord->fetch_assoc()){
				$word2 = $row["Word"];
				echo "word " . $word2 . "<br>";
			}
		}
		$wordLength2 = strlen($word2);
		$_SESSION["word"] = $word2;
		$_SESSION["wordLength"] = $wordLength2;
		$_SESSION["attempt"] = 0;
		$_SESSION["correct"] = 100;
		$conn->close();
		header("Location: hangmangame.php");
		exit;
	}
	//logout function-- redirect to login page
    function logout() {
        header("Location: hangmanlogin.php");
		exit;
    }
	
    ?>
       
    <br>
    <h3>Guess a letter</h3>
    <form name="guessForm" method="get" action="hangmangame.php">
        <?php

        echo "<input type='hidden' name='OldLettersGuessed' value='" . $AllLettersGuessed . "'>";
        ?>
        <?php
        for ($x = 1; $x <= 26; $x++) {
            $letter = chr(64 + $x);
            echo "<input type='submit' name='LetterGuessed' value='" . chr($x + 64) . "'>";
        }
		echo "<br> <br> <br>";
        ?>
	</form>
		<?php
		
		session_start();
		include("ConnectionInfo.php");
		
		if(isset($_POST["play"])) {
			$_SESSION["attempt"] = 0;
			$_SESSION["correct"] = 100;
			playAgain($servername, $username, $password, $dbname); //already created above
		}
		if(isset($_POST["logout"])) {
			logout(); //already created above
		}
		?>
	<form method="post">	
		<input type="submit" name="play" class="button" value="Play Again" />
		<input type="submit" name="logout" class="button" value="Log Out" />
	</form>
	
</body>
</html>