<?php

// esto incluye la libreria
include_once "../somosioticos/somosioticos_dialogflow.php";
credenciales('andinobot','andino321');
debug();

//me conecto a la BD
//$mysqli = mysqli_connect("localhost","usuario bd","password","nombre BD");
$mysqli = mysqli_connect("localhost","andinoaguayhielo_chatbot","chatB0T2020","andinoaguayhielo_webhooks");

if (!$mysqli) {
  echo "Error: No se pudo conectar a MySql." . PHP_EOL;
  die();
}

//set nombre cliente
//$nombre='';

$nombre_ini ="";
$msg_e='';
$arr = [];
if ( intent_recibido("obtener_nombre"))
{
  //global $nombre_ini;
  $nombre_ini = obtener_variables()["nombre"];
  $arr[0]=$nombre_ini;
  //$_SESSION["nombre"]= $nombre_ini;
  //insert_cliente($nombre_ini,'ivan@dm.pe',1234567,'san miguel','av lima este 123');
  return;
}

if ( intent_recibido("test"))
{
  $nombre_ini = obtener_variables()["nombre_uni"];
  enviar_texto("hola ". $nombre_ini . ", bienvenido ");
  return;
}
//inicia_pedido

if (intent_recibido("inicia_pedido")){

    $plataforma = origen();

    $mensaje ="Le hacemos recordar nuestro catálogo de productos:"."\n";
    $lista = consulta_productos();
    $mensaje = $mensaje . $lista ;

    //enviar_texto($mensaje);
    $textos[0] = $mensaje;
    $textos[1] = "Primero escriba el SKU luego la cantidad, para regitrar mas de un producto, sepárelo por comas(,) . \n Ejemplo:  A1 2, H2 5";
    enviar_varios_textos($textos);
}

if ( intent_recibido("registra_pedido"))
{
  $mensaje ="";
  $cont =0;
/*
  $producto1 = obtener_variables()['producto1'];
  $cantidad1 = obtener_variables()['cantidad1'];
  $nombre = get_nom_producto($producto1);

  if ($cantidad1>0) {
    if (strlen($nombre)>0){
        $cont ++;
        $mensaje = "$cont.- $producto1 ($nombre) --> $cantidad1";
      }
  }

  $producto2 = obtener_variables()['producto2'];
  $cantidad2 = obtener_variables()['cantidad2'];
  $nombre = get_nom_producto($producto2);

  if ($cantidad2>0) {
    if (strlen($nombre)>0){
        $cont ++;
        $mensaje = $mensaje ."\n". "$cont.- $producto2 ($nombre) --> $cantidad2";
      }
  }

  $producto3 = obtener_variables()['producto3'];
  $cantidad3 = obtener_variables()['cantidad3'];
  $nombre = get_nom_producto($producto3);

  if ($cantidad3>0) {
    if (strlen($nombre)>0){
        $cont ++;
        $mensaje = $mensaje ."\n". "$cont.- $producto3 ($nombre) --> $cantidad3";
      }
  }

  $producto4 = obtener_variables()['producto4'];
  $cantidad4 = obtener_variables()['cantidad4'];
  $nombre = get_nom_producto($producto4);

  if ($cantidad4>0) {
    if (strlen($nombre)>0){
        $cont ++;
        $mensaje = $mensaje ."\n". "$cont.- $producto4 ($nombre) --> $cantidad4";
      }
  }

  $producto5 = obtener_variables()['producto5'];
  $cantidad5 = obtener_variables()['cantidad5'];
  $nombre = get_nom_producto($producto5);

  if ($cantidad5>0) {
    if (strlen($nombre)>0){
        $cont ++;
        $mensaje = $mensaje ."\n". "$cont.- $producto5 ($nombre) --> $cantidad5";
      }
  }
*/
  //$GLOBALS['msg_e'] = $mensaje;
  $list = get_pedido(1);
  $cont = $list[0];
  $mensaje = $list[1];

  if ($cont == 0){
    $msg[0] = "Usted no ha registrado correctamente los SKU's o las cantidades no son correctas";
    $msg[1] = "Elija una de las siguientes opciones: \n 2. CAMBIAR para modificar el pedido \n 3. CANCELAR para salir del registro de pedidos";
    enviar_varios_textos($msg);
  }
  else{
    $msg[0] = "Validamos que usted a pedido:"."\n". $mensaje;
    $msg[1] = "Elija una de las siguientes opciones: "."\n". "1. CONFIRMAR para continuar con el pedido" ."\n". "2. CAMBIAR para modificar el pedido" ."\n". "3. CANCELAR para salir del registro de pedidos";
    enviar_varios_textos($msg);
  }

  return;
}

