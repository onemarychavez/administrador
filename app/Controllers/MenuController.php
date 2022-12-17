<?php

namespace App\Controllers;

use App\Models\CategoriasModel;
use App\Models\EmpresasModel;
use App\Models\MenuDetalleModel;
use App\Models\MenuModel;
use App\Models\SucursalesModel;
use App\Models\UsuariosModel;
use Config\Database;
use CodeIgniter\HTTP\IncomingRequest;

class MenuController extends BaseController
{
    
    private $menu,$mdetalle,$db;
    protected $request,$session;
    public function __construct(){
        $this->request = \Config\Services::request();
        $this->session = \Config\Services::session();
        $this->db = Database::connect();
        $this->menu = new MenuModel();
        $this->mdetalle = new MenuDetalleModel();
    }
    
    
    public function index()
    {   
        if(!$this->session->has('usuario')){
            return redirect()->to('');
        }
        return view('menu/index');
    }

    public function list(){
        try {

            $filtro = [ "menu.activo"=>true];
            if(intval($this->session->get('idempresa'))>0){
                $filtro["menu.idempresa"]=intval($this->session->get('idempresa'));
            }
            $menues = $this->menu->select("menu.*,empresas.nombre as empresa,categoria_negocio.nombre as categoria")
                        ->join("empresas","empresas.idempresa= menu.idempresa","inner")
                        ->join("categoria_negocio","categoria_negocio.idcategoria=menu.idcategoria","inner")
                        ->where($filtro)->findAll();
            if(count($menues)<=0){
                return $this->response->setStatusCode(404,'Not Found')
                ->setJSON(['message'=>'No se encontraron Menus']);
            }
            $data = [];
            foreach($menues as $menu){
                array_push($data,[
                    'key'=>intval($menu['idmenu']),
                    'keyempresa'=>intval($menu['idempresa']),
                    'empresa'=>$menu['empresa'],
                    'nombre'=>$menu['nombre'],
                    'keycategoria'=>intval($menu['idcategoria']),
                    'categoria'=>$menu['categoria']
                ]);
            }
            return $this->response->setStatusCode(200,'OK')
            ->setJSON($data);

        } catch (\Throwable $th) {
            return $this->response->setStatusCode(500,'internal server')
            ->setJSON(['message'=>$th->getMessage()]);
        }
    }

    public function listFilter($empresa=0,$categoria=0){
        try {
            $filtros = [
                'activo'=>true
            ];
            if($empresa >0){
                $filtros['idempresa']=$empresa;
            }
            if($categoria >0){
                $filtros['idcategoria'] =$categoria;
            }
            $menues = $this->menu->where($filtros)->findAll();
            if(count($menues)<=0){
                return $this->response->setStatusCode(404,'Not Found')
                ->setJSON(['message'=>'No se encontraron Menus']);
            }
            $data = [];
            foreach($menues as $menu){
                array_push($data,[
                    'key'=>intval($menu['idmenu']),
                    'keyempresa'=>intval($menu['idempresa']),
                    'nombre'=>$menu['nombre'],
                    'keycategoria'=>intval($menu['idcategoria'])
                ]);
            }
            return $this->response->setStatusCode(200,'OK')
            ->setJSON($data);

        } catch (\Throwable $th) {
            return $this->response->setStatusCode(500,'internal server')
            ->setJSON(['message'=>$th->getMessage()]);
        }
    }

    private function deleteImage($image){
        $baseurl=substr(APPPATH ,0,-4);
        $rute = $baseurl."public\\images\\menu\\".$image;
        unlink($rute);
    }

    private function saveImagen($image,$name,$id){
        $baseurl=substr(APPPATH ,0,-4);
        $part = explode(".",$name);
        $imageName = $id.$part[0].date('Ymdhis').'.'.$part[1];
        $decode = base64_decode($image);
        $rute = $baseurl."public\\images\\menu\\".$imageName;
        file_put_contents($rute,$decode);
        return 'images/menu/'.$imageName;
    }

