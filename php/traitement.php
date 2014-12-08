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

  }
  if(isset($_POST)){
  	insert_formulaire($_POST);
  }
  require('../pdfgenerateur/tfpdf.php');
function pdfgenerate($post){

if(isset($post) and ($post['sexe']=='Masculin')){
  $sexe = 'Monsieur';
}
elseif (isset($post) and ($post['sexe']=='Feminin')) {
  # code...
  $sexe = 'Madame';
}

if (isset($post) and ($post['avis']=='true') and $sexe=='Monsieur'){
  $entete='très chèr futur collaborateur';
  $phrase2='Nous avons pris note de votre potentielle ambition de développer votre e-projet '.$post['NomEntreprise'];
  $phrase3='avec nous.';
  $phrase4=' Restant à votre entière disposition pour nous entretenir au sujet de notre future collaboration.';
  $phrase5='Nous vous prions de croire en l\'assurance de notre respectueuse considération';
  $message=''. $sexe.' '. $post['Nom'] . ', '.$entete.', ';
}
elseif (isset($post) and ($post['avis']=='true') and $sexe=='Madame') {
  # code...
  $entete='très chère future collaboratrice';
  $phrase2='Nous avons pris note de votre potentielle ambition de développer le e-projet '.$post['NomEntreprise'];
  $phrase3='avec nous.';
  $phrase4='Restant à votre entière disposition pour nous entretenir au sujet de notre future collaboration.';
  $phrase5='Nous vous prions de croire en l\'assurance de notre respectueuse considération.';
  $message=''. $sexe.' '. $post['Nom'] . ', '.$entete.', ';
}
elseif (isset($post) and ($post['avis']=='false')) {
  # code...
  $entete='';
  $phrase2='Par ce présent message nous tenons à garder le contact pour de plus amples infomations';
  $phrase3='sur nos services.';
  $phrase4='Restant à votre entière disposition.';
  $phrase5='Nous vous prions de croire en l\'assurance de notre respectueuse considération.';
  $message=''. $sexe.' '. $post['Nom'] . ', '.$entete;
}

$phrase1 = 'Nous vous remercions d\'avoir été des nôtres lors du lancement de Digital F.';
$pdf = new tFPDF();
$pdf->AddPage();

// Ajoute une police Unicode (utilise UTF-8)
$pdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
$pdf->SetFont('DejaVu','',14);
$pdf->SetTextColor (48, 48, 51);

// Sélectionne une police standard (utilise windows-1252)
$pdf->Image('../images/logo.png',10,10);
$pdf->Image('../images/signature.png',0,270);
$pdf->Text(10,35,'9 Avenue de bois');
$pdf->Text(10,45,'92290 Châtenay-Malabry');
$pdf->Text(10,55,'France');
$pdf->Text(85,70, 'Remerciements');
$pdf->SetFont('DejaVu','',12);
$pdf->SetDrawColor (255, 155, 89);
$pdf->Rect (10,80,190,80);
$pdf->SetDrawColor(48, 48, 51);
$pdf->SetTextColor (48, 48, 51);
$pdf->SetRightMargin(185);
$pdf->Text(12,90,$message);
$pdf->Text(12,100,$phrase1); 
$pdf->Text(12,105,$phrase2);
$pdf->Text(12,110,$phrase3);
$pdf->Text(12,115,$phrase4);
$pdf->Text(12,120,$phrase5);
$pdf->Text (12,130,"Bien à Vous,");
$pdf->Text (12,140,"Digital F.");
$pdf->Output('../pdf/recapitulatif_'.$post['Prenom'].'_'.$post['Nom'].'_'.$post['avis'].'.pdf');
}
pdfgenerate($_POST);
function sendmail{

$mail = $post['Email']; // Déclaration de l'adresse de destination.
if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $mail)) // On filtre les serveurs qui présentent des bogues.
{
  $passage_ligne = "\r\n";
}
else
{
  $passage_ligne = "\n";
}
//=====Déclaration des messages au format texte et au format HTML.
$message_txt = "";
$message_html = "";
//==========
 
//=====Lecture et mise en forme de la pièce jointe.
$chemin='../pdf/recapitulatif_'.$post['Prenom'].'_'.$post['Nom'].'_'.$post['avis'].'.pdf';
$fichier   = fopen($chemin, "r");
$attachement = fread($fichier, filesize($chemin));
$attachement = chunk_split(base64_encode($attachement));
fclose($fichier);
//==========
 
//=====Création de la boundary.
$boundary = "-----=".md5(rand());
$boundary_alt = "-----=".md5(rand());
//==========
 
//=====Définition du sujet.
$sujet = "Remerciement Digital F";
//=========
 
//=====Création du header de l'e-mail.
$header = "From: \"Dorian\"<dorian.alitonou@farawa.com>".$passage_ligne;
$header.= "Reply-to: \"Dorian\" <dorian.alitonou@farawa.com>".$passage_ligne;
$header.= "MIME-Version: 1.0".$passage_ligne;
$header.= "Content-Type: multipart/mixed;".$passage_ligne." boundary=\"$boundary\"".$passage_ligne;
//==========
 
//=====Création du message.
$message = $passage_ligne."--".$boundary.$passage_ligne;
$message.= "Content-Type: multipart/alternative;".$passage_ligne." boundary=\"$boundary_alt\"".$passage_ligne;
$message.= $passage_ligne."--".$boundary_alt.$passage_ligne;
//=====Ajout du message au format texte.
$message.= "Content-Type: text/plain; charset=\"ISO-8859-1\"".$passage_ligne;
$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
$message.= $passage_ligne.$message_txt.$passage_ligne;
//==========
 
$message.= $passage_ligne."--".$boundary_alt.$passage_ligne;
 
//=====Ajout du message au format HTML.
$message.= "Content-Type: text/html; charset=\"ISO-8859-1\"".$passage_ligne;
$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
$message.= $passage_ligne.$message_html.$passage_ligne;
//==========
 
//=====On ferme la boundary alternative.
$message.= $passage_ligne."--".$boundary_alt."--".$passage_ligne;
//==========
 
 
 
$message.= $passage_ligne."--".$boundary.$passage_ligne;
 
//=====Ajout de la pièce jointe.
$message.= "Content-Type: application/pdf; name=\"Remerciements.pdf\"".$passage_ligne;
$message.= "Content-Transfer-Encoding: base64".$passage_ligne;
$message.= "Content-Disposition: attachment; filename=\"Remerciements.pdf\"".$passage_ligne;
$message.= $passage_ligne.$attachement.$passage_ligne.$passage_ligne;
$message.= $passage_ligne."--".$boundary."--".$passage_ligne; 
//========== 
//=====Envoi de l'e-mail.
mail($mail,$sujet,$message,$header);
 }
 sendmail($_POST);
//==========
?>