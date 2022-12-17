<?php

namespace App\Controllers;

use App\Models\PedidosModel;

class Home extends BaseController
{   
    protected $request,$session,$pedido;
    public function __construct(){
       $this->pedido = new PedidosModel();
        $this->session = \Config\Services::session();
    }
    
    public function index()
    {
        if(!$this->session->has('usuario')){
            return redirect()->to('');
        }
        return view('home/index');
    }

    public function getEstadisticas(){
        try {
            $idempresa =intval($this->session->get('idempresa'));
            $filtro = '';
            if($idempresa > 0){
                $filtro = "AND idempresa=$idempresa ";
            }
            $query = "COUNT((SELECT idpedido FROM pedidos WHERE estado=0 $filtro AND DATE(fecha)=CURDATE() and activo=1))  
            AS nuevo,COUNT((SELECT idpedido FROM pedidos WHERE estado=1 $filtro AND DATE(fecha)=CURDATE()  and activo=1)) 
            AS procesado , 
            COUNT((SELECT idpedido FROM pedidos WHERE estado=2 $filtro AND DATE(fecha)=CURDATE()  and activo=1)) AS finalizado
            ";

            $data = $this->pedido->select($query)
            ->findAll();

            return $this->response->setStatusCode(200,'OK')
            ->setJSON($data);
        } catch (\Throwable $th) {
            return $this->response->setStatusCode(500,'internal server')
            ->setJSON(['message'=>$th->getMessage()]);
        }
    }
}
