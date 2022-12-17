<?php

namespace App\Controllers;

use App\Models\CategoriasEmpresasModel;
use App\Models\CategoriasModel;
use App\Models\EmpresasModel;
use App\Models\SucursalesModel;
use App\Models\UsuariosModel;
use Config\Database;
use CodeIgniter\HTTP\IncomingRequest;

class EmpresaController extends BaseController
{
    
    private $empresa,$categoriaEmpresa;
    protected $request,$session,$sucursal,$db;
    public function __construct(){
        $this->request = \Config\Services::request();
        $this->empresa = new EmpresasModel();
        $this->session = \Config\Services::session();
        $this->sucursal = new SucursalesModel();
        //$this->categoria = new CategoriasModel();
        $this->categoriaEmpresa = new CategoriasEmpresasModel();
        $this->db = Database::connect();
    }
    
    public function index()
    {   
        if(!$this->session->has('usuario')){
            return redirect()->to('');
        }
        return view('empresa/index');
    }

    public function listById($id){
        try {
            $empresa = $this->empresa->find($id);
            if($empresa == null){
                return $this->response->setStatusCode(404,'NOT FOUND')
                ->setJSON(['message'=>'NO SE ENCONTRO EMPRESA']);
            }
            //$baseurl = getenv('app.baseURL',true);
            $rows = [
                    'key'=>intval($empresa['idempresa']),
                    'nombre'=>$empresa['nombre'],
                    'representante'=>$empresa['representante'],
                    'razon'=>$empresa['razonsocial'],
                    'telefono'=>$empresa['telefono'],
                    'nrc'=>$empresa['nrc'],
                    'giro'=>$empresa['giro'],
                    'logo'=>strlen(trim($empresa['logo']))>0? "/images/empresa/".trim($empresa['logo']) :'',
                    'categoria'=>$this->getCategorias($empresa['idempresa'])
                ];
            
            return $this->response->setStatusCode(200,'OK')
            ->setJSON($rows);
        } catch (\Throwable $th) {
            return $this->response->setStatusCode(500,'internal server')
            ->setJSON(['message'=>$th->getMessage()]);
        }
    }

    private function getCategorias($empresa){
        $categorias = $this->categoriaEmpresa->where([
            'activo'=>true,
            'idempresa'=>$empresa
        ])->findAll();

        $cate = [];
        foreach($categorias as $row){
            array_push($cate,$row['idcategoria']);
        }
        return $cate;
    }

    public function list(){
        try {
            $rol = intval($this->session->get('idrol'));
            $filtros = ['activo'=>true];
            if($rol === 3){
                $filtros['idempresa']=$this->session->get('idempresa');
            }

            $empresas = $this->empresa->where($filtros)->findAll();
            if(count($empresas)<0){
                return $this->response->setStatusCode(404,'NOT FOUND')
                ->setJSON(['message'=>'NO HAY EMPRESAS']);
            }
            $rows = [];
            $baseurl = getenv('app.baseURL',true);
            foreach($empresas  as $row){
                array_push($rows,[
                    'key'=>intval($row['idempresa']),
                    'nombre'=>$row['nombre'],
                    'representante'=>$row['representante'],
                    'razon'=>$row['razonsocial'],
                    'telefono'=>$row['telefono'],
                    'nrc'=>$row['nrc'],
                    'giro'=>$row['giro'],
                    'logo'=>strlen(trim($row['logo']))>0? $baseurl."/images/empresa/".trim($row['logo']) :''
                ]);
            }
            return $this->response->setStatusCode(200,'OK')
            ->setJSON($rows);
        } catch (\Throwable $th) {
            return $this->response->setStatusCode(500,'internal server')
            ->setJSON(['message'=>$th->getMessage()]);
        }
    }

    private function deleteImage($image){
        $baseurl=substr(APPPATH ,0,-4);
        $rute = $baseurl."public\\images\\empresa\\".$image;
        unlink($rute);
    }

    private function saveImgen($image,$name,$id){
        $baseurl=substr(APPPATH ,0,-4);
        $part = explode(".",$name);
        $imageName = $id.$part[0].date('Ymdhis').'.'.$part[1];
        $decode = base64_decode($image);
        $rute = $baseurl."public\\images\\empresa\\".$imageName;
        file_put_contents($rute,$decode);
        return $imageName;
    }

