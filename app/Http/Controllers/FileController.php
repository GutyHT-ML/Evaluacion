<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\File;
use App\User;
use App\Mail\Confirm;
use App\Mail\Notify;
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
        $path = Storage::disk('local')->putFile('files/', $request->file('file'));
        $file = new File();
        $file->name = $request->name;
        $file->user_id = $request->user()->id;
        $file->file = $path;
        if($file->save()){
            $response = Http::attach(
                'file', file_get_contents($request->file), $request->name
            )->withToken($t)->post('192.168.43.120/api/agregar/archivo', [
                'file'=>$request->file('file'),
                'name'=>$request->name
            ]);
            Mail::to($request->user())->send(new Confirm($request->name));
            return response()->json($response->json(), $response->status());    
        }
        return response()->json(['Error'=>'Error while saving file'], 400);
    }
    public function downloadFile(Request $request){
        $request->validate([
            'name'=>'required'
        ]);
        $file = File::where('name', $request->name)->first();
        if(! $file){
            $user = $request->user();
            $token = $user->tokenapp()->first();
            $t = $token->token;
            $fichier = Http::withToken($t)->withHeaders(['Accept:'=>'application/json'])
            ->get('192.168.43.120/api/descargar', [
                'name'=>$request->name
            ]);
            Storage::disk('local')->put($fichier['file'], $fichier['content']);
            $archivo = new File();
            $archivo->name = $request->name;
            $archivo->user_id = $request->user()->id;
            $archivo->file = $fichier['file'];    
            if($archivo->save()){
                Mail::to($request->user())->send(new Notify($request->name));
                return Storage::disk('local')->download($fichier['file']);
            }
        }
        Mail::to($request->user())->send(new Notify($request->name));
        return Storage::disk('local')->download($file->file);
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