    public function getById($menu){
        try {
            $men = $this->menu->find($menu);
            if($men == null){
                return $this->response->setStatusCode(404,'Not Found')
                ->setJSON(['message'=>'No se Menu']);
            }
            $menuDetalle = $this->mdetalle->where([
                'idmenu'=>$menu,
                'activo'=>true
            ])->findAll();
            $itemMenu = [
                'keymenu'=>intval($men['idmenu']),
                'keyempresa'=>intval($men['idempresa']),
                'nombre'=>$men['nombre'],
                'keycategoria'=>intval($men['idcategoria']),
                'detalle'=>[]
            ];
            $baseurl = getenv('app.baseURL',true);
            foreach($menuDetalle as $deta){
                array_push($itemMenu['detalle'],[
                    'keydetalle'=>intval($deta['idmenudetalle']),
                    'nombre'=>$deta['nombre'],
                    'precio'=>doubleval($deta['precio']),
                    'foto'=>$baseurl."/images/menu/".trim($deta['foto']),
                    'descripcion'=>$deta['descripcion'],
                    'extras'=> json_decode($deta['extras'])
                ]);
            }
            return $this->response->setStatusCode(200,'OK')
            ->setJSON($itemMenu);


        } catch (\Throwable $th) {
            return $this->response->setStatusCode(500,'internal server')
            ->setJSON(['message'=>$th->getMessage()]);
        }
    }

    public function create(){
        try {
           $datos = $this->request->getJSON(true);
           $campos = ['nombre','empresa','categoria','platillos'];
           $ok = true;
           foreach($campos as $campo){
                if(!isset($datos[$campo])){
                    $ok = false;
                    break;
                }
           }
           if($ok){
                $platoCampos = ['nombre','descripcion','precio','imagen','imagen_name','extras'];
                $platos = $datos['platillos'];
                foreach($platos as $plato){
                    $valido = true;
                    foreach($platoCampos as $c){
                        if(!isset($plato[$c])){
                            $valido=false;
                            break;
                        }
                    }
                    if(!$valido){
                        $ok=false;
                        break;
                    }
                }
                if(!$ok){
                    return $this->response->setStatusCode(400,'Cliente Error')
                    ->setJSON(['message'=>'Campos Invalidos']);
                }

           }else{
            return $this->response->setStatusCode(400,'Cliente Error')
            ->setJSON(['message'=>'Campos Invalidos']);
           }
           $this->db->transBegin();
           $idmenu = $this->menu->insert([
            'idempresa'=>$datos['empresa'],
            'nombre'=>$datos['nombre'],
            'idcategoria'=>$datos['categoria']
           ]);
           $platos = $datos['platillos'];
           foreach($platos as $plato){
                $iddetallemenu = $this->mdetalle->insert([
                    'idmenu'=>$idmenu,
                    'nombre'=>$plato['nombre'],
                    'precio'=>$plato['precio'],
                    'descripcion'=>$plato['descripcion'],
                    'foto'=>'',
                    'extras'=> json_encode($plato['extras'])
                ]);
                $img = $this->saveImagen($plato['imagen'],$plato['imagen_name'],$iddetallemenu);
                $this->mdetalle->update($iddetallemenu,[
                    'foto'=>$img
                ]);
           }
           $this->db->transCommit();
           return $this->response->setStatusCode(201,'Created')
            ->setJSON(['key'=>$idmenu]);
        } catch (\Throwable $th) {
            $this->db->transRollback();
            return $this->response->setStatusCode(500,'internal server')
            ->setJSON(['message'=>$th->getMessage()]);
        }
    }
    
    public function delete($menu){
        try {
            $men = $this->menu->find($menu);
            if($men == null){
                return $this->response->setStatusCode(404,'Not Found')
                ->setJSON(['message'=>'No se encontro Menu']);
            }
            $this->menu->update($menu,[
                'activo'=>false
            ]);
            return $this->response->setStatusCode(200,'OK')
            ->setJSON(['message'=>'menu eliminado']);


        } catch (\Throwable $th) {
            return $this->response->setStatusCode(500,'internal server')
            ->setJSON(['message'=>$th->getMessage()]);
        }
    }
}
