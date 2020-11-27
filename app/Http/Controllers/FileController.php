<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\File;
use App\User;
use App\Mail\Confirm;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class FileController extends Controller
{
    public function testNow(){
        return response()->json(['Fecha'=>now()], 200);
    }
    public function storeFile(Request $request){
        $user = $request->user();
        $token = $user->tokenapp()->first();
        $t = $token->token;
        $response = Http::attach(
            'file', file_get_contents($request->file), $request->name
        )->withToken($t)->post('192.168.43.120/api/agregar/archivo', [
            'file'=>$request->file,
            'name'=>$request->name
        ]);
        Mail::to($request->user())->send(new Confirm($request->name));
        return response()->json($response->json(), $response->status());
    }
    public function getFile(Request $request){
        $user = $request->user();
        $token = $user->tokenapp()->first();
        $t = $token->token;
        $response = Http::withToken($t)->get('192.168.43.120/api/ver/archivo', [
            'name'=>$request->name
        ]);
        return response()->json($response->json(), $response->status());
    }
}
