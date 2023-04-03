<?php
/*
Canuto Rockero
    API Key: K59Nck194n1pDWHhKA6hM2zdf
    API Key Secret: 0Myhgh7oPB5LHEcHcWxiqovj3fxKLeVLPwEk793bA6nUcQmSok
    Bearer Token: AAAAAAAAAAAAAAAAAAAAAM03lgEAAAAAyJ6KV1antOxwTxHZclIEOBDV0vo%3DfTQ9VbQwHvxff4W8IajBfGul7ad3lDoewkAZUqXKrEcCswl74T

TM
	qdjgAxPA4pXzmGRQRsWrBDhLD
	JwokYSyS2JULPEEWg0vrLZeAsWcp2f7OnQnxjzGk81JTFjor1X
	3396001858-jMB36N0HGg3sVymzzwxHPSuZdpG7PRkqYavfqQX
	SS04mbGpIwuMBUhFUpZnBRiI6qqnslAqEWf2drHLXdJJC

*/

require_once __DIR__ . '/../config.php';

// definimos el array con las cuentas de twitter
$cuentas = array('@pvodanovic', '@fidelsenador', '@alvaroelizalde', '@PSChile');
 
// inicializamos la librería de twitter
require "vendor/autoload.php";

use Abraham\TwitterOAuth\TwitterOAuth;
 
// definimos los parámetros de conexión a la API de twitter 
$consumerKey = "qdjgAxPA4pXzmGRQRsWrBDhLD"; 
$consumerSecret = "JwokYSyS2JULPEEWg0vrLZeAsWcp2f7OnQnxjzGk81JTFjor1X"; 
$accessToken = "3396001858-jMB36N0HGg3sVymzzwxHPSuZdpG7PRkqYavfqQX"; 
$accessTokenSecret = "SS04mbGpIwuMBUhFUpZnBRiI6qqnslAqEWf2drHLXdJJC"; 


//CONNECTION TO THE TWITTER APP TO ASK FOR A REQUEST TOKEN
// creamos una nueva instancia de la clase TwitterOAuth  
$twitter = new TwitterOAuth($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);  

//Realizamos una petición a la API para obtener toda la información de la cuenta @PSChile. 
$user_info = $twitter->get('users/show', array('screen_name' => 'PSChile')); 

//Mostramos por pantalla los datos obtenidos. 
echo "<pre>";   //Para mostrar el resultado en formato legible.  
print_r($user_info);   //Mostramos el resultado por pantalla.  
echo "</pre>";
die();
// Recorremos el array con las cuentas y obtenemos los tuits  
foreach ($cuentas as $cta) {  

    // obtenemos los tuits de cada cuenta  
    $tuits = $twitter->get("statuses/user_timeline", array("screen_name" => $cta, "count" => 10));  
    
    // guardamos los tuits en un archivo json  
    file_put_contents('uploads/' . $cta . '.json', json_encode($tuits));  

}

