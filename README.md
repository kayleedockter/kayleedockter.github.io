# Kaylee Dockter CS3750 Portfolio
# kayleedockter-github.io
Hello, my name is Kaylee Dockter. 
I am a student at Weber State University pursuing a Computer Science degree with a minor in Mathematics.
I am also currently working as a Business Intelligence Account Manager at Marketstar.

Checkout out some of my most recent projects below. 
***Each of their names link to their respective github repos.***

## Projects:

# [Hangman](https://github.com/kayleedockter/Hangman) 
![Hangman Game](/docs/assets/Hangman1.PNG)
![Hangman Game](/docs/assets/Hangman2.PNG)
![Hangman Game](/docs/assets/Hangman3.PNG)

***Created With***

PHP, SQL.

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
# [Bank](https://github.com/rflowers45/TigerBanking) 
![Bank](/docs/assets/Bank1.PNG)
![Bank](/docs/assets/Bank2.PNG)
![Bank](/docs/assets/Bank3.PNG)

***Created With***

.NET 6 framework, MVC, Entity Framework, Javascript, JQuery, C#.

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

# [Stock Investing](https://github.com/rflowers45/StockInvestingGame) 
![Stock Game](/docs/assets/Stocks1.PNG)
![Stock Game](/docs/assets/Stocks2.PNG)

***Created With***

.NET 6 framework, Razer Pages, Javascript, JQuery, C#.

***Overview***

This project was an introduction to a Stock Investing Application. We were required to provide the user three different
ways to buy and sells stocks.

***Challenges***

This project for me felt the most over my head and I am grateful to my teammates for working with me
and helping me better understand the concepts and the code. Something I really struggled to grasp was 
how we were to connect the slider to the pie chart and the text field when the user entered an amount.

***Sample Code***

I worked mostly on the actions of the hold and quit buttons and how the game was to end. 
This meant for example, figuring out how to track seven days from the start of the game and how to change the price each day.
```
  public IActionResult OnPostHold()
        {
            try
            {
                var symbol = HttpContext.Session.GetString("ticker"); //Setting the ticker symbol to what the user has entered
                var apiKey = "YQ12ME2NUXQ29XG8"; //I got this key by registering my email. You all might wanna do the same or use mine?
                var dailyPrices = $"https://www.alphavantage.co/query?function=TIME_SERIES_DAILY&symbol={symbol}&outputsize=full&apikey={apiKey}&datatype=csv"
                    .GetStringFromUrl().FromCsv<List<StockData>>();
               // string endGame = endGameScenario();
                var vDateIndex = HttpContext.Session.GetInt32("currentDay"); //getting date index
                int iDateIndex = vDateIndex.Value;
                
                var dayPrice = dailyPrices[iDateIndex].Close;//This gets the price
                price = dayPrice;
                HttpContext.Session.SetString("price", price.ToString());
                string sSessionPrice = HttpContext.Session.GetString("price"); //Getting session price
                string sSessionBalance = HttpContext.Session.GetString("balance"); //Getting session balance
               
                var vDayCounter = HttpContext.Session.GetInt32("dayCounter");
                int iDayCounter = vDayCounter.Value;
                iDayCounter++;
                var iSessionShares = HttpContext.Session.GetInt32("shares"); //Getting sessions shares
                int ownedShares = iSessionShares.Value; //Have to convert nullable-int to int
                balance = Convert.ToDecimal(sSessionBalance);
                price = Convert.ToDecimal(sSessionPrice);
                string endGame = endGameScenario();
                while (iDayCounter < 8)
                {
                    iDateIndex++;
                    HttpContext.Session.SetInt32("currentDay", iDateIndex);
                    HttpContext.Session.SetInt32("dayCounter", iDayCounter);
                    HttpContext.Session.SetInt32("shares", ownedShares); //Setting session shares held
                    HttpContext.Session.SetString("balance", balance.ToString()); //Setting session balance

                    return new JsonResult("Current Balance: $" + balance + "<br> Shares Held: " + ownedShares + "<br> Current Day: " + iDayCounter + "<br> New Price:  $" + price);
                }
                return new JsonResult(endGame);


            }
            catch (Exception)
            {
                return new JsonResult(endGameScenario());

            }

        }
```
# [Boggle Game](https://github.com/kayleedockter/BoggleGame) 
![Boggle Game](/docs/assets/BoggleGame1.PNG)
![Boggle Game](/docs/assets/BoggleGame2.PNG)

***Created With***

.NET 6 framework, MVC, SignalR, Javascript, JQuery, MSSQL Server, C#

***Overview***

The Boggle Game is a 2-player word guessing game in which the players are given 60 seconds to create as many words as they can
with the letters on the board. The letters have to be next to eachother in order to form a word, and the word has to exist.

***Challenges***

This project came with many challenges... Including using SignalR for the first time, learning how to call a 
SignalR function in javascript and MVC, and figuring out how to access the database we created with MSSQL with SignalR.

***Sample Code***

Getting the page to redirect to an EndGame.cshtml page after the timer ran out took a long time for me to figure out 
and it turns out it was just this small line of code.
```
                if (distance < 0) {
                    clearInterval(x);
                    document.getElementById("timer1").innerHTML = "Time's up!";
                    var url = $("#RedirectTo").val();
                    location.href = url;
                }
            }, 1000);
        }
```
