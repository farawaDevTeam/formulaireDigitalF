<?php
$bdd = new PDO('mysql:host=localhost;dbname=digitalf', 'root', '');

function insert_formulaire($post)
  {
 
    global $bdd;
 

//Insertion de l'annonce dans la base de donnée
   $req=$bdd->prepare('INSERT INTO formulaire(Nom,Prenom,Profession,Email,NomEntrepriseDigitale,DomaineActivite,Url1,Url2,Url3,ChiffreAffaire,
   	ServicesSouhaites,BoolNextRdv,NextRdv,Genre)
  VALUES(:Nom,:Prenom,:Profession,:Email,:NomEntrepriseDigitale,:DomaineActivite,:Url1,:Url2,:Url3,:ChiffreAffaire,
   	:ServicesSouhaites,:BoolNextRdv,:NextRdv,:Genre)');
  $req -> execute(array(
              'Nom'=>$post['Nom'],
              'Prenom'=>$post['Prenom'],
              'Profession'=>$post['Profession'],
              'Email'=>$post['Email'],
              'NomEntrepriseDigitale'=>$post['NomEntreprise'],
              'DomaineActivite'=>$post['DomaineActivite'],
              'Url1'=>$post['Url1'],
              'Url2'=>$post['Url2'],
              'Url3'=>$post['Url3'],
              'ChiffreAffaire'=>$post['ChiffreAffaire'],
              'ServicesSouhaites'=>$post['ServicesSouhaites'],
              'BoolNextRdv'=>$post['avis'],
              'NextRdv'=>$post['NextRdv'],
              'Genre'=>$post['sexe']

  ));
print_r($post);
  }
  if(isset($_POST)){
  	insert_formulaire($_POST);
  }