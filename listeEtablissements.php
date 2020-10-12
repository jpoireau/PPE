<?php

include("_debut.inc.php");
include("_gestionBase.inc.php"); 
include("_controlesEtGestionErreurs.inc.php");


$bdd=connect();
if (!$bdd)
{
   ajouterErreur("Echec de la connexion au serveur MySql");
   afficherErreurs();
   exit();
}


// AFFICHER L'ENSEMBLE DES ÉTABLISSEMENTS
// CETTE PAGE CONTIENT UN TABLEAU CONSTITUÉ D'1 LIGNE D'EN-TÊTE ET D'1 LIGNE PAR
// ÉTABLISSEMENT

echo "
<table width='70%' cellspacing='0' cellpadding='0' align='center' 
class='tabNonQuadrille'>
   <tr class='enTeteTabNonQuad'>
      <td colspan='4'>Etablissements</td>
   </tr>";
     
   $req=obtenirReqEtablissements();
   $rsEtab = $bdd->prepare($req);
	$rsEtab->execute();
	$lgEtab = $rsEtab->fetch();

   // BOUCLE SUR LES ÉTABLISSEMENTS
   while ($lgEtab!=FALSE)
   {
	   $id=$lgEtab['id'];
      $nom=$lgEtab['nom'];
      
      echo "
		<tr class='ligneTabNonQuad'>
         <td width='40%'>$nom</td>
         
         <td width='15%' align='center'> 
         <a href='detailEtablissement.php?id=$id'>
         Voir détail</a></td>
         
         <td width='15%' align='center'> 
         <a href='modificationEtablissement.php?action=demanderModifEtab&amp;id=$id'>
         Modifier</a></td>";
      	
         // S'il existe déjà des attributions pour l'établissement, il faudra
         // d'abord les supprimer avant de pouvoir supprimer l'établissement

			if (!existeAttributionsEtab($bdd, $id))
			{
            echo
               "<td width='15%'>VIDE </td>";
            echo "
            <td width='15%' align='center'> 
            <a href='suppressionEtablissement.php?action=demanderSupprEtab&amp;id=$id'>
            Supprimer</a></td>";

             
         }
         else
         {
            $nbAttribution = complet($bdd,$id);
            $nbOccupe = obtenirNbOccup($bdd, $id);
            echo
               "<td width='15%'> $nbOccupe / $nbAttribution </td>";
            /*$nbattributionlibre= 0;*/

            echo "
            <td width='15%'>&nbsp </td>";

         }         
            /*if($nbAttribution==$nbOccupe)
            {

               echo "
               <td width='15%'>COMPLET </td>";
            }*/
            /*else if ($nbOccupe==0) 
            {
               echo
               "<td width='15%'>VIDE </td>";
            }*/
            /*else
            {
               echo
               "<td width='15%'> $nbOccupe / $nbAttribution </td>";
            }*/
           
			
         echo"
      </tr>";
			$lgEtab = $rsEtab->fetch();
   }   
   echo "
   <tr class='ligneTabNonQuad'>
      <td colspan='4'><a href='creationEtablissement.php?action=demanderCreEtab'>
      Création d'un établissement</a ></td>
  </tr>
</table>";

?>
