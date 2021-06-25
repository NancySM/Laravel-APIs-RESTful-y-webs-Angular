<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request; 
use App\Helpers\JwtAuth;
use App\Http\Requests;
use App\Car;

class Carcontroller extends Controller
{
   //Mostrar todos los registros de la tabla carros de la bd
    public function index(Request $request){
    	$cars = Car::all()->load('user'); //Obtener todos los vehiculos
        return response()-> json(array(
            'cars'=> $cars,
            'status'=>'success'
        ),200);
    	
    }

    //Método que muestra un vehiculo que coincida con el id
    public function show($id){
        $car = Car::find($id);

        if(is_object($car)){
        $car = Car::find($id)->load('user'); //Cargar los datos del usuario que ha creado el coche
        return response()->json(array('car'=> $car, 'status'=>'success'),200);
    }else{
        return response()->json(array('message'=> 'El coche no existe','status'=>'error'),200);
    }
    
    }
     //Metodo para agregar un nuevo coche la base de datos
    //Guardar y validar datos servicio rest
    public function store(Request $request){
	$hash = $request->header('Authorization',null);

    	$jwtAuth = new JwtAuth();
    	$checkToken = $jwtAuth->checkToken($hash);

    	if($checkToken){ //En caso de que el token sea valido
    		//Recoger datos por POST
    		$json = $request->input('json',null);
    		$params = json_decode($json);
    		$params_array = json_decode($json, true);

    		//Conseguir el usuario identificado
    		$user = $jwtAuth->checkToken($hash, true); //devuelve el objeto de los datos del usuario

    		//Validación
                $validate = \Validator::make($params_array, [
                'title'=>'required|min:5',
                'description'=>'required',
                'price'=>'required',
                'status'=>'required'
            ]);

                if($validate->fails()){
                    return response()->json($validate->errors(), 400);
                }
       
    		

    		//Guardar el coche
    		$car = new Car();
    		$car->user_id = $user->sub;
    		$car->title = $params->title;
    		$car->description =$params->description;
    		$car->price = $params->price;
    		$car->status =$params->status;

    		$car->save();

    		$data = array(
    			'car'=> $car,
    			'status'=>'success',
    			'code'=>200,
    		);
    	}else{
    	//Devolver error
    		$data = array(
    			'message'=> 'Login incorrecto',
    			'status'=>'error',
    			'code'=>400,
    		);
    }
    return response()->json($data, 200);
    }

    //Metodo para editar un vehiculo de la base de datos
    public function update($id, Request $request){
        $hash = $request->header('Authorization',null); //La cabecera de autenticación que lleva todo el token

        $jwtAuth = new JwtAuth();
        //Comprobar si ese token es valido o no
        $checkToken = $jwtAuth->checkToken($hash);

        if($checkToken){ //En caso de que el token sea valido
            //Recoger datos por POST
            $json = $request->input('json',null);
            $params = json_decode($json);
            $params_array = json_decode($json, true);

            //Validar datos 
            $validate = \Validator::make($params_array, [
                'title'=>'required|min:5',
                'description'=>'required',
                'price'=>'required',
                'status'=>'required'
            ]);

                if($validate->fails()){
                    return response()->json($validate->errors(), 400);
                }

            //Actualizar el coche
                //Sacar de la base de datos cuyo id = al id de la url
                unset($params_array['id']);
                unset($params_array['user_id']);
                unset($params_array['created_at']);
                unset($params_array['user']);

                $car = Car::where('id', $id)->update($params_array);
                $data = array(
                    'car'=> $params,
                    'status'=> 'success',
                    'code'=> 200
                );
    }else{
        //Devolver error
            $data = array(
                'message'=> 'Login incorrecto',
                'status'=>'error',
                'code'=>400,
            );
    }
    return response()->json($data, 200);
}

public function destroy($id, Request $request){
    $hash = $request->header('Authorization',null); //La cabecera de autenticación que lleva todo el token

     $jwtAuth = new JwtAuth();
        //Comprobar si ese token es valido o no
        $checkToken = $jwtAuth->checkToken($hash);

        if($checkToken){ //En caso de que el token sea valido

            //Comprobar que el registro exista
            $car = Car::find($id);

            //borrar el registro
            $car->delete();

            //Devolver el registro borrado
            $data = array(
                'car'=> $car,
                'status'=>'success',
                'code'=> 200
            );

        }else{
            $data = array(
                'status'=> 'error',
                'code'=> 400,
                'message'=> 'Login incorrecto'
            );
        }
        return response()->json($data, 200);
        

}
}//end class
