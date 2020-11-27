<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use App\TokenApp;
use App\Mail\Register;
use App\Mail\Access;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function logIn(Request $request){
        $request->validate([
            'email'=>'required|email',
            'password'=>'required'
        ]);
        $response = Http::post('192.168.43.120/api/login', [
            'email' => $request->email,
            'password'=>$request->password
        ])['token'];

        $user = User::where('email',$request->email)->first();

        if(! $user || ! Hash::check($request->password, $user->password)){
            return response()->json(['error' => 'Credenciales incorrectas'], 401);
        }
        TokenApp::create(['user_id'=>$user->id,'token'=>$response]);
        $token = $user->createToken($request->email, ['user:user'])->plainTextToken;
        Mail::to($user)->send(new Access($request->email));
        return response()->json(['token server 1'=>$token, 'token sever 2'=>$response], 201);
    }

    public function signIn(Request $request){
        $request->validate([
            'email'=>'required|email',
            'password'=>'required',
            'name'=>'required'
        ]);
        $respuesta = Http::post('192.168.43.120/api/insertar/usuario', [
            'name' => $request->name,
            'email' => $request->email,
            'password'=>$request->password
        ]);
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        if($user->save()){
            Mail::to($user)->send(new Register($request->name, $request->email));
            return response()->json(['User'=>$user ,'User sv2'=>$respuesta->json()], 201);
        }
                
        return abort(400, 'Error al generar el registro');
    }

    public function checkConnection(){
        $response = Http::get('192.168.43.120/api/prueba');
        return $response;
    }
}
