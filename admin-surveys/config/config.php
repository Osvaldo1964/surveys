<?php

//define("BASE_URL", "http://localhost/cmrpos/");
//const BASE_URL = "https://sialp-app.com/";
const BASE_URL = "http://localhost/surveys/admin-surveys/";
const LIBS = 'Libraries/';
const VIEWS = 'Views/';

//Zona horaria
date_default_timezone_set('America/Bogota');

//Datos de Conexion Base de Datos
const DB_HOST = "localhost";
const DB_NAME = "db-surveys";
const DB_USER = "root";
const DB_PASSWORD = "";
//const DB_USER = "Osval_001";
//const DB_PASSWORD = "Oscor_0331";
const DB_CHARSET = "charset=utf8";

//Para el envio de Correos
const ENVIRONMENT = ""; // Local = 0 - Produccion = 1

//Deliminadores decimal y millar Ej. 24,1989.00
const SPD = ".";
const SPM = ",";

//Simbolo de moneda
const SMONEY = "$";
const CURRENCY = "USD";

//API PAYPAL SANDBOX
//const IDCLIENTE = "AerWYYIWKl0voOPo6ddeZwNvwKVB92l5A1ktAf7wvNgLuBXw6yZ2E2kQWhaYCxYFdSAcD491xAyj0-BC";
//const SECRETPAYPAL = "EISG6RESokUGFvIa5XJwA4xNuUFZtlB182-ik0RHq93OhZR_DOS1tbOhb5nDR_SzGevFsnTRr4q5FMI5";
//const URLPAYPAL = "https://api-m.sandbox.paypal.com";

const IDCLIENTE = "ASCeAPxAaDAVG9SYAGahz0K0Pgh2yujzbWuyKkOwUNIc9walb_fLJqvuFXPgH0pSgmOTUksmj0P0a6RP";
const SECRETPAYPAL = "EOvV1QIAGhOU8eZXvceZQTqbC62uK5i5Bk5HNDfkFgVzQb4ulg4YKh3raE8ETAw_cIC9BBfNSjhXL0NB";
const URLPAYPAL = "https://api-m.sandbox.paypal.com";

//APIPAYPAL LIVE
//const IDCLIENTE = "AZCvGmUdBhGXiY_Hl1GyFaMOianldOaRqPYfXDpfUjgUCT3Lts8u_YWTVKGPbMonvIwvfsJ56RHxVCvf";
//const SECRETPAYPAL = "EOsJqzOxnnNuqoxuQdA0XsgPv2G8sYhgNMo1R6z-iHJPf5wf0kUVDaluq2OEws28MNN9HT_zctS83dZx";
//const URLPAYPAL = "https://api-m.paypal.com";

//Datos envio de correo
const NOMBRE_REMITENTE = "Sistema Integrado de Control Centros de Atención Adulto Mayor";
const EMAIL_REMITENTE = "info@vidactiva.com.co";
const NOMBRE_EMPRESA = "UNION TEMPORAL VIDA PLENA";
const WEB_EMPRESA = "https://admin.vidactiva.com.co";

const DESCRIPCION = "Sistema Integrado de Control Centros de Atención Adulto Mayor";
const SHAREDHASH = "SialpApp";

//Datos Empresa
const DIRECCION = "CR 11 17 06 BRR TERRITORIAL, Santa Marta";
const TELEMPRESA = "+(57)3023898254";
const WHATSAPP = "+573023898254";
const EMAIL_EMPRESA = "info@vidactiva.com.co";
const EMAIL_PEDIDOS = "";
const EMAIL_CONTACTO = "info@vidactiva.com.co";
const EMAIL_PQR = "pqrs@vidactiva.com";
const EMAIL_SUSCRIPCION = "info@vidactiva.com";

const CAT_SLIDER = "1,2,3";
const CAT_BANNER = "4,5,6";
const CAT_FOOTER = "1,2,3,4,5";

//Datos para Encrpitar / Desencriptar
const KEY = "osvicor";
const METHODENCRIPT = "AES-128-ECB";

//Envio
const COSTOENVIO = 10;

// Modulos
const MDASHBOARD = 1;
const MGENERALES = 2;
const MESTRUCTURA = 3;
const MCOMPONENTES = 4;

const MCLIENTES = 8;
const MCARTERA = 13;
const MSUSCRIPTORES = 17;
const MDCONTACTOS = 18;
const MDPAGINAS = 19;
const MPQRS = 20;

// PAGINAS
const PINICIO = 1;
const PTIENDA = 2;
const PCARRITO = 3;
const PNOSOTROS = 4;
const PCONTACTO = 5;
const PPREGUNTAS = 6;
const PTERMINOS = 7;
const PSUCURSALES = 8;
const PERROR = 9;
const PPQRS = 10;

// TIPOS DE ACTAS
const ACTINICIAL = 1;
const ACTINVERSIONES = 2;
const ACTERCEROS = 3;
const ACTBAJA = 4;

// Constantes Cartera
const IDCARTERA = 5;
const FACTURADORES = "1,3";

// Roles
const RCLIENTES = 5;
const RAMINISTRADOR = 1;

const STATUS = array('Completo', 'Aprobado', 'Cancelado', 'Reembolsado', 'Pendiente', 'Enviado', 'Entregado', 'Anulado');

//Productos por página
const CANTPRODHOME = 3;
const PROPORPAGINA = 2;
const PROCATEGORIA = 2;
const PROBUSCAR = 2;

//REDES SOCIALES
const FACEBOOK = "https://www.facebook.com/sialp-app";
const INSTAGRAM = "https://www.instagram.com/sialp-app/";
const PINTEREST = "https://www.pinterest.es/sialp-app";