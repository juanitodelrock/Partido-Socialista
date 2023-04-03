<?php
// app/config.php

// Ruta base de la aplicación
define('APP_ROOT', '/app');
define('APP_NAME', 'Political Monitor');

// Constantes para definir el tiempo de expiración de las sesiones y cookies de la app
define('SESSION_EXPIRATION_TIME', 3600); // tiempo de sesión en segundos
define('COOKIE_EXPIRATION_TIME', 604800); // tiempo de cookie en segundos

// Constantes para definir rutas y URLs de uso común en la app
define('ROOT_URL', '/app');
define('IMAGES_DIR', '/images');
define('HOME_PAGE_URL', ROOT_URL . '/index.php');

// Constantes para definir valores por defecto de la app
define('DEFAULT_PAGE_SIZE', 10); // número máximo de elementos a mostrar por página
define('DATE_FORMAT', 'Y-m-d'); // formato de las fechas


$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Constantes para BDD MYsql
define('DB_HOST', $_ENV['DB_HOST']);
define('DB_USER', $_ENV['DB_USER']);
define('DB_PASSWORD', $_ENV['DB_PASSWORD']);
define('DB_NAME', $_ENV['DB_NAME']);

// Constantes para twitter
// Datos de autenticación de la API de Twitter
define('TWITTER_CONSUMER_KEY', "qdjgAxPA4pXzmGRQRsWrBDhLD");
define('TWITTER_CONSUMER_SECRET', "JwokYSyS2JULPEEWg0vrLZeAsWcp2f7OnQnxjzGk81JTFjor1X");
define('TWITTER_ACCESS_TOKEN', "3396001858-jMB36N0HGg3sVymzzwxHPSuZdpG7PRkqYavfqQX");
define('TWITTER_ACCESS_TOKEN_SECRET', "SS04mbGpIwuMBUhFUpZnBRiI6qqnslAqEWf2drHLXdJJC");

// Función para obtener la URL base de la aplicación
function base_url($path = '')
{
    return 'https://' . $_SERVER['HTTP_HOST'] . APP_ROOT . '/' . $path;
}

// Función para redireccionar a una URL
function redirect($url)
{
    header('Location: ' . base_url($url));
    exit();
}

// Función para obtener el usuario actual
if (!function_exists('get_current_user')) {
    function get_current_user()
    {
        return $_SESSION['user_name'];
    }
}

// Función para comprobar si un usuario es super administrador
function is_super_admin($user_id)
{
    $db = new Database();
    $user = new User($db->getConnection());
    return $user->is_super_admin($user_id);
}

// Función para comprobar si un usuario tiene acceso a un tópico
function user_has_access_to_topic($user_id, $topic_id)
{
    $db = new Database();
    $topic = new Topic($db->getConnection());
    return $topic->user_has_access($user_id, $topic_id);
}


// Funciones para validar y sanitizar datos de entrada
function validate_email($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function sanitize_string($str)
{
    return filter_var(trim($str), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
}

function sanitize_html($html)
{
    return filter_var($html, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
}

function validate_password($password)
{
    return preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^\w\d\s:])([^\s]){8,}$/', $password);
}

function validate_name($name)
{
    return preg_match('/^[a-zA-Z\s]+$/', $name);
}

// Funciones para cargar archivos y manejar su subida y descarga
function upload_file($file, $destination)
{
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return false;
    }

    return move_uploaded_file($file['tmp_name'], $destination);
}

function download_file($file_path, $file_name = null)
{
    if (!file_exists($file_path)) {
        return false;
    }

    if (is_null($file_name)) {
        $file_name = basename($file_path);
    }

    header('Content-Type: ' . mime_content_type($file_path));
    header('Content-Disposition: attachment; filename=' . $file_name);
    readfile($file_path);

    return true;
}

// Funciones para formatear fechas y horas
function format_date($date_string, $format = DATE_FORMAT)
{
    $date = date_create_from_format('Y-m-d H:i:s', $date_string);

    return date_format($date, $format);
}

// Funciones para encriptar y desencriptar datos sensibles
function encrypt_data($data, $key)
{
    $iv_size = openssl_cipher_iv_length('AES-256-CBC');
    $iv = openssl_random_pseudo_bytes($iv_size);
    $ciphertext = openssl_encrypt($data, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);

    return base64_encode($iv . $ciphertext);
}

function decrypt_data($data, $key)
{
    $data = base64_decode($data);
    $iv_size = openssl_cipher_iv_length('AES-256-CBC');
    $iv = substr($data, 0, $iv_size);
    $ciphertext = substr($data, $iv_size);
    $plaintext = openssl_decrypt($ciphertext, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);

    return $plaintext;
}
/**
 * Función para generar un link a una acción de la aplicación
 * @param string $action Nombre de la acción
 * @param array $params Parámetros de la acción
 * @return string URL de la acción
 * @example 
 * Generar un link simple
 * $link = generate_link('login');
 * Generar un link con datos adicionales
 * $link = generate_link('search', array('q' => 'política', 'category' => 'noticias'));
 */
function generate_link($action, $params = array())
{
    $base_url = base_url();
    $query_strings = '';
    if (count($params) > 0) {
        foreach ($params as $key => $value) {
            $query_strings .= '&' . $key . '=' . urlencode($value);
        }
    }

    return $base_url . '?action=' . $action . $query_strings;
}

function p(){
    echo "<pre>";
    print_r(func_get_args());
    echo "</pre>";
}