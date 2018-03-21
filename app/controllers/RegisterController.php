<?php
use function core\render;
use MiladRahimi\PHPRouter\Request;
use MiladRahimi\PHPRouter\Response;
use model\Maestro\Clase;
use model\Maestro\Raza;
use model\Usuario\Usuario;
use PHPMailer\PHPMailer\PHPMailer;

/**
 * Created by PhpStorm.
 * User: Lucky
 * Date: 02/03/2018
 * Time: 0:14
 */
class RegisterController
{
	public function index(Response $response) { // get
		$response->redirect("/registrarse/paso-1");
	}
	
	public function indexPaso1() { // get
		echo render()->render("/registrarse-paso-1.twig");
	}
	
	public function indexPaso2() { // get
		$listaClases = Clase::getAll();
		$listaRazas = Raza::getAll();
		echo render()->render("/registrarse-paso-2.twig", array(
			"listaClases" => $listaClases,
			"listaRazas" => $listaRazas
		));
	}
	
	public function indexPaso3() { // get
		$listaClases = Clase::getAll();
		$listaRazas = Raza::getAll();
		echo render()->render("/registrarse-paso-3.twig", array(
			"listaClases" => $listaClases,
			"listaRazas" => $listaRazas
		));
	}
	
	public function registrarsePaso1(Request $request, Response $response) { // post
		$target = new Usuario;
		$target->nombre = $request->post("nombre");
		$target->apellido = $request->post("apellido");
		$target->correo = $request->post("correo");
		$target->nickname = $request->post("nickname");
		$target->password = $request->post("password");
		
		$mail = new PHPMailer(true);
		$mail->isSMTP();                                      // Set mailer to use SMTP
		$mail->Host = 'genubi.com.ar';  // Specify main and backup SMTP servers
		$mail->SMTPAuth = true;                               // Enable SMTP authentication
		$mail->Username = 'info@genubi.com.ar';                 // SMTP username
		$mail->Password = 'D7Oo7DNv';                           // SMTP password
		$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
		$mail->Port = 587;                                    // TCP port to connect to

		$mail->setFrom('info@genubi.com.ar', 'Genubi');

		$mail->addAddress('lucaslois95@gmail.com');               // Name is optional
		$mail->isHTML(true);                                  // Set email format to HTML
		$mail->Subject = 'Here is the subject';
		$mail->Body    = 'This is the HTML message body <b>in bold!</b>';
		$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
		
		$mail->send();
		$response->redirect("/registrarse/paso-2");
	}
	
	public function registrarsePaso2(Request $request, Response $response) { // post
		
		$response->redirect("/registrarse/paso-3");
	}
}