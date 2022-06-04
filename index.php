<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Title</title>
	<style>
		.error{ color:#fff; background: red; padding: 5px; }
	</style>
</head>
<body>
	<form>
		<fieldset>
			<div class="error">Erreur il ne peux y avoir de personne agéee de plus de 150 ans</div>
			<div>
				<label for="nom">Nom</label>
				<input type="text" value="" name="nom" id="nom"/>
			</div>
			<div>
				<label for="prenom">Prenom</label>
				<input type="text" value="" name="prenom" id="prenom"/>
			</div>
			<div>
				<label for="date">Date de naissance</label>
				<input type="date" placeholder="JJ/MM/AAAA" value="" name="date" id="date"/>
				
			</div>
		</fieldset>
		<button type="submit" name="envoyer">Valider</button>
	</form>
	
</body>
</html>