if ( intent_recibido("confirma_pedido"))
{
  //$m= $GLOBALS['msg_e'] ;
  $nombre = obtener_variables()["nombre_ini"];
  //$nombre = obtener_variables_ext()["nombre"];

  $email = obtener_variables()["email"];
  $telefono = obtener_variables()["telefono"];
  $distrito = obtener_variables()["distrito"];
  $direccion = obtener_variables()["direccion"];

  $list = get_pedido(2);
  $cont = $list[0];
  $mensaje = $list[1];

  insert_cliente($nombre,$email,$telefono,$distrito,$direccion,$mensaje);

  $mensaje_f = "<html><body>";
  $mensaje_f .= "<h1>Se ha registrado un nuevo pedido con el siguiente detalle:</h1>";
  $mensaje_f .= "<table bgcolor='#e0e0e0' cellpadding='0' cellspacing='0' border='0'>";
  $mensaje_f .= "<tr><td width='20%'><strong>Nombre:</strong> </td><td width='70%'> $nombre </td> </tr>";
  $mensaje_f .= "<tr><td width='20%'><strong>Email: </strong></td><td width='70%'> $email </td> </tr>";
  $mensaje_f .= "<tr><td width='20%'><strong>Teléfono: </strong></td><td width='70%'> $telefono </td> </tr>";
  $mensaje_f .= "<tr><td width='20%'><strong>Dirección:</strong> </td><td width='70%'> $direccion , $distrito </td> </tr>";
  $mensaje_f .= "<tr><td width='20%'><strong>Pedido: </strong> </td><td width='70%'> $mensaje  </td> </tr>";
  $mensaje_f .= "</table></body></html>";

  $cabeceras = 'MIME-Version: 1.0' . "\r\n";
  $cabeceras .= 'Content-type: text/html; charset=utf-8' . "\r\n";
  $cabeceras .= 'From: AndinoBot';

  mail('ivan.mejiach@gmail.com','Nuevo Pedido desde AndinoBot!',$mensaje_f,$cabeceras);

//enviar_texto("hola mundo");
  return;
}

//******************** FUNCIONES *******************
function get_nombre(){
  global $nombre_ini;

  $nombre_ini = obtener_variables()["nombre"];
  return $nombre_ini;
}


function insert_cliente($nombre,$email,$telefono,$distrito,$direccion,$mensaje){
  global $mysqli;
  //global $nombre_ini;
  //$a = $nombre_ini;



  //$resultado = $mysqli->query("INSERT INTO `m_cliente`(`nombres`,`email`,`telefono`,`distrito`,`direccion`) VALUES ('$nombre','$email','$telefono','$distrito','$direccion')");
  $resultado = $mysqli->query("INSERT INTO `m_cliente`(`nombres`,`email`,`telefono`,`distrito`,`direccion`,`pedido`) VALUES ('$nombre','$email','$telefono','$distrito','$direccion','$mensaje')");
}


function get_nom_producto($codigo){
  global $mysqli;
  $resultado = $mysqli->query("SELECT `nombre_completo` FROM `m_producto` where cod_producto='$codigo'");
  $fila = mysqli_fetch_assoc($resultado);
  $nombre = $fila["nombre_completo"];
  return $nombre;
}


function consulta_productos(){
  global $mysqli;
  $resultado = $mysqli->query("SELECT  CONCAT('SKU: ' ,`cod_producto` ,' (', `nombre_completo`,') -->S/ ', `precio`) AS `nombre_completo` FROM `m_producto` WHERE 1");

  $mensaje= "";
  while ($fila = mysqli_fetch_array($resultado)){
    $mensaje = $mensaje . $fila["nombre_completo"]."\n" ;
  }


  return $mensaje;
}

function get_pedido($int) {
  $mensaje ="";
  $cont =0;

  if ($int==1){
    $producto1 = obtener_variables()['producto1'];
    $cantidad1 = obtener_variables()['cantidad1'];
  }
  if ($int==2){
    $producto1 = obtener_variables_ext()['producto1'];
    $cantidad1 = obtener_variables_ext()['cantidad1'];
  }

  $nombre = get_nom_producto($producto1);

  if ($cantidad1>0) {
    if (strlen($nombre)>0){
        $cont ++;
        $mensaje = "$cont.- $producto1 ($nombre) --> $cantidad1";
      }
  }

  if ($int==1){
    $producto2 = obtener_variables()['producto2'];
    $cantidad2 = obtener_variables()['cantidad2'];
  }
  if ($int==2){
    $producto2 = obtener_variables_ext()['producto2'];
    $cantidad2 = obtener_variables_ext()['cantidad2'];
  }

  $nombre = get_nom_producto($producto2);

  if ($cantidad2>0) {
    if (strlen($nombre)>0){
        $cont ++;
        $mensaje = $mensaje ."\n". "$cont.- $producto2 ($nombre) --> $cantidad2";
      }
  }

  if ($int==1){
    $producto3 = obtener_variables()['producto3'];
    $cantidad3 = obtener_variables()['cantidad3'];
  }
  if ($int==2){
    $producto3 = obtener_variables_ext()['producto3'];
    $cantidad3 = obtener_variables_ext()['cantidad3'];
  }

  $nombre = get_nom_producto($producto3);

  if ($cantidad3>0) {
    if (strlen($nombre)>0){
        $cont ++;
        $mensaje = $mensaje ."\n". "$cont.- $producto3 ($nombre) --> $cantidad3";
      }
  }

  if ($int==1){
    $producto4 = obtener_variables()['producto4'];
    $cantidad4 = obtener_variables()['cantidad4'];
  }
  if ($int==2){
    $producto4 = obtener_variables_ext()['producto4'];
    $cantidad4 = obtener_variables_ext()['cantidad4'];
  }

  $nombre = get_nom_producto($producto4);

  if ($cantidad4>0) {
    if (strlen($nombre)>0){
        $cont ++;
        $mensaje = $mensaje ."\n". "$cont.- $producto4 ($nombre) --> $cantidad4";
      }
  }

  if ($int==1){
    $producto5 = obtener_variables()['producto5'];
    $cantidad5 = obtener_variables()['cantidad5'];
  }
  if ($int==2){
    $producto5 = obtener_variables_ext()['producto5'];
    $cantidad5 = obtener_variables_ext()['cantidad5'];
  }

  $nombre = get_nom_producto($producto5);

  if ($cantidad5>0) {
    if (strlen($nombre)>0){
        $cont ++;
        $mensaje = $mensaje ."\n". "$cont.- $producto5 ($nombre) --> $cantidad5";
      }
  }

  return [$cont,$mensaje];
}



 ?>
