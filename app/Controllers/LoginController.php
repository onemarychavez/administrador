<?php

namespace App\Controllers;

use App\Models\UsuariosModel;
use CodeIgniter\HTTP\IncomingRequest;

class LoginController extends BaseController
{
    
    private $usuario,$encrypt;
    protected $request,$session;
    public function __construct(){
        $config = new \Config\Encryption();
        $config->key = getenv('encryption.key');
        $config->driver = getenv('encryption.driver');
        $config->blockSize = getenv('encryption.blockSize');
        $config->digest = getenv('encryption.digest');        
        $this->request = \Config\Services::request();
        $this->usuario = new UsuariosModel();
        $this->encrypt = \Config\Services::encrypter($config);
        $this->session = \Config\Services::session();
        
    }
    
    public function index()
    {   $this->session->destroy();
        
        return view('login/index');
    }

   public function login(){
    try {
        $datos = $this->request->getJSON(true);
        if(!isset($datos['usuario']) || !isset($datos['clave'])){
            return $this->response->setStatusCode(400,'validation error')
            ->setJSON(['message'=>'Parametros invalidos']);
        }
        $usuario = $this->usuario->where([
            'usuario'=>trim($datos['usuario']),
            'activo'=>true
        ])->first();

        if($usuario == null){
            return $this->response->setStatusCode(400,'validation error')
            ->setJSON(['message'=>'Credenciales Invalidas']);
        }

        $clave = $this->encrypt->decrypt(base64_decode(trim($usuario['clave'])));
        if($clave !== trim($datos['clave'])){
            return $this->response->setStatusCode(400,'validation error')
            ->setJSON(['message'=>'Credenciales Invalidas']);
        }

        $this->session->set($usuario);
        return $this->response->setStatusCode(200,'OK')
            ->setJSON(['message'=>'Credenciales validas']);
        
    } catch (\Throwable $th) {
        return $this->response->setStatusCode(500,'internal server')
        ->setJSON(['message'=>$th->getMessage()]);
    }
   }
}
