<?php

class db {

  function __construct($db_host, $db_root, $db_pass, $db_name, $prefix){

    mysqli_connect($db_host, $db_root, $db_pass,$db_name);


    $this -> db_host = $db_host;
    $this -> db_root = $db_root;
    $this -> db_pass = $db_pass;
    $this -> db_name = $db_name;
    $this -> count_request = 0;
    $this -> prefix  = $prefix.'_';

  }

  //--------------------------------------------------------------------------------
  // Fonction d'ajout
  //--------------------------------------------------------------------------------

  // Ajoute un nouvel enregistrement
  //--------------------------------------------------------------------------------
  // L'id de l'enregistrement créé est disponible dans $this->$db_table->last_id
  //
  // $attributes: tableau contenant les données à mettre à jour sous la forme : array('champ'=>valeur, ...)
  // $db_table: table concernée par la requete
  //
  // La fonction retourne "true" en cas de succès, "false" sinon
  //--------------------------------------------------------------------------------
  public function add($attributes, $db_table) {

    $sql  = "INSERT INTO ".$this -> prefix.$db_table." SET ";
    $sql .= $this->make_attributes($attributes);
    $result = $this->mysqlquery($sql);

    // Mise à jour du dernier identifiant de la table courrante
    //$this->$db_table->last_id = mysql_insert_id();

    // Mise à jour du cache du nombre d'enregistrements
    //$this->$db_table->count = $this->count(array(), $db_table);

    return($result);
  }

    public function doublon($key,$db_table) {

$sql = "SELECT * FROM ".$this -> prefix.$db_table." having count(".$key.") > 1";
     $result = $this->mysqlquery($sql);

    return($result);
  }

  //--------------------------------------------------------------------------------
  // Fonction de modification
  //--------------------------------------------------------------------------------

  // Modifie un ou plusieurs champs
  //--------------------------------------------------------------------------------
  // $condition : (string) chaine de caractère conditionnant la requete, et sous la forme : "id = '1' AND ..."
  // $attributes: tableau contenant les données à mettre à jour sous la forme : array('champ'=>valeur, ...)
  // $db_table: table concernée par la requete
  //
  // La fonction retourne "true" en cas de succès, "false" sinon
  //--------------------------------------------------------------------------------
  public function update_attributes($condition, $attributes, $db_table){
    $sql  = "UPDATE ".$this -> prefix.$db_table." SET ";
    $sql .= $this->make_attributes($attributes);
    $sql .= " WHERE $condition";
    $result = $this->mysqlquery($sql);

    return($result);
  }

  //--------------------------------------------------------------------------------
  // Fonctions de recherche
  //--------------------------------------------------------------------------------

  // Trouve un enregistrement de la table $db_table
  //--------------------------------------------------------------------------------
  // $param: (array) tableau de paramètres sous la forme : array('where' => 'id = \'1\' ', 'order'=> 'ASC', ...)
  // $db_table: table concernée par la requete
  //
  // La fonction retourne un tableau de valeurs
  //--------------------------------------------------------------------------------
  public function find($find='*', $params=array(), $db_table){
  $array = '';
    $sql = "SELECT $find FROM ".$this -> prefix.$db_table;
    $sql.= $this->parse_params($params);
    $result = $this->mysqlquery($sql);
    while($ligne=mysqli_fetch_array($result)){
    $array[]=$ligne;
                          }
      return $array;
                                  }

  public function getcols($find='*', $params=array(), $db_table){
  $array = '';
    $sql = "SELECT $find FROM ".$this -> prefix.$db_table;
    $sql.= $this->parse_params($params);
    $result = $this->mysqlquery($sql);
    while($ligne=mysqli_fetch_array($result)){
    $array[]=$ligne;
                          }
      return $array;
                                  }

   public function finmulti($table, $where=array()){
   $iswhere = '';
   $sqljointure = '';
   $attrib = '';
   $array = array();
   $sqlfirst = "SELECT ";
   $letter = 'a';
   for($i=0; $i<count($table); $i++){
     $attrib .= $letter++.".*,";
   if($i == 0){
   $letters = 'a';
     $sqlfisrt_table = " FROM ".$this -> prefix.$table[0]." AS ".$letters++;
                  }else{
   $sqljointure .= " JOIN ".$this -> prefix.$table[$i]." AS ". $letters++;
                  }
                                   }
               if($where){
   $iswhere .= $this->parse_params($where);
                  }
   $sql = $sqlfirst.
        substr($attrib,0,-1).
        $sqlfisrt_table.
        $sqljointure.
        $iswhere;
   $result = $this->mysqlquery($sql);
   while($ligne=mysqli_fetch_array($result)){
     $array[]=$ligne;
                            }
   return $array;
                                             }


  // Compte les enregistrements de la table
  //--------------------------------------------------------------------------------
  // $param: (array) tableau de paramètres sous la forme : array('where' => 'id = \'1\' ')
  // $db_table: table concernée par la requete
  //
  // La fonction retourne un nombre (int)
  //--------------------------------------------------------------------------------
  public function counts($params=array(), $db_table){
    $sql = "SELECT * FROM ".$this -> prefix.$db_table;
    $sql.= $this->parse_params($params);

    $result = $this->mysqlquery($sql);

    return(mysql_num_rows($result));
  }

