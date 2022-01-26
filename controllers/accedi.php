<?php
require_once 'components/page.php';
require_once 'components/header.php';
require_once 'components/footer.php';

require_once __DIR__ . '/../services/public/login.php';
require_once __DIR__ . '/../services/public/session.php';

$pagina = page('Accedi - Scissorhands');

$path = array(
    "Accedi" => "/accedi.php"
);

$main = file_get_contents('../views/accedi.html');

session_start();



if(isset($_SESSION["sessionid"])) #TODO da migliorare la situazione quando uno è già loggato
{
	header("Location: user/prenotazioni.php");
	die();
}

if(isset($_POST["submit"]) && isset($_POST["mail"]) && (preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $_POST["mail"]) || $_POST["mail"]=="admin" || $_POST["mail"]=="user"))
{
	$mail = $_POST["mail"];
}
else
{
	$_SESSION["error"]="Email o password non valide.";
	if(isset($_POST["mail"]))
		$_SESSION["mail"] = $_POST["mail"];
	unset($mail);
}

if(isset($_POST["submit"]) && isset($_POST["password"]) && (preg_match("/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/", $_POST["password"]) || $_POST["password"]=="admin" || $_POST["password"]=="user"))
{
	$password = $_POST["password"];
}
else
{
	$_SESSION["error"]="Email o password non valide.";
	if(isset($_POST["mail"]))
		$_SESSION["mail"] = $_POST["mail"];
	unset($password);
}

//TODO sistemare meglio sti if else
if(isset($password) && isset($mail))
{
	//$password=PublicLoginService::getUserPassword($mail);
	//if($password == $_POST["password"])
	if(PublicLoginService::verifyLogin($mail,$password))
	{
		$_SESSION["sessionid"] = $mail;
		if(Session::isUser($mail)) 
		{
			$_SESSION["type"] = "USER";
			header("Location: user/prenotazioni.php");
			die();
		}
		elseif(Session::isOwner($mail))
		{
			$_SESSION["type"] = "OWNER";
			header("Location: staff/prenotazioni.php");
			die();
		}	
		else	#better safe than sorry
		{
			$_SESSION["type"] = "GUEST";
			//header("Location: user/prenotazioni.php"); #TODO che fare?
		}
	}
	else
	{
		$_SESSION["error"]="Email o Password non corrette.";
		$_SESSION["mail"] = $mail;
	}
}



if(isset($_POST["submit"]) && isset($_SESSION["error"]))
{
	$main = str_replace("%ERRORE%",$_SESSION["error"], $main);
	unset($_SESSION["error"]);
	if(isset($_SESSION["mail"]))
		$main = str_replace("%MAILP%","value=\"".$_SESSION["mail"]."\"", $main);
	#unset($mail);
	unset($_SESSION["mail"]);
}
else
{
	$main = str_replace("%ERRORE%", "", $main);
	$main = str_replace("%MAILP%", "", $main);
	unset($_SESSION["mail"]);
}


$header = _header($path);
$footer = _footer();

$pagina = str_replace('%DESCRIPTION%', "Pagina di accesso a Scissorhands" ,$pagina);
$pagina = str_replace('%KEYWORDS%', "accedi, accesso, login, scissorhands, capelli, barba, barbiere",$pagina);
$pagina = str_replace('%HEADER%', $header, $pagina);
$pagina = str_replace('%MAIN%', $main, $pagina);


echo $pagina;
