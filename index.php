<?php
	define('__ROOT__', dirname(dirname(dirname(__FILE__))));
	require_once(__ROOT__.'/php/lib/lib.php'); 
?>
<html>
<head>
	<?php head("4InRow"); ?>
	<link rel="stylesheet" href="css/master.css">
</head>
<body>
	<?php nav_bar(); ?>
	<div class="container">
		<form id="form_dim">
			<input type="number" name="i" value=6 />
			<input type="number" name="j" value=7 />
		</form>
		<button onclick="printTable()">Start</button>
		<button onclick="rules()" id="button_rules">Pokaż zasady</button>
		<div>
			<p>Aktualny gracz: <button id="btnActualColor" class="checked_p2"></button></p>
		</div>
		<div id="div_rules"></div>
		<div id="div_finished"></div>
		<div id="div_table"></div>
	</div>
</body>

<script type="text/javascript">
var four = 4;
var I = document.getElementById('form_dim').i.value;
var J = document.getElementById('form_dim').j.value;

var hiddenRules = true;
function rules() {
	let a = '';
	let buttonDescription = "Pokaż zasady";
	if (hiddenRules) {
		a = '<p>Należy ułożyć obok siebie 4 komórki tego samego koloru. Mogą one być ułożone w wierszu, kolumnie lub na przekątnej. Gracz, który pierwszy ułoży 4 pola obok siebie, wygrywa grę.</p>';
		buttonDescription = 'Ukryj zasady';
	}
	document.getElementById('div_rules').innerHTML = a;
	document.getElementById('button_rules').innerHTML = buttonDescription;
	hiddenRules = !hiddenRules;
}

function printTable() {
	wonTheGame = '';
	finished = false;
	finishGame();
	setIJ();
	let a = '<table id="playground">';
	for (i = I - 1; i >= 0; i--) {
		a += "<tr>";
		for (j = 0; j < J; j++) {
			a += '<td><button class="unchecked" id="b' + i + "," + j +
			'" onclick="mark(id)" value="0"></button></td>';
		}
		a += "</tr>";
	}
	a += '</table>';
	document.getElementById('div_table').innerHTML = a;
}

function setIJ() {
	I = document.getElementById('form_dim').i.value;
	J = document.getElementById('form_dim').j.value;
}

var actualColor = false;

function mark(id) {
	if (markable(id) && document.getElementById(id).value == "0") {
		document.getElementById(id).classList.remove('checked_p' + notPlayer());
		document.getElementById(id).classList.add('checked_p' + player());
		document.getElementById(id).value = notPlayer();
		document.getElementById("btnActualColor").classList.remove('checked_p' + player());
		document.getElementById("btnActualColor").classList.add('checked_p' + notPlayer());
		winner();
		actualColor = !actualColor;
	}
}

function player() {
	if (actualColor) {
		return "1";
	}
	return "2";
}

function notPlayer() {
	if (actualColor) {
		return "2";
	}
	return "1";
}

function idToArray(id) {
	var arr = ["", ""];
	var ptr = 0;
	for (i = 1; i < id.length; i++) {
		if (id[i] != ',') {
			arr[ptr] += id[i];
		}
		else {
			ptr++;
		}
	}
	// alert(arr[1]);
	return arr;
}

function markable(id) {
	if (finished) {
		return false;
	}
	var arr = idToArray(id);
	// alert(arr[0]);
	for (i = 1; i <= arr[0]; i++) {
		var ii = i*1-1;
	// alert(document.getElementById('b' + ii + ',' + arr[1]).value);
		if (document.getElementById('b' + ii + ',' + arr[1]).value == "0") {
			return false;
		}
	}
	return true;
}
var finished = false;
function winner() {
	if (!finished) {
		for (i = 0; i < I; i++) {
			for (j = 0; j <= J - four; j++) {
				if (theSameRow(i, j) != 0) {
					finished = true;
					break;
				}
			}
		}
		for (j = 0; j < J; j++) {
			for (i = 0; i <= I - four; i++) {
				if (theSameColumn(i, j) != 0) {
					finished = true;
					break;
				}
			}
		}
		for (i = 0; i <= I - four; i++) {
			for (j = 0; j <= J - four; j++) {
				if (theSameDiagR(i, j) != 0) {
					finished = true;
					break;
				}
			}
		}
		for (i = 0; i <= I - four; i++) {
			for (j = four - 1; j < J; j++) {
				if (theSameDiagL(i, j) != 0) {
					finished = true;
					break;
				}
			}
		}
	}
	finishGame();
	return 0;
}

var wonTheGame = '';

function finishGame() {
	let a = '';
	if (finished) {
		a = '<h3>Wygrał ' + wonTheGame + '</h3>';
		alert("Koniec gry");
	}
	document.getElementById('div_finished').innerHTML = a;
}

function theSameReturn(ts) {
	wonTheGame = ts;
	return 1;
}

function theSameRow(i, j) {
	var theSame = document.getElementById('b' + i + ','  + j).value;
	if (theSame == 0) {
		return 0;
	}
	// alert(theSame);
	for (k = 1; k < four; k++) {
		// alert(document.getElementById('b' + i + ','  + (j + k)).value);
		if (theSame != document.getElementById('b' + i + ','  + (j + k)).value) {
			return 0;
		}
	}
	return theSameReturn(theSame);
}

function theSameColumn(i, j) {
	var theSame = document.getElementById('b' + i + ','  + j).value;
	if (theSame == 0) {
		return 0;
	}
	// alert(theSame);
	for (k = 1; k < four; k++) {
		// alert(document.getElementById('b' + i + ','  + (j + k)).value);
		if (theSame != document.getElementById('b' + (i + k) + ','  + j).value) {
			return 0;
		}
	}
	return theSameReturn(theSame);
}

function theSameDiagR(i, j) {
	var theSame = document.getElementById('b' + i + ','  + j).value;
	if (theSame == 0) {
		return 0;
	}
	// alert(theSame);
	for (k = 1; k < four; k++) {
		// alert(document.getElementById('b' + i + ','  + (j + k)).value);
		if (theSame != document.getElementById('b' + (i + k) + ','  + (j + k)).value) {
			return 0;
		}
	}
	return theSameReturn(theSame);
}

function theSameDiagL(i, j) {
	var theSame = document.getElementById('b' + i + ','  + j).value;
	// alert("Przed");
	if (theSame == 0) {
		return 0;
	}
	// alert("Po");
	// alert(theSame);
	for (k = 1; k < four; k++) {
		// alert(document.getElementById('b' + i + ','  + (j + k)).value);
		if (theSame != document.getElementById('b' + (i + k) + ','  + (j - k)).value) {
			return 0;
		}
	}
	return theSameReturn(theSame);
}



</script>
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script> -->
</html>
