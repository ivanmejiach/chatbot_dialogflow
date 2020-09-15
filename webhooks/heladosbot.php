<?php

// esto incluye la libreria
include_once "../somosioticos/somosioticos_dialogflow.php";
credenciales('heladosbot','heladosbot');
debug();

//me conecto a la BD
$mysqli = mysqli_connect("localhost","andinoaguayhielo_heladosbot","heladosbot123","andinoaguayhielo_heladosbot");

if (!$mysqli) {
  echo "Error: No se pudo conectar a MySql." . PHP_EOL;
  die();
}

//demora
$demora_x_empanada = 0.5;

if (intent_recibido("imagen")){
  $tarjetas[0]['titulo'] = "Titulo de prueba 1";
  $tarjetas[0]['subtitulo'] = "esto es un subtitulo 1";
  $tarjetas[0]['url'] = "https://media-cdn.tripadvisor.com/media/photo-m/1280/19/a0/62/09/main-plaza.jpg";
  $tarjetas[0]['botones'][0] = "Boton 1";
  $tarjetas[0]['botones'][1] = "Boton 2";
  $tarjetas[0]['botones'][2] = "Boton 3";

  $tarjetas[1]['titulo'] = "Titulo de prueba 2";
  $tarjetas[1]['subtitulo'] = "esto es un subtitulo 2";
  $tarjetas[1]['url'] = "https://live.staticflickr.com/8248/8665238857_30e3a3934a_b.jpg";
  $tarjetas[1]['botones'][0] = "Boton 1";
  $tarjetas[1]['botones'][1] = "Boton 2";

  $plataforma = origen();

  enviar_tarjetas($tarjetas,$plataforma);
}

if (intent_recibido("imagen_recibir")){
  $url = obtener_imagen();
  agregar_imagen($url);

  enviar_texto("Imagen recibida, estará publicada en https://andinoaguayhielo.com/imagen.php");
}

if (intent_recibido("imagen_enviar")){

  $plataforma = origen();

  if (origen() =="FACEBOOK" OR origen() =="TELEGRAM"){
    $imagenes[0]="https://i.pinimg.com/564x/be/2c/3d/be2c3d10187aa263a95d734d5b3a0bfe.jpg";
    $imagenes[1]="https://i.pinimg.com/564x/8a/e2/9e/8ae29ee02d3ec3e5811e293d3339fbaa.jpg";
    $imagenes[2]="https://i.pinimg.com/564x/a1/75/1c/a1751c656c0b2eb4c59aa963081a2cfb.jpg";
    enviar_imagenes($imagenes,origen());
  }
}

// tomart el intent consultar precio
if (intent_recibido("consultar_precio")){
  $p_arabes = consultar_precio['arabes'];
  $p_choclo = consultar_precio['choclo'];
  $p_carne = consultar_precio['carne'];
  enviar_texto("Las de carne cuestan $p_carne las arabes cuestan $p_arabes y las de choclo cuestan $p_choclo");

}


if ( intent_recibido("tomar_orden"))
{
  $cantidad1 = obtener_variables()['cantidad1'];
  $sabor1 = obtener_variables()['sabor1'];
  $disponibilidad1 = 0;
  $precio1 = 0;
  $subtotal1 = 0;
  if ($cantidad1 >0){
    $precio1 = consultar_precio($sabor1);
    $disponibilidad1 = consulta_stock($sabor1);
    $subtotal1 = $cantidad1 * $precio1;

    if ($cantidad1>$disponibilidad1){
      enviar_texto("Lo siento no tenemos suficientes empanadas $sabor1 en este momento, si deseas reformular el pedido simplemente di 'quiero ordenar' la cantidad que actualmente nos quedan es de $disponibilidad1 unidades");
      return;
    }
  }


  $cantidad2 = obtener_variables()['cantidad2'];
  $sabor2 = obtener_variables()['sabor2'];
  $disponibilidad2 = 0;
  $precio2 = 0;
  $subtotal2 = 0;
  if ($cantidad2 >0){
    $precio2 = consultar_precio($sabor2);
    $disponibilidad2 = consulta_stock($sabor2);
    $subtotal2 = $cantidad2 * $precio2;

    if ($cantidad2>$disponibilidad2){
      enviar_texto("Lo siento no tenemos suficientes empanadas $sabor2 en este momento, si deseas reformular el pedido simplemente di 'quiero ordenar' la cantidad que actualmente nos quedan es de $disponibilidad2 unidades");
      return;
    }
  }


  $cantidad3 = obtener_variables()['cantidad3'];
  $sabor3 = obtener_variables()['sabor3'];
  $disponibilidad3 = 0;
  $precio3 = 0;
  $subtotal3 = 0;
  if ($cantidad3 >0){
    $precio3 = consultar_precio($sabor3);
    $disponibilidad3 = consulta_stock($sabor3);
    $subtotal3 = $cantidad3 * $precio3;

    if ($cantidad3>$disponibilidad3){
      enviar_texto("Lo siento no tenemos suficientes empanadas $sabor3 en este momento, si deseas reformular el pedido simplemente di 'quiero ordenar' la cantidad que actualmente nos quedan es de $disponibilidad3 unidades");
      return;
    }
  }

  $total = $subtotal1 + $subtotal2 + $subtotal3;
  enviar_texto("Usted pidio: $cantidad1 $sabor1, $cantidad2 $sabor2, $cantidad3 $sabor3 y el total de soles es $total , por favor confirme su pedido");
  //$valor_a_enviar = opera_numeros(100,300);

  //enviar_texto("hola mundo");
}

