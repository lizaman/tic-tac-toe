<html>
<head>
	<title>Tic Tac Toe</title>
	<style>
	body {
		background-color: white;
		text-align: center;
	}
	#ip{
		border-radius: 50px;
    	border: 2px solid black;
    	padding: 50px; 
    	width: 200px;
    	height: 15px;
    	margin-bottom: 20px;
    	margin-top: 20px;
    	margin-right: 20px;
    	font-size: 30px;
	}
	#go{
		 
    	width: 200px;
    	height: 15px;
    	margin-top: 20px;
    	padding: 50px;
    	border-radius: 50px;
	}
	</style>
</head>
<body>
	<div style="margin:0 auto;width:75%;text-align:center;">
	<form name = "ticktactoe" method = "POST" action = "index.php">
		<div class="container">
            <h1>Jugador 1</h1> <label for="player1"></label>
    <input type="text" placeholder="Introduce jugador 1" name="player1" required>
            <h1>Jugador 2</h1> <label for="player2"></label>
    <input type="text" placeholder="Introduce jugador 2" name="player2" required>
           <br><input type="submit">
	</form>
	</div></div>
</body>
</html>