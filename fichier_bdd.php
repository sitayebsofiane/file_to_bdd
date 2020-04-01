<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?php
try{
    if(!empty($_FILES)) {
        $file_name = $_FILES['fichier']['name'];
        $file_tmp_name = $_FILES['fichier']['tmp_name'];
        $file_dest = 'files/'.$file_name;
        $file_ext = strrchr($file_name,".");
        $extention_autoriser = array('.png','.jpg');
        if(in_array($file_ext,$extention_autoriser)){
            if(move_uploaded_file($file_tmp_name,$file_dest)){
                $bdd = new PDO("pgsql:dbname=php_test;host=localhost", "postgres", "as122014") ;
                // $fileData = $bdd->pgsqlLOBCreate();
                // $stream = $bdd->pgsqlLOBOpen($fileData, 'w');
                // $fh = fopen($file_dest, 'rb');
                // stream_copy_to_stream($fh, $stream);
                $reponse = $bdd->prepare('INSERT INTO fichier(nom,url_fichier) VALUES(:nom,:fichier)');
                $reponse->execute(array('nom' => $file_name,'fichier' =>$file_dest));
                echo 'fichier envoyer avec succes '.$file_tmp_name;
                $reponse->closeCursor();
        }else{
                echo 'echec de l\'envoi du fichier';
            }          
               
        }
        } 
}catch(Execption $e){
    $bdd->rollBack();
    die('conexion impossible');
}
?>
<h1> Uploader un fichier </h1>


<form action="" method="post" enctype="multipart/form-data">
<label for="fichier"></label>
<input type="file" id="fichier" name="fichier"><br>
<input type="submit" value="Envoyer">
</form>
<h1>PHOTO enrigistré</h1>
<?php 
try{
    $bdd = new PDO("pgsql:dbname=php_test;host=localhost", "postgres", "as122014") ;
    $reponse = $bdd->query('select * from fichier;');
    while($data = $reponse->fetch()){
        echo $data['nom'].": "."<a href=".$data['url_fichier']."> lien</a><br>";
    }
    $reponse->closeCursor();
}catch(Execption $e){
    $bdd->rollBack();
    die('conexion impossible');
}
?>

</body>
</html>