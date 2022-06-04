<?
class personne{

public function _postFormulaire($nom,$prenom,$date){
global $db;	

if($date):
//Verification de l'age de l'utilisateur
$today = date('Y-m-d');
$dateDifference = abs(strtotime($today) - strtotime($date));
//nombre anné differences
$years  = floor($dateDifference / (365 * 60 * 60 * 24));


//si inferieur a 150 ans
if($years >= 150):
$erreur = '<div class="error">Erreur il ne peux y avoir de personne agéee de plus de 150 ans</div>';	
			endif;
			
else:
	
	$erreur = '<div class="error">Date de naissance obligatoire</div>';
	
endif;

//si nous n'avons pas d'erreur ont enregistre en base
$db->add(array('nom' => $nom,'prenom' => $prenom,'naissance' => $date),'Personne');	

//pour ne pas poster plusieurs fois le formulaires
$_SESSION['envoi'] = 1;

	
								 }
	
			}