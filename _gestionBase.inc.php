<?php

// FONCTIONS DE CONNEXION

function connect()
{   
   
	$pdo = new PDO('mysql:host=localhost;dbname=festival;charset=utf8', 'root', '');
    $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
    return $pdo;
}

/*
function selectBase($connexion)
{
   $bd="festival";
   $query="SET CHARACTER SET utf8";
   // Modification du jeu de caractères de la connexion
   $res=mysql_query($query, $connexion); 
   $ok=mysql_select_db($bd, $connexion);
   return $ok;
}
*/

// FONCTIONS DE GESTION DES ÉTABLISSEMENTS

function obtenirReqEtablissements() {
$sql= "SELECT  id, nom FROM Etablissement ORDER BY id";

return $sql;

}


function obtenirReqEtablissementsOffrantChambres()
{
   
   
   $req="select id, nom, nombreChambresOffertes from Etablissement where 
         nombreChambresOffertes!=0 order by id";
   return $req;
}

function obtenirReqEtablissementsAyantChambresAttribuées()
{
	$req= "SELECT  distinct id, nom, nombreChambresOffertes FROM Etablissement, 
         Attribution WHERE id = idEtab ORDER BY id";

   return $req;
}

function obtenirDetailEtablissement($bdd, $id)
{
   $req="select * from Etablissement where id='$id'";
   
      $rsEtab = $bdd->prepare($req);
	$rsEtab->execute();
	$lgEtab = $rsEtab->fetch();

  
   return $lgEtab;
}

function supprimerEtablissement($bdd, $id)
{
   $req="delete from Etablissement where id='$id'";
   $bdd->exec($req);
}
 
function modifierEtablissement($bdd, $id, $nom, $adresseRue, $codePostal, 
                               $ville, $tel, $adresseElectronique, $type, 
                               $civiliteResponsable, $nomResponsable, 
                               $prenomResponsable, $nombreChambresOffertes)
{  
   $nom=str_replace("'", "''", $nom);
   $adresseRue=str_replace("'","''", $adresseRue);
   $ville=str_replace("'","''", $ville);
   $adresseElectronique=str_replace("'","''", $adresseElectronique);
   $nomResponsable=str_replace("'","''", $nomResponsable);
   $prenomResponsable=str_replace("'","''", $prenomResponsable);
  
   $req="update Etablissement set nom='$nom',adresseRue='$adresseRue',
         codePostal='$codePostal',ville='$ville',tel='$tel',
         adresseElectronique='$adresseElectronique',type='$type',
         civiliteResponsable='$civiliteResponsable',nomResponsable=
         '$nomResponsable',prenomResponsable='$prenomResponsable',
         nombreChambresOffertes='$nombreChambresOffertes' where id='$id'";
   
   $bdd->exec($req);
}

function creerEtablissement($bdd, $id, $nom, $adresseRue, $codePostal, 
                            $ville, $tel, $adresseElectronique, $type, 
                            $civiliteResponsable, $nomResponsable, 
                            $prenomResponsable, $nombreChambresOffertes)
{ 
   $nom=str_replace("'", "''", $nom);
   $adresseRue=str_replace("'","''", $adresseRue);
   $ville=str_replace("'","''", $ville);
   $adresseElectronique=str_replace("'","''", $adresseElectronique);
   $nomResponsable=str_replace("'","''", $nomResponsable);
   $prenomResponsable=str_replace("'","''", $prenomResponsable);
   
   $req="insert into Etablissement values ('$id', '$nom', '$adresseRue', 
         '$codePostal', '$ville', '$tel', '$adresseElectronique', '$type', 
         '$civiliteResponsable', '$nomResponsable', '$prenomResponsable',
         '$nombreChambresOffertes')";
   
   $bdd->query($req);
}


function estUnIdEtablissement($bdd, $id)
{
   $req="select * from Etablissement where id='$id'";
   $rsEtab=$bdd->query($req);
   return $rsEtab->fetchAll(PDO::FETCH_ASSOC);
}

function estUnNomEtablissement($bdd, $mode, $id, $nom)
{
   $nom=str_replace("'", "''", $nom);
   // S'il s'agit d'une création, on vérifie juste la non existence du nom sinon
   // on vérifie la non existence d'un autre établissement (id!='$id') portant 
   // le même nom
   if ($mode=='C')
   {
      $req="select * from Etablissement where nom='$nom'";
   }
   else
   {
      $req="select * from Etablissement where nom='$nom' and id!='$id'";
   }
   $rsEtab=$bdd->query($req);
   return $rsEtab->fetchAll(PDO::FETCH_ASSOC);
}

function obtenirNbEtab($bdd)
{
   $req="select count(*) as nombreEtab from Etablissement";
   $rsEtab=mysql_query($req, $bdd);
   $lgEtab=mysql_fetch_array($rsEtab);
   return $lgEtab["nombreEtab"];
}

