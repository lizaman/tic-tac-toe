<?php

	$boxes=["","","","","","","","",""];
	$g=false;
	$mensaje="";
	$gameSaved = isset($_COOKIE["tictoe"])?json_decode($_COOKIE["tictoe"], true):null;

	if($gameSaved == null || $gameSaved["finished"]==true || isset($_POST["player1"]))
	{
		$tictoe=[
			"boxes"=>["","","","","","","","",""], 
			"turn"=>1, 
			"finished"=>false, 
			"player1"=>$_POST["player1"],
			"player2"=>$_POST["player2"],
		];
		$mensaje="Es el turn del jugador " . $_POST["player1"];
		setcookie("tictoe", json_encode($tictoe), time() +3600);
	} else {
		$game= new Game(
			$gameSaved["boxes"],
			$gameSaved["turn"],
			$gameSaved["finished"],
			$gameSaved["player1"],
			$gameSaved["player2"]

		);
		for ($i=0; $i<9; $i++){
			$boxes[$i]=isset($_POST["box".$i])?$_POST["box".$i]:'';
		}

		$game->play($boxes);

		setcookie("tictoe", json_encode($game->toArray()), time() +3600);

		$boxes=$game->getBoxes();
		$mensaje=$game->getMessages();
		$g = $game->isGameFinished();
	}



class Game{
	private $boxes;
	private $turn;
	private $finished;
	private $player1;
	private $player2;
	private $mensaje;

	 public function __construct($boxes, $turn, $finished=false ,$player1="", $player2=""){
		 $this->boxes=$boxes;
		 $this->turn=$turn;
		 $this->finished=$finished;
		 $this->player1=$player1;
		 $this->player2=$player2;
	}

	public function play($updatedBoxes){
		
		if($this->ensureOnlyOneMovement($updatedBoxes)==false) {
			$this->mensaje="Has metido más de una ficha"; 
			return;
		}
		if($this->checkTurn($updatedBoxes)==false) {
			$this->mensaje="No es el turno de esta ficha"; 
			return;
		}
		if($this->doMovement($updatedBoxes)==false){
			$this->mensaje="No se ha podido efectuar este movimiento"; 
			return;
		} 
		if($this->getPiecesNumber()==false){
			$this->mensaje="No están las fichas que deberían"; 
			return;
		} 
		if($this->checkVictory("x")){
			$this->mensaje="¡¡¡¡¡Ha ganado el Jugador ". $this->player1 . "!!!!!!"; 
			return;
		}
		if($this->checkVictory("o")){
			$this->mensaje="¡¡¡¡¡Ha ganado el jugador " . $this->player2 . "!!!!!"; 
			return;
		}
		if($this->hasEmptyBox()==false){
			$this->mensaje="Hay un empate"; 
			return;
		}
		$this->turn++;
		$jugadorActual=$this->turn%2==0?$this->player2:$this->player1;
		$this->mensaje="Turno del jugador: " . $jugadorActual;
	}

	 function getPiecesNumber(){
	
		$fichastablero=0;
		foreach($this->boxes as $box){
			if (!empty($box)) {
				$fichastablero++;
			}
		}
		return ($fichastablero==$this->turn);
	}

	 function ensureOnlyOneMovement($updatedBoxes){
		$totalx=0;
		$totalo=0;
		foreach($updatedBoxes as $box){
				if ($box=="x") {$totalx++;}
				if ($box=="o"){$totalo++;}
		}
		if ($totalx-$totalo<=1){
			return true;
		}
		
		return false;
	}

	function checkTurn($updatedBoxes){
		if (
			($this->turn%2==0 && $this->currentPiece($updatedBoxes)=="o") 
			|| ($this->turn%2!=0 && $this->currentPiece($updatedBoxes)=="x")){
			return true;
		}

		return false;

	}

	function doMovement($updatedBoxes){
		for ($i=0; $i<9; $i++){
			if(empty($this->boxes[$i]) && !empty($updatedBoxes[$i])){
				$this->boxes[$i]=$updatedBoxes[$i];
				return true;
			}
			if (!empty($this->boxes[$i]) && $this->boxes[$i]!=$updatedBoxes[$i])
				return false;
		}
	}

	function currentPiece($updatedBoxes){

		for ($i=0; $i<9; $i++){
			if(empty($this->boxes[$i]) && !empty($updatedBoxes[$i])){
				return $updatedBoxes[$i];
			}
		}
	}

	

	function checkVictory($player){
	
		if(($this->boxes[0] == $player && $this->boxes[1] == $player && $this->boxes[2] == $player)  
		|| ($this->boxes[3] == $player && $this->boxes[4] == $player && $this->boxes[5] == $player) 
		|| ($this->boxes[6] == $player && $this->boxes[7] == $player && $this->boxes[8] == $player) 
		|| ($this->boxes[0] == $player && $this->boxes[3] == $player && $this->boxes[6] == $player)  
		|| ($this->boxes[1] == $player && $this->boxes[4] == $player && $this->boxes[7] == $player) 
		|| ($this->boxes[2] == $player && $this->boxes[5] == $player && $this->boxes[8] == $player) 
		|| ($this->boxes[0] == $player && $this->boxes[4] == $player && $this->boxes[8] == $player) 
		|| ($this->boxes[2] == $player && $this->boxes[4] == $player && $this->boxes[6] == $player) )
			{
				$this->finished = true;
				return true;
			}
			return false;
	}

	private function hasEmptyBox(){
	
		for ($i=0; $i<9; $i++){
			if(empty($this->boxes[$i])){
				return true;
			}
		}
			$this->finished = true;
			return false;
	}

	public function isGameFinished(){
		return $this->finished; 
	}

	public function getBoxes(){
		return $this->boxes;
	}

	public function getMessages(){
		return $this->mensaje;
	}

	public function toArray(){
		return [
			"boxes"=>$this->boxes, 
			"turn"=>$this->turn, 
			"finished"=>$this->finished, 
			"player1"=>$this->player1,
			"player2"=>$this->player2
		];
	}
}

?>

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
		<?php
		
			print("<h1>".$mensaje."</h1>");
			print("<br>");
			for($i = 0; $i <9; $i++){
				printf('<input type = "text" id = "ip" name = "box%s" value = "%s"  pattern="[xo]">', $i, $boxes[$i]);
				if ($i == 2 || $i == 5 || $i == 8){
				print("<br>");
				}
			}
			if(!$g){
				 print('<input type = "submit" name = "gobtn" value = "Siguiente Movimiento" id = "go">');
			}
			else{
				print('<input type = "button" name = "newgamebtn" value = "Juega otra vez" id = "go" onclick = "window.location.href=\'inicio.php\'">');
			}
	
		?>
	</form>
	</div>
</body>
</html>