    public function create(){
        try {
            $data = $this->request->getJSON(true);
            $campos = ['nombre','representante','razon','telefono','nrc','giro','logo','categorias'];
            $validacion = false;
            foreach($campos as $row){
                if(!isset($data[$row])){
                    $validacion = true;
                    break;
                }
            }
            if($validacion){
                return $this->response->setStatusCode(400,'Validation Error')
                ->setJSON(['message'=>'PARAMETROS INVALIDOS']); 
            }
            $this->db->transBegin();
            $logo = trim($data['logo']);
            $newEmpresa = [
                'nombre'=>trim($data['nombre']),
                'representante'=>trim($data['representante']),
                'razonsocial'=>trim($data['razon']),
                'telefono'=>trim($data['telefono']),
                'nrc'=>trim($data['nrc']),
                'giro'=>trim($data['giro']),
                'logo'=> ''
            ];
            $keyEmpresa = $this->empresa->insert($newEmpresa);
            if(strlen($logo)>0){
                $this->empresa->update($keyEmpresa,[
                    'logo'=> $this->saveImgen($logo,$data['img_name'],$keyEmpresa)
                ]);
            }
            foreach($data['categorias'] as $cate){
                $this->categoriaEmpresa->insert([
                    'idempresa'=>$keyEmpresa,
                    'idcategoria'=>$cate
                ]);
            }
            $this->db->transCommit();
            return $this->response->setStatusCode(201,'CREATED')
            ->setJSON(['key'=>$keyEmpresa]);
        } catch (\Throwable $th) {
            $this->db->transRollback();
            return $this->response->setStatusCode(500,'internal server')
            ->setJSON(['message'=>$th->getMessage()]);
        }
    }

    public function update($id){
        try {
            $existe = $this->empresa->find($id);
            if($existe == null){
                return $this->response->setStatusCode(404,'NOT FOUND')
                ->setJSON(['message'=>'EMPRESA NO ENCONTRADA']); 
            }
            $data = $this->request->getJSON(true);
            $campos = ['nombre','representante','razon','telefono','nrc','giro','logo','categorias'];
            $validacion = false;
            foreach($campos as $row){
                if(!isset($data[$row])){
                    $validacion = true;
                    break;
                }
            }
            if($validacion){
                return $this->response->setStatusCode(400,'Validation Error')
                ->setJSON(['message'=>'PARAMETROS INVALIDOS']); 
            }
           
            $logo = trim($data['logo']);
            $this->db->transBegin();
            $newEmpresa = [
                'nombre'=>trim($data['nombre']),
                'representante'=>trim($data['representante']),
                'razonsocial'=>trim($data['razon']),
                'telefono'=>trim($data['telefono']),
                'nrc'=>trim($data['nrc']),
                'giro'=>trim($data['giro']),
            ];
            if(strlen($logo)>0){
                $oldImage = $existe['logo'];
                $this->deleteImage($oldImage);
                $newEmpresa['logo'] = $this->saveImgen($logo,$data['img_name'],$id);
            }
            $this->empresa->update($id,$newEmpresa);
            $categorias = $data['categorias'];
            if(count($categorias)>0){
               foreach($categorias as $row){
                    $existe = $this->categoriaEmpresa->where([
                        'idempresa'=>$id,
                        'idcategoria'=>$row
                    ])->first();
                    if($existe != null){
                          $this->categoriaEmpresa->update($existe['idcateemp'],[
                            'activo'=>true 
                          ]);  
                    }else{
                        $this->categoriaEmpresa->insert([
                            'idempresa'=>$id,
                            'idcategoria'=>$row
                        ]);
                    }
               }
            }else{
                $this->categoriaEmpresa
                ->where([
                    'idempresa'=>$id
                ])
                ->set([
                    'activo'=>false
                ])->update();

            }
            $this->db->transCommit();
            return $this->response->setStatusCode(200,'OK')
            ->setJSON(['message'=>'Empresa Actualizada']);
        } catch (\Throwable $th) {
            $this->db->transRollback();
            return $this->response->setStatusCode(500,'internal server')
            ->setJSON(['message'=>$th->getMessage()]);
        }
    }

    public function delete($id){
        try {
            $existe = $this->empresa->find($id);
            if($existe == null){
                return $this->response->setStatusCode(404,'NOT FOUND')
                ->setJSON(['message'=>'EMPRESA NO ENCONTRADA']); 
            }
            $this->db->transBegin();
            $this->empresa->update($id,[
                'activo'=>false
            ]);
            $this->sucursal->where([
                'idempresa'=>$id
            ])->set(['activo'=>false])
            ->update();
            $this->db->transCommit();
            return $this->response->setStatusCode(200,'OK')
            ->setJSON(['message'=>'Empresa ELIMINADA']);
        } catch (\Throwable $th) {
            $this->db->transRollback();
            return $this->response->setStatusCode(500,'internal server')
            ->setJSON(['message'=>$th->getMessage()]);
        }
    }
   
}
