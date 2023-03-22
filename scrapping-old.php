<?php

// Conectar a la API de Twitter usando la librería "twitteroauth"
require "vendor/autoload.php";
use Abraham\TwitterOAuth\TwitterOAuth;

// Datos de autenticación de la API de Twitter
$consumer_key = "qdjgAxPA4pXzmGRQRsWrBDhLD"; 
$consumer_secret = "JwokYSyS2JULPEEWg0vrLZeAsWcp2f7OnQnxjzGk81JTFjor1X"; 
$access_token = "3396001858-jMB36N0HGg3sVymzzwxHPSuZdpG7PRkqYavfqQX"; 
$access_token_secret = "SS04mbGpIwuMBUhFUpZnBRiI6qqnslAqEWf2drHLXdJJC"; 


// Conexión a la API de Twitter
$connection = new TwitterOAuth($consumer_key, $consumer_secret, $access_token, $access_token_secret);

// Array con los nombres de usuario de las cuentas que se quieren obtener información
$usuarios = array('@pvodanovic', '@fidelsenador', '@alvaroelizalde', '@PSChile');

// Ciclo para obtener información de cada cuenta y guardarla en un archivo JSON
foreach ($usuarios as $usuario) {

  // Obtener información de la cuenta
  $usuario_info = $connection->get("users/show", ["screen_name" => $usuario]);

  // Obtener los últimos 200 tweets del usuario
  $tweets = $connection->get("statuses/user_timeline", ["screen_name" => $usuario, "count" => 200]);

  // Variables para guardar la información de la cuenta
  $numero_seguidores = $usuario_info->followers_count;
  $numero_tweets = $usuario_info->statuses_count;
  $numero_retweets = 0;
  $numero_hashtags_mencionados = 0;
  $promedio_tweets_dia = 0;

  // Contar retweets y hashtags mencionados en los últimos 200 tweets
  foreach ($tweets as $tweet) {
    if (isset($tweet->retweeted_status)) {
      $numero_retweets++;
    }
    $hashtags = $tweet->entities->hashtags;
    foreach ($hashtags as $hashtag) {
      if (strcasecmp($hashtag->text, $usuario) != 0) {
        $numero_hashtags_mencionados++;
      }
    }
  }

  // Calcular promedio de tweets por día
  $fecha_primer_tweet = new DateTime($tweets[0]->created_at);
  $fecha_ultimo_tweet = new DateTime($tweets[count($tweets)-1]->created_at);
  $diferencia_dias = $fecha_ultimo_tweet->diff($fecha_primer_tweet)->days;
  if ($diferencia_dias > 0) {
    $promedio_tweets_dia = round($numero_tweets / $diferencia_dias, 2);
  }

  // Crear un arreglo con la información de la cuenta
  $info_cuenta = array(
    "numero_seguidores" => $numero_seguidores,
    "numero_tweets" => $numero_tweets,
    "numero_retweets" => $numero_retweets,
    "numero_hashtags_mencionados" => $numero_hashtags_mencionados,
    "promedio_tweets_dia" => $promedio_tweets_dia
    // Agregar cualquier otra información relevante aquí
  );

  // Convertir el arreglo a formato JSON y guardarlo en un archivo
  $nombre_archivo = strtolower(str_replace(" ", "-", $usuario)) . ".json";
  $ruta_archivo = "json-data/" . $nombre_archivo;
  $json_cuenta = json_encode($info_cuenta);
  file_put_contents($ruta_archivo, $json_cuenta);
}
