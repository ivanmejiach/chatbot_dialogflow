<?php

// me conecto a db
$mysqli = mysqli_connect("localhost","andinoaguayhielo_heladosbot","heladosbot123","andinoaguayhielo_heladosbot");

if (!$mysqli) {
	echo "Error: No se pudo conectar a MySQL." . PHP_EOL;
	die();
}

//***************************
//**** FUNCIONES ************
//***************************

function consulta_imagenes(){
  global $mysqli;
  $resultado = $mysqli->query("SELECT * FROM `imagenes` WHERE 1");
	while ($row = mysqli_fetch_assoc($resultado)){
  	$rows[]=$row;
	}
  return $rows;
}


function consulta_stock($sabor){
  global $mysqli;
  $resultado = $mysqli->query("SELECT $sabor FROM `stock` WHERE 1");
  $stock = mysqli_fetch_assoc($resultado);
  $cantidad = $stock[$sabor];
  return $cantidad;
}

function consulta_precio($sabor){
  global $mysqli;
  $resultado = $mysqli->query("SELECT $sabor FROM `precios` WHERE 1");
  $precios = mysqli_fetch_assoc($resultado);
  $precio = $precios[$sabor];
  return $precio;
}

function descuenta_stock($cantidad,$sabor){
	  global $mysqli;
		$mysqli->query("UPDATE `stock`  SET $sabor = $sabor - $cantidad ");
}

function agrega_stock($cantidad,$sabor){
	  global $mysqli;
		$mysqli->query("UPDATE `stock`  SET $sabor = $sabor + $cantidad ");
}

 ?>



<!doctype html>
<html lang="en-US">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">

    <!-- Custom Css -->
    <link rel="stylesheet" href="style.css" type="text/css" />

    <!-- Ionic icons -->
    <link href="https://unpkg.com/ionicons@4.2.0/dist/css/ionicons.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900" rel="stylesheet">

    <title>Empanadas Bot</title>
  </head>

  <body>

  <!-- N A V B A R -->
  <nav class="navbar navbar-default navbar-expand-lg fixed-top custom-navbar">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="icon ion-md-menu"></span>
    </button>
    <img src="images/logo.png" class="img-fluid nav-logo-mobile" alt="Company Logo">
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
      <div class="container">
      	<img src="https://storage.googleapis.com/cloudprod-apiai/e0f1c74b-cd79-4a64-afee-1e2ca13163d5_s.png" style="width:50px" class="img-fluid nav-logo-desktop" alt="Company Logo">
        <ul class="navbar-nav ml-auto nav-right" data-easing="easeInOutExpo" data-speed="1250" data-offset="65">
          <li class="nav-item nav-custom-link">
            <a class="nav-link" href="index.php">Home <i class="icon ion-ios-arrow-forward icon-mobile"></i></a>
          </li>

          <li class="nav-item nav-custom-link ">
            <a class="nav-link" href="https://somosioticos.com">Contacto <i class="icon ion-ios-arrow-forward icon-mobile"></i></a>
          </li>
					<li class="nav-item nav-custom-link btn btn-demo-small">
						<a class="nav-link" href="#pricing">Empanadas <i class="icon ion-ios-arrow-forward icon-mobile"></i></a>
					</li>
        </ul>
      </div>
    </div>
  </nav>
  <!-- E N D  N A V B A R -->

  <!-- H E R O -->
  <section id="hero">
    <div class="container">
      <div class="row">
        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
          <img src="https://themodernproper.com/assets/imager/assets/img/posts/2015/949/7491/IMG_1133_ba740feb132926901f5efa7bc2de7ad0.jpg" class="img-fluid" alt="Demo image">
        </div>
        <div class="col-md-7 content-box hero-content">
          <span>Pidelas a nuestro bot</span>
          <h1>Sabrosas<b> Empanadas</b></h1>
          <p>Primer delívery con atención online basada en inteligencia artificial y procesamiento natural del lenguaje</p>
          <a href="https://somosioticos.co" class="btn btn-regular">Quiero un bot para mi tienda</a>
        </div>
      </div>
    </div>
  </section>
  <!-- E N D  H E R O -->



  <!-- P R I C I N G -->
  <section id="pricing">
    <div class="container">
      <div class="title-block">
        <h2>Imagenes enviadas</h2>
      </div>

      <div class="row">
        <?php  $imagenes = consulta_imagenes();
						foreach ( $imagenes as $imagen) { ?>

						<div class="col-md-4">
			       	<div class="pricing-box">

								<img src="<?php echo $imagen['url'] ?>" style="width:100%" alt="">

							</div>
						</div>

			<?php } ?>

      </div>
    </div>
  </section>
  <!-- E N D  P R I C I N G -->

  <!-- C A L L  T O  A C T I O N -->
  <section id="call-to-action">
    <div class="container text-center">
      <h2>Aumente sus ventas</h2>
      <div class="title-block">
        <p>Dele a su emprendimiento la posibilidad de posicionarse con asistentes virtuales</p>
        <a href="https://somosioticos.com" class="btn btn-regular">Empieza Ahora</a>
      </div>
    </div>
  </section>
  <!-- E N D  C A L L  T O  A C T I O N -->

  <!--  F O O T E R  -->
  <footer>
    <div class="container">

      <div class="divider"></div>
      <div class="row">
        <div class="col-md-6 col-xs-12">
            <a href="#"><i class="icon ion-logo-facebook"></i></a>
            <a href="#"><i class="icon ion-logo-instagram"></i></a>
            <a href="#"><i class="icon ion-logo-twitter"></i></a>
            <a href="#"><i class="icon ion-logo-youtube"></i></a>
          </div>
          <div class="col-md-6 col-xs-12">
            <small>2018 &copy; All rights reserved. Made by <a href="https://templune.com/" target="blank" class="external-links">Templune</a></small>
          </div>
      </div>
    </div>
  </footer>


  <div id="top">
<button type="button" onclick='esconde()'  style="color:white;background-color:#007bff" name="button">Chatear!</button>
		<iframe id="chat"
    allow="microphone;"
    width="270"
    height="330"
    src="https://console.dialogflow.com/api-client/demo/embedded/35d4ada9-4bde-4d62-a8a7-e94792c334ab">
</iframe></div>

<style media="screen">
#top {
  position:fixed;
     bottom:0;
     right:0;
}
</style>

  <!--  E N D  F O O T E R  -->


    <!-- External JavaScripts -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
<script>
	function esconde(){
		$('#chat').toggle();
	}

	esconde();
</script>

  </body>
</html>