//=============orden_confirmada==================
if ( intent_recibido("orden_confirmada"))
{
  $nombre = obtener_variables()['nombre'];
  $domicilio = obtener_variables()['domicilio'];
  $telefono = obtener_variables()['telefono'];

  $cantidad1 = obtener_variables()['cantidad1'];
  $sabor1 = obtener_variables()['sabor1'];
  $subtotal1 = 0;
  if ($cantidad1 >0){
    $precio1 = consultar_precio($sabor1);
    $subtotal1 = $cantidad1 * $precio1;
    descuenta_stock($cantidad1,$sabor1);
  }


  $cantidad2 = obtener_variables()['cantidad2'];
  $sabor2 = obtener_variables()['sabor2'];
  $subtotal2 = 0;
  if ($cantidad2 >0){
    $precio2 = consultar_precio($sabor2);
    $subtotal2 = $cantidad2 * $precio2;
    descuenta_stock($cantidad2,$sabor2);
  }

  $cantidad3 = obtener_variables()['cantidad3'];
  $sabor3 = obtener_variables()['sabor3'];
  $subtotal3 = 0;
  if ($cantidad3 >0){
    $precio3 = consultar_precio($sabor3);
    $subtotal3 = $cantidad3 * $precio3;
    descuenta_stock($cantidad3,$sabor3);
  }
  $total = $subtotal1 + $subtotal2 + $subtotal3;

  $mensaje = "Nueva orden para $nombre enviar: \n\n\n $sabor1 $cantidad1 \n\n $sabor2 $cantidad2 \n\n $sabor3 $cantidad3 \n enviar a: $domicilio \n\n Total a cobrar: $total";
  mail('ivan.mejiach@gmail.com','Nueva Orden desde HeladosBot!',$mensaje);

  $cantidad_total = $cantidad1 + $cantidad2 + $cantidad3;
  $demora = $demora_x_empanada * $cantidad_total;
  enviar_texto("Listo! su orden está en camino, llegará a destino en aproximadamente $demora minutos. Gracias!");

}

//******************** FUNCIONES *******************
function consulta_stock($sabor){
  global $mysqli;
  $resultado = $mysqli->query("SELECT $sabor FROM `stock` where id=1");
  $stock = mysqli_fetch_assoc($resultado);
  $cantidad = $stock[$sabor];
  return $cantidad;
}

function consultar_precio($sabor){
  global $mysqli;
  $resultado = $mysqli->query("SELECT $sabor FROM `precios` where id=1");
  $precios = mysqli_fetch_assoc($resultado);
  $precio = $precios[$sabor];
  return $precio;
}

function descuenta_stock($cantidad,$sabor){
  global $mysqli;
  $resultado = $mysqli->query("UPDATE `stock` set $sabor = $sabor - $cantidad ");
}

function agregar_stock($cantidad,$sabor){
  global $mysqli;
  $resultado = $mysqli->query("UPDATE `stock` set $sabor = $sabor + $cantidad ");
}

function agregar_imagen($url){
  global $mysqli;
  $resultado = $mysqli->query("INSERT INTO `imagenes` (`url`) VALUES ('$url')");
}

function opera_numeros($numero1,$numero2){
  $resultado = $numero1 + $numero2;

  return $resultado;
}
/*
if ( intent_recibido("calculadora"))
{
  $valor1 = obtener_variables()['numero1'];
  $valor2 = obtener_variables()['numero2'];
  $resultado = $valor1 * $valor2;

  enviar_texto("Luego de multiplicar los valores el resultado es $resultado");
}
*/







 ?>
