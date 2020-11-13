<?php

namespace App;

define('ORGANIZACAO_NOME', 'Pets');
//Verifica qual o protocolo
$protocol = $_SERVER['SERVER_PORT'] == 443 ? 'https://' : 'http://';
$domainName = $_SERVER['HTTP_HOST']; // pega o dominio
$domainName = rtrim($domainName, '/'); // garante que o dominio nao veio com barra no final
$domainName .= '/'; // adiciona uma barra no fim
define('BASE_URL', 'http://localhost/pets/public/');//ao usar em produção, retira 'public/'
//define('BASE_URL', 'http://seudominio.com.br/');//ao usar em produção, retira 'public/'

define('APP_URL', 'C:\\xampp\\htdocs\\pets\\App'); // para renderizar views, aqui passa o local dos arquivos

define('APP_NSPACE', 'App'); // para boostrap

define('DB_TYPE', 'mysql');
define('DB_HOST', 'localhost');
define('DB_PORT', '3306');
define('DB_NAME', 'pets');
define('DB_USER', 'root');
define('DB_PASS', '');

define('DEFAULT_PAGE_TITLE', 'Meus animais'); //máximo 60 caracteres
define('DEFAULT_PAGE_DESCRIPTION', 'Organization Description');
define('DEFAULT_CONTROLLER', 'Index');
define('DEFAULT_LANGUAGE', 'ptBR');//set language
define('DEFAULT_TEMA', 'default');
define('DB_CHAR', 'utf8');
define('SECURE', false);
define('SLEEP_BRUTE_FORCE', 3);
define('QT_TENTATIVAS_LOGIN', 3);

define('EMAIL_CONTATO', 'organization_email@email.com');
define('NOME_CONTATO', 'Organization');
define('ASSUNTO_CONTATO', 'Form de contato Organization');
define('DEFAULT_REMETENTE_EMAIL', 'organization_email@email.com');
define('DEFAULT_REMETENTE_NOME', 'Organization');
define('DEFAULT_CEP', '00000000');

// define('DIR_FILES', 'C:\\xampp\\htdocs\\pets\\Files\\');
// define('DIR_XML', 'C:\\xampp\\htdocs\\pets\\Files\\xml\\');

define('NUM_ITENS_GRID', 12);
define('NUM_ITENS_SHOW', 12);

define('SECRET_KEY_RECAPTCHA', "");