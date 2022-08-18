# Kaylee Dockter CS3750 Portfolio
Hello, my name is Kaylee Dockter. 
I am a student at Weber State University pursuing a Computer Science degree with a minor in Mathematics.
I am also currently working as a Business Intelligence Account Manager at Marketstar.

Checkout out some of my most recent projects below. Each link to their respective github repos.

## Projects:

### [Hangman](https://github.com/kayleedockter/Hangman) 
![Hangman Game](/docs/assets/Hangman1.png)
![Hangman Game](/docs/assets/Hangman2.png)
![Hangman Game](/docs/assets/Hangman3.png)

***Created With***

PHP, SQL

***Overview***

This project plays through a simple game of hangman. The user is required to sign up or login in to play
and is given 12 attempts to guess the word properly. The game finishes with a scoreboard ranking the player.

***Challenges***

This project was the first time I had ever been exposed to php, so it was a bit of a learning curve at the start.
I especially had trouble with the session variables.
I'd say out of all the projects though, this went the smoothest in terms of my understanding of the concepts and languages used.

***Sample Code***

Working on both creating and retrieving the salt and hash password to and from the databse was the most enjoyable part of the project for me.
We were so relieved when we finally got it to work.
```
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
    
```
### [Bank](https://github.com/rflowers45/TigerBanking) 
![Bank](/docs/assets/Bank1.png)
![Bank](/docs/assets/Bank2.png)
![Bank](/docs/assets/Bank3.png)

***Created With***

.NET 6 framework, MVC, Entity Framework, Javascript, JQuery, C#

***Overview***

This project displays a banking app that allows the user to deposit and withdraw money into one of their bank accounts. 
The app also allows the user to view previous transactions for each of the accounts.

***Challenges***

I would say our greatest challenge for this project was understanding and properly implementing MVC and Entity Framework
as none of us had previously worked in them before.
I personally had never worked in C# either which was a challenge at the start for me as well.

***Sample Code***

Because I had the least experience in C# in this group, I was assigned most of the html and css required for the assignment.
I had a lot of fun with the design of the bank page and was pretty happy with how it turned out.

(Html)
```
<div class="text-center">
    <div class="row">
        <div class="column">
            <a asp-action="Deposit" asp-route-userId="@Model.userId" style="text-decoration: none; color: inherit;">
                <article class="card">
                    <img src="~/images/deposit.jpg" alt="deposit" style="width:100%">
                    <div class="container">
                        <h1>Make a Deposit</h1>
                    </div>
                </article>
            </a>
        </div>
        <div class="column">
            <a asp-action="Withdraw" asp-route-userId="@Model.userId" style="text-decoration: none; color: inherit;">
                <article class="card">
                    <img src="~/images/withdraw.jpg" alt="withdraw" style="width:100%">
                    <div class="container">
                        <h1>Make a Withdrawal</h1>
                    </div>
                </article>
            </a>
        </div>
        <div class="column">
            <a asp-action="Transactions" asp-route-userId="@Model.userId" style="text-decoration: none; color: inherit;">
                <article class="card">
                    <img src="~/images/search.png" alt="search" style="width:100%">
                    <div class="container">
                        <h1>View Account History</h1>
                    </div>
                </article>
            </a>
        </div>
    </div>
</div>
```
(Final Product)
![Bank](/docs/assets/Bank2.png)

### [Stock Investing](https://github.com/rflowers45/StockInvestingGame) 
![Stock Game](/docs/assets/Stocks1.png)
![Stock Game](/docs/assets/Stocks2.png)

***Created With***

.NET 6 framework, Razer Pages, Javascript, JQuery, C#

***Overview***
This project was an introduction to a Stock Investing Application. 
***Challenges***

This project was the first time I had ever been exposed to php, so it was a bit of a learning curve at the start.
I'd say out of all the projects though, this went the smoothest in terms of my understanding of the concepts and languages used.

### [Boggle Game](https://github.com/kayleedockter/BoggleGame) 
![Boggle Game](/docs/assets/Boggle1.png)
![Boggle Game](/docs/assets/Boggle2.png)

***Created With***

.NET 6 framework, MVC, SignalR, Javascript, JQuery, MSSQL Server, C#

***Overview***

***Challenges***

This project was the first time I had ever been exposed to php, so it was a bit of a learning curve at the start.
I'd say out of all the projects though, this went the smoothest in terms of my understanding of the concepts and languages used.
