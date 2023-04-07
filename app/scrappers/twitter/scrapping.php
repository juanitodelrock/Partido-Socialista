<?php

require_once __DIR__ . '/../app/config.php';
require_once __DIR__ . '/../app/models/database.php';
require_once __DIR__ . '/../app/models/Topic.php';
require_once __DIR__ . '/../app/models/TopicMeta.php';
require_once __DIR__ . '/vendor/autoload.php';


// Conectar a la API de Twitter usando la librería "twitteroauth"
use Abraham\TwitterOAuth\TwitterOAuth;


// Obtener los tópicos de la base de datos
$topics = $topicsModel->get_topics();

// Ciclo para obtener información de cada cuenta y guardarla en un archivo JSON
foreach ($topics as $topic) {
	$topic_name = $topic['name'];

	// Verificar si ya se ha obtenido la información de este tópico
	$exists = $topicsMetaModel->check_topic_meta_exists($topic_name, 'twitter_account');
	if ($exists) {
		continue;
	}

	// Conexión a la API de Twitter
	$connection = new TwitterOAuth($consumer_key, $consumer_secret, $access_token, $access_token_secret);

	// Obtener información de la cuenta del tópico
	$user = $connection->get("users/show", ["screen_name" => $topic_name]);

	// Obtener los últimos 400 tweets del usuario
	$tweets = $connection->get("statuses/user_timeline", ["screen_name" => $topic_name, "count" => 400]);

	// Variables para guardar la información de la cuenta
	$numero_seguidores = $user->followers_count;
	$numero_tweets = $user->statuses_count;
	$numero_retweets = 0;
	$numero_hashtags_mencionados = 0;
	$promedio_tweets_dia = 0;
	$ultimos_400_tweets = array();