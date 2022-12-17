<?php

namespace App\Controllers;

use App\Models\UsuariosModel;
use CodeIgniter\HTTP\IncomingRequest;

class RegistrarController extends BaseController
{
    
    private $registrar;
    protected $request,$session;
    public function __construct(){
        $config = new \Config\Encryption();
        $config->key = getenv('encryption.key');
        $config->driver = getenv('encryption.driver');
        $config->blockSize = getenv('encryption.blockSize');
        $config->digest = getenv('encryption.digest');        
        $this->request = \Config\Services::request();
        $this->registrar = new UsuariosModel();
        $this->encrypt = \Config\Services::encrypter($config);
        $this->session = \Config\Services::session();
    }
    
    public function index()
    {   
        if(!$this->session->has('usuario')){
            return redirect()->to('');
        }
        return view('registrar/index');
    }

    public function list(){
        try {
            $registrar = $this->registrar->where([
                'activo'=>true
            ])->findAll();
            if(count($registrar)<=0){
                return $this->response->setStatusCode(404,'not found')
                ->setJSON(['message'=>'USUARIOS NO ENCONTRADOS']);
            }
            $users = [];
            foreach($registrar as $row){
                array_push($users,[
                    'key'=>intval($row['idempresa']),
                    'nombres'=>$row['nombres'],
                    'apellidos'=>$row['apellidos'],
                    'dui'=>$row['dui'],
                    'nit'=>$row['nit'],
                    'usuario'=>$row['usuario'],
                    'rol'=>intval($row['idrol'])
                ]);
            }
            return $this->response->setStatusCode(200,'OK')
            ->setJSON($users);


        } catch (\Throwable $th) {
            return $this->response->setStatusCode(500,'internal server')
            ->setJSON(['message'=>$th->getMessage()]);
        }
    }

    public function listById($id){
        try {
            $registrar = $this->registrar->find($id);
            if($registrar==null){
                return $this->response->setStatusCode(404,'not found')
                ->setJSON(['message'=>'USUARIO NO ENCONTRADOS']);
            }
        
    
              $users= [
                    'key'=>intval($registrar['idempresa']),
                    'nombres'=>$registrar['nombres'],
                    'apellidos'=>$registrar['apellidos'],
                    'dui'=>$registrar['dui'],
                    'nit'=>$registrar['nit'],
                    'usuario'=>$registrar['usuario'],
                    'password'=>$this->encrypt->decrypt(base64_decode(trim($registrar['clave']))),
                    'rol'=>intval($registrar['idrol'])
                ];
            
            return $this->response->setStatusCode(200,'OK')
            ->setJSON($users);


        } catch (\Throwable $th) {
            return $this->response->setStatusCode(500,'internal server')
            ->setJSON(['message'=>$th->getMessage()]);
        }
    }


    public function create(){
        try {
            $obligatorios = ['nombres','apellidos','dui','nit','usuario','clave','rol'];
            $datos = $this->request->getJSON(true);
            $error = false;
            foreach($obligatorios as $camp){
                if(!isset($datos[$camp])){
                    $error = true;
                    break;
                }
            }//VALIDA LOS CAMPOS OBLIGATORIOS
            if($error){
                return $this->response->setStatusCode(400,'Client Error')
                ->setJSON(['message'=>'CAMPOS INVALIDOS']);
            }

            $userNameRepit = $this->registrar->where(['registrar'=>trim($datos['registrar'])])->findAll();
            if(count($userNameRepit)>0){
                return $this->response->setStatusCode(400,'Client Error')
                ->setJSON(['message'=>'NOMBRE DE USUARIO NO DISPONIBLE']);
            }

            $newUsuario = [
                'nombres'=> trim($datos['nombres']),
                'apellidos'=> trim($datos['apellidos']),
                'dui'=>trim($datos['dui']),
                'nit'=>trim($datos['nit']),
                'usuario'=>trim($datos['usuario']),
                'clave'=>base64_encode($this->encrypt->encrypt(trim($datos['clave']))),
                'idrol'=> $datos['rol']
            ];

            $iduser = $this->registrar->insert($newUsuario);
            return $this->response->setStatusCode(201,'CREATED')
            ->setJSON(['userKey'=>$iduser]);
            
        } catch (\Throwable $th) {
            return $this->response->setStatusCode(500,'internal server')
            ->setJSON(['message'=>$th->getMessage()]);
        }
    }

    public function update($idusario){
        try {
            $datos = $this->request->getJSON(true);
            $existe = $this->registrar->find($idusario);
            if($existe == null){
                return $this->response->setStatusCode(404,'not found')
                ->setJSON(['message'=>'USUARIO NO EXISTE']);
            }
            $obligatorios = ['nombres','apellidos','dui','nit','usuario','clave','rol'];
            $error = false;
            foreach($obligatorios as $camp){
                if(!isset($datos[$camp])){
                    $error = true;
                    break;
                }
            }//VALIDA LOS CAMPOS OBLIGATORIOS
            if($error){
                return $this->response->setStatusCode(400,'Client Error')
                ->setJSON(['message'=>'CAMPOS INVALIDOS']);
            } 

            $userNameRepit = $this->registrar->where(['registrar'=>trim($datos['registrar'])])->findAll();
            if(count($userNameRepit)>0){
                return $this->response->setStatusCode(400,'Client Error')
                ->setJSON(['message'=>'NOMBRE DE USUARIO NO DISPONIBLE']);
            } 
            $newUsuario = [
                'nombres'=> trim($datos['nombres']),
                'apellidos'=> trim($datos['apellidos']),
                'dui'=>trim($datos['dui']),
                'nit'=>trim($datos['nit']),
                'usuario'=>trim($datos['usuario']),
                'clave'=> $this->encrypt->encrypt(trim($datos['clave'])),
                'idrol'=> $datos['rol']
            ];
            $this->registrar->update($idusario,$newUsuario);
            return $this->response->setStatusCode(200,'OK')
            ->setJSON(['message'=>'USUARIO ACTUALIZADO']);


        } catch (\Throwable $th) {
            return $this->response->setStatusCode(500,'internal server')
            ->setJSON(['message'=>$th->getMessage()]);
        }
    }

    public function delete($idempresa){
        try {
            $existe = $this->registrar->find($idempresa);
            if($existe == null)
            {
                return $this->response->setStatusCode(404,'not found')
                ->setJSON(['message'=>'USUARIO NO EXISTE']);
            }
            $this->registrar->update($idempresa,[
                'activo'=>false
            ]);
            return $this->response->setStatusCode(200,'OK')
            ->setJSON(['message'=>'USUARIO ELIMINADO']);
        } catch (\Throwable $th) {
            return $this->response->setStatusCode(500,'internal server')
            ->setJSON(['message'=>$th->getMessage()]);
        }
    }
}
