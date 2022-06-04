<?
//ouverture de session
session_start();

//Les erreur PHP
error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set('display_errors', '2');
ini_set('upload_max_filesize', '100M');
//le time zone
date_default_timezone_set('Europe/Paris');
//Les classes & LIBRAIRIE
define('CLASS_DIR',  $_SERVER['DOCUMENT_ROOT'].'/class/');
set_include_path(get_include_path().PATH_SEPARATOR.CLASS_DIR);
spl_autoload_extensions('.class.php');
spl_autoload_register();

//connexion a la base de donne MYSQL BDD PHP OBJECT (CLASS DB)
//Server / utilisateur / pass / Nom de la base / prefix de la table (securité)
$db       = new db('localhost','noah_user','y996Hcc_','bdd_noah','WEB');
$perssone = new personne();

//si nous ne postons pas la page ont detruit la session (rechargement de page)
//et donc reposter un autre formulaire
if(!isset($_POST['envoyer'])):
	unset($_SESSION['envoi']);
					endif;

//si nous cliquons sur le bouton visualise
if(isset($_GET['visualiser'])):

//liste des Personne trié par Nom alphabetiquement
$liste = $db->find('*',array('order' => 'nom ASC'),'Personne');
//si la liste nest pas vide
if($liste):
foreach($liste as $lst){

echo '<ul><li>'.utf8_encode($lst['nom']).' '.utf8_encode($lst['prenom']).' '.date('d/m/Y',strtotime($lst['naissance'])).'</li></ul>';	
						
						}	
			endif;
			
			  endif;



//declaration de la variable contenant le message erreur
$erreur = '';

//si nous postons le formulaire
if(isset($_POST['envoyer']) && !isset($_SESSION['envoi'])):
	//les postes
	$nom    = $_POST['nom'];
	$prenom = $_POST['prenom'];
	$date   = $_POST['date'];
	//appel de la fonction qui test et enregistre dans la base
	$erreur = $perssone::_postFormulaire($nom,$prenom,$date);
	
	
						endif;

?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Title</title>
	<style>
		.error{ color:#fff; background: red; padding: 5px; }
		.button-wrapper{ display: flex; justify-content: space-between; }
	</style>
</head>
<body>
	<form method="post" action="#">
		<fieldset>
			<? echo $erreur; ?>
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
		<div class="button-wrapper">
		<button type="submit" name="envoyer">Valider</button>
		<? if(!isset($_GET['visualiser'])): ?>
		<a href="m.php?visualiser" target="parent">Visualiser</a>
		<? else: ?>
		<a href="m.php" target="parent">Retour</a>
		<? endif; ?>
		</div>
	</form>
	
</body>
</html>
