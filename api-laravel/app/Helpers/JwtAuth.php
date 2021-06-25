<?php
namespace App\Helpers;
//Creación de un helper: clase que contiene varios métodos que ayudan a hacer una funcionalidad cocreta
//Este helper actua como un servicio que contiene un método para el login va a comprobar que el usuario que intenta idetificarse existe, en el caso de que exista va a generar un token, ese token lo devuelve un método del controlador, y utilizando ese token en cada una de las peticiones que se hagan al API se van a poder comprobar si el usuario esta identificado o no

//Librerias para generar un JWT
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\DB;
use App\User;

class JwtAuth{
	public $key;

	public function __construct(){
		$this->key ='esta-es-mi-clave-secreta-039094093943924';
	}


	public function signup($email, $password, $getToken= null){
		//Comprobar que el usuario que se intenta pasar es correcto
		$user = User::where(
			array(
				'email' => $email, //sacar todos los usuarios que contengas el mismo email y password del parametro
				'password' => $password
			))->first(); //Sacar el primer elemento
//Se tiene un objeto completo en la variable $user

	$signup =false;
	if(is_object($user)){ //Si user es un objeto, entonces se puede logear
		$signup = true;
		}
	if($signup){ 
		//Generar el token y devolverlo
		//Objeto del usuario que va almacenar el token
		$token = array( //objeto completo con los datos del usuarios
			'sub'=> $user->id,
			'email'=>$user->email,
			'name'=>$user->name,
			'surname'=>$user->surname,
			'iat'=> time(), //Tiempo de creación del token
			'exp'=> time()+(7*24*60*60)//tiempo de expiración del token
		);

		//decodificar el token mediante encode
		//codifico ese objeto y converitrlo en un token de json web token mediante el uso de encode, se le pasa el token, una clave secreta, algoritmo de codificación de ese JWT
		$jwt = JWT::encode($token,$this->key, 'HS256');
		//decodificar ese mismo token ya cifrado
		$decoded = JWT::decode($jwt, $this->key, array('HS256')); //objeto del usuario identificado

		if(is_null($getToken)){
			return $jwt;
		}else{
			return $decoded;
		}
	}else{
		//Devolver un error
		return array('status'=>'error', 'message'=>'Login ha fallado!!');
	}
	}

	//Recoger un token de jwt y Comprobar si es correcto o no es correcto el token
	public function checkToken($jwt, $getIdentity = false){
		$auth = false; 
		try{ 
			$decoded = JWT::decode($jwt, $this->key, array('HS256'));

			}catch(\UnexpectedValueException $e){
				$auth = false;
			}catch(\DomainException $e){
				$auth = false;
			}
			if(isset($decoded) && is_object($decoded)&& isset($decoded->sub)){
				$auth = true;
			}else{
				$auth = false;
			}

		if($getIdentity){
			return $decoded;
		}
		return $auth;

	}
}


