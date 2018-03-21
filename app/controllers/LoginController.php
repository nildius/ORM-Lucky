<?php
use core\ORM\Carbon;
use core\Render;
use MiladRahimi\PHPRouter\Request;
use MiladRahimi\PHPRouter\Response;
use model\Usuario\Auth;
use model\Usuario\Usuario;

/**
 * Created by PhpStorm.
 * User: Lucky
 * Date: 26/02/2018
 * Time: 1:09
 */
class LoginController
{
	public static function checkFirstLogin()
	{
		$auth = Auth::getAuth();
		$usuario = $auth->getUsuario();
		if($usuario) return;
		$datosNavegador = $_SERVER['HTTP_USER_AGENT'];
		$sesion = \model\Usuario\Sesion::buscar($datosNavegador);
		if($sesion) {
			$target = $sesion->getUsuario();
			$auth->login($target->id);
		}
	}
	
	public function index()
	{
		echo Render::$twig->render("/iniciar_sesion.twig");
	}
	
	public function login(Request $request, Response $response)
	{
		var_dump($request->post());
		$correo = $request->post("correo");
		$password = Auth::crypt($request->post("password"));
		$auth = Auth::getAuth();
		$usuario = Usuario::getUsuarioByCorreoAndPassword($correo, $password);
		if($usuario)
		{
			$auth->login($usuario->id);
			$auth->agregarExito("Bienvenido de nuevo, $usuario->nombre.");
			
			session_regenerate_id();
			
			if($request->post("remind")) { // Si el usuario eligió recordar la sesión
				$sesion = new \model\Usuario\Sesion();
				$sesion->idUsuario = $usuario->id;
				$sesion->token = $_SERVER["HTTP_USER_AGENT"];
				$sesion->ip = $request->getIP();
				$sesion->fecha_creacion = Carbon::now();
				$sesion->fecha_expiracion = Carbon::now()->addDays(15);
				$sesion->insert();
			}
			$response->redirect("/");
		}
		else {
			$auth->agregarError("Tus credenciales son incorrectas. Intentalo de nuevo.");
			//$response->redirect("/iniciar-sesion");
		}
	}
	
	public function logout(Response $response)
	{
		$auth = Auth::getAuth();
		$target = $auth->getUsuario();
		\model\Usuario\Sesion::destruirSesionesDelUsuario($target);
		$auth->logout();
		
		session_unset();
		
		$response->redirect("/");
	}
}