function obtenirNbEtabOffrantChambres($bdd)
{
   $req="select count(*) as nombreEtabOffrantChambres from Etablissement where 
         nombreChambresOffertes!=0";
		 
 
   $rsEtabOffrantChambres = $bdd->prepare($req);
	$rsEtabOffrantChambres->execute();
	$lgEtabOffrantChambres = $rsEtabOffrantChambres->fetch();
	return $lgEtabOffrantChambres["nombreEtabOffrantChambres"];
   /*  
   $rsEtabOffrantChambres=mysql_query($req, $bdd);
   $lgEtabOffrantChambres=mysql_fetch_array($rsEtabOffrantChambres);
   return $lgEtabOffrantChambres["nombreEtabOffrantChambres"];  
   */
}

// Retourne false si le nombre de chambres transmis est inférieur au nombre de 
// chambres occupées pour l'établissement transmis 
// Retourne true dans le cas contraire
function estModifOffreCorrecte($bdd, $idEtab, $nombreChambres)
{
   $nbOccup=obtenirNbOccup($bdd, $idEtab);
   return ($nombreChambres>=$nbOccup);
}

// FONCTIONS RELATIVES AUX GROUPES

function obtenirReqIdNomGroupesAHeberger()
{
   $req="select id, nom, nomPays from Groupe where hebergement='O' order by id";
   return $req;
}

function obtenirNomGroupe($bdd, $id)
{
   $req="select nom from Groupe where id='$id'";
   
   $rsGroupe = $bdd->prepare($req);
	$rsGroupe->execute();
	$lgGroupe = $rsGroupe->fetch();
	return $lgGroupe["nom"];
   }
   
  /*
  $rsGroupe=mysql_query($req, $bdd);
   $lgGroupe=mysql_fetch_array($rsGroupe);
   return $lgGroupe["nom"];

*/

// FONCTIONS RELATIVES AUX ATTRIBUTIONS

// Teste la présence d'attributions pour l'établissement transmis    
function existeAttributionsEtab($bdd, $id)
{
   $req="select count(*) as gg From Attribution where idEtab='$id'";
   $rsAtt=$bdd->query($req);
   $lgAtt=$rsAtt->fetchColumn();
   return $lgAtt > 0;
  /* $req="select * From Attribution where idEtab='$id'";
   $rsAtt=$bdd->prepare($req);
   $rsAtt->execute();
   $rsAttrib = $rsAtt->fetchAll();
*/

   return ($rsAttrib);
}

// Retourne le nombre de chambres occupées pour l'id étab transmis
function obtenirNbOccup($bdd, $idEtab)
{
   $req="select IFNULL(sum(nombreChambres), 0) as totalChambresOccup from
        Attribution where idEtab='$idEtab'";
		
	$rsOccup = $bdd->prepare($req);
	$rsOccup->execute();
	$lgOccup = $rsOccup->fetch();
   return $lgOccup["totalChambresOccup"];

		
   /*
   $rsOccup=mysql_query($req, $bdd);
   $lgOccup=mysql_fetch_array($rsOccup);
   return $lgOccup["totalChambresOccup"];
   */ 
   
}

// Met à jour (suppression, modification ou ajout) l'attribution correspondant à
// l'id étab et à l'id groupe transmis
function modifierAttribChamb($bdd, $idEtab, $idGroupe, $nbChambres)
{
   $req="select count(*) as nombreAttribGroupe from Attribution where idEtab=
        '$idEtab' and idGroupe='$idGroupe'";

   $rsAttrib=$bdd->query($req);
   $lgAttrib=$rsAttrib->fetch(PDO::FETCH_ASSOC);


   if ($nbChambres==0)
      $req="delete from Attribution where idEtab='$idEtab' and idGroupe='$idGroupe'";
   else
   {
      if ($lgAttrib["nombreAttribGroupe"]!=0)
         $req="update Attribution set nombreChambres=$nbChambres where idEtab=
              '$idEtab' and idGroupe='$idGroupe'";
      else
         $req="insert into Attribution values('$idEtab','$idGroupe', $nbChambres)";
   }
   $bdd->query($req);
}

// Retourne la requête permettant d'obtenir les id et noms des groupes affectés
// dans l'établissement transmis
function obtenirReqGroupesEtab($id)
{
   $req="select distinct id, nom, nomPays from Groupe, Attribution where 
        Attribution.idGroupe=Groupe.id and idEtab='$id'";
   return $req;
}
            
// Retourne le nombre de chambres occupées par le groupe transmis pour l'id étab
// et l'id groupe transmis
function obtenirNbOccupGroupe($bdd, $idEtab, $idGroupe)
{
   $req="select nombreChambres From Attribution where idEtab='$idEtab'
        and idGroupe='$idGroupe'";
	
$rsAttribGroupe = $bdd->prepare($req);
	$rsAttribGroupe->execute();
	
	
   if ($lgAttribGroupe = $rsAttribGroupe->fetch())
      return $lgAttribGroupe["nombreChambres"];
   else
      return 0;
}

function complet($bdd, $idEtab)
{
  $req="select nombreChambresOffertes FROM Etablissement WHERE id='$idEtab'";
  $rsComplet=$bdd->query($req);
  $lgComplet=$rsComplet->fetch();
  return $lgComplet['nombreChambresOffertes'];
}
?>