  //--------------------------------------------------------------------------------
  // Fonctions de destruction
  //--------------------------------------------------------------------------------

  // Détruit un enregistrement
  //--------------------------------------------------------------------------------
  // $condition : (string) chaine de caractère conditionnant la requete, et sous la forme : "id = '1' AND ..."
  // $param: (array) tableau de paramètres sous la forme : array('where' => 'id = \'1\' ', 'order'=> 'ASC',  ...)
  // $db_table: table concernée par la requete
  //
  // La fonction retourne "true" en cas de succès, "false" sinon
  //--------------------------------------------------------------------------------
  public function destroy($condition, $db_table){

    $sql = "DELETE FROM ".$this -> prefix.$db_table." WHERE $condition";
    $result = $this->mysqlquery($sql);

    //$this->$db_table->last_id = mysql_insert_id();

    //$this->$db_table->count = $this->count(array(), $db_table);

    return($result);
  }

  // Effacer tous les enregistrements
  //--------------------------------------------------------------------------------
  // $db_table: table concernée par la requete
  //
  // La fonction retourne "true" en cas de succès, "false" sinon
  //--------------------------------------------------------------------------------
  public function truncate($db_table){
    $sql = "TRUNCATE TABLE $db_table";
    $result = $this->mysqlquery($sql);

    $this->$this -> prefix.$db_table->count = '0';

    $this->$this -> prefix.$db_table->last_id = '0';

    return($result);
  }

  // Execute une requète SQL
  //--------------------------------------------------------------------------------
  // $sql: (string) requete SQL a éxécuter
  //
  // La fonction retourne la requete $sql éxécutée ou un message d'erreur
  //--------------------------------------------------------------------------------
  public function mysqlquery($sql){
    $myConnection = mysqli_connect($this->db_host, $this->db_root, $this->db_pass,$this->db_name);
    $result = mysqli_query($myConnection, $sql) or die (mysqli_error($myConnection));




    if(!$result) throw new Exception($this->mysqlerror($sql));
    else $this->count_request++;

    return $result;
  }

  //--------------------------------------------------------------------------------
  // Fonctions protégées
  //--------------------------------------------------------------------------------

  // Affiche un message d'erreur complet
  //--------------------------------------------------------------------------------
  // $sql_query: (string) requete SQL éxécutée par mysqlquery
  //
  // La fonction retourne une chaine de caractères
  //--------------------------------------------------------------------------------
  protected function mysqlerror($sql_query){

    return 'Erreur SQL ' . mysqli_errno() . ': ' . mysqli_error().'<br />'.$sql_query;

  }

  // Création d'une chaine SQL contenant un ensemble 'champ'=valeur en fonction du table passé en paramètre
  //--------------------------------------------------------------------------------
  // Les champs sont associés aux clés du tableau et les données à leurs valeurs.
  //
  // $attributes: (array) Tableau contenant les données traiter : array('champ'=>valeur, ...)
  //
  // La fonction retourne une chaîne SQL du type 'champ'=valeur, 'champ'=valeur, ...
  //--------------------------------------------------------------------------------
  protected function make_attributes($attributes){
  $myConnection = mysqli_connect($this->db_host, $this->db_root, $this->db_pass,$this->db_name);
    $keys = array_keys($attributes);
    $sql = '';
    $nb_keys = count($keys);
    for($index=0; $index<$nb_keys ; $index++)
    {
      $key = $keys[$index];
      $value = mysqli_real_escape_string($myConnection,$attributes[$key]);

       $sql .= "$key='".utf8_decode($value)."'";

      // Pas le dernier paramètre : on ajoute une virgule
      if($index !== count($keys)-1)
        $sql .= ', ';
    }
    return($sql);
  }

  public function change_key( $array, $old_key, $new_key) {

    if( ! array_key_exists( $old_key, $array ) )
        return $array;

    $keys = array_keys( $array );
    $keys[ array_search( $old_key, $keys ) ] = $new_key;

    return array_combine( $keys, $array );
}

  // Création d'une chaîne SQL contenant les paramètres passés dans le tableau
  //--------------------------------------------------------------------------------
  // Ne traite que les paramettres 'conditions', 'order' et 'limits'
  //
  // $params : (array) Tableau contenant les données traiter : array('parametre'=>valeur, ...)
  //
  // La fonction retourne une chaîne SQL (ou une chaîne vide si aucun paramètre correct n'est passé)
  //--------------------------------------------------------------------------------
  protected function parse_params($params){
    $retour = '';

    if(array_key_exists('on', $params))
      $retour .= ' on '.$params['on'];

    if(array_key_exists('where', $params))
      $retour .= ' WHERE '.$params['where'];

    if(array_key_exists('and', $params))
      $retour .= ' AND '.$params['and'];

    if(array_key_exists('or', $params))
      $retour .= ' OR '.$params['or'];

    if(array_key_exists('order', $params))
      $retour .= ' ORDER BY '.$params['order'];

       if(array_key_exists('group', $params))
      $retour .= ' GROUP BY '.$params['group'];

    if(array_key_exists('limit', $params))
      $retour .= ' LIMIT '.$params['limit'];
    return $retour;
  }

  public function truefalse($test){

     if($test>0): return 'Oui'; else: return 'Non'; endif;

                                 }


}
?>