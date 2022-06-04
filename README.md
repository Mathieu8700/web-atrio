# web-atrio
web-atrio.com

ARBORESCENCE

 Page index.php ---> Formuilaire demandé (3b)
 DOSSIER CLASS --> La class requis "Personne" Ainsi que la class db (classe que j'utilise souvent en POO incluant les requetes MYSQL classique)
  
  j'ai commenté mon code afin d'expliquer le deroulement du script, ainsi :
  
  J'inclut mes "class"
  
  je test si la session existe pas deja aucas ou le formulaire serais reposter
  
  je test si nous avons envoyer le formulaire
  
  je test si la date est bien remplis
  
  si tous vas bien j'enregistre en base de donnée
  
  sinon j'affiche le message d'erreur.
  
  
  //Visualiser
  le bouton visualiser fait passer en get l'affichage des nom par ordre alphabetique.
  
  
 Supplement fichier SQL CONTENANT la base de donnée "Personne"
 
 id_perssone Autoincrement 10 INT
 nom varchar 350
 prenom varchar 350
 naissance date
 
 
 Dévéllopement informations :
 j'ai utilisé le frameworks PHP7 en php orienté objets
 MYSQL PHPMYADMIN
 
 
 Avec plus de temps le "style" aurais été plus sympathique.
 
 Merci.
 
 
 
