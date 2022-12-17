<?= $this->extend('base/index') ?>

<?= $this->section('body') ?>
    <div class="row">
        <div class="col-12">
            <h4>Menus</h4>
        </div>
    </div>
    <div class="row">
        <div class="col-12 card shadow">
            <div class="card-body">
                <div class="row">
                    <div class="col-10">
                        <div class="row">
                            <div class="col-6">
                               
                            </div>
                            <div class="col-4">
                                
                            </div>
                            <div class="col-2">
                                
                            </div>
                        </div>
                    </div>
                    <div class="col-2 align-items-end">
                    <Button type="button" id="btnNew" class="btn btn-success float-right" >Nuevo</Button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 card shadow">
            <div class="card-body responsive-table">
                <table class="table table-sm table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Empresa</th>
                            <th>Nombre de Menu</th>
                            <th>Categoria</th>
                            
                        </tr>
                    </thead>
                    <tbody id="tabla"></tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Nuevo Menu</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label>Nombre de Menu</label>
                            <input type="text" class="form-control" id="nombremenu"  />
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label>Empresa</label>
                            <select class="form-control" id="empresas"></select>
                        </div>
                    </div>
                    
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label>Categoria</label>
                            <select  id="categoriam" class="form-control"></select>
                        </div>
                    </div>
                    <div class="col-6">
                        <button type="button" id="btnaddproducto" class="btn btn-success btn-sm">+</button>
                    </div>
                </div> 
                <div class="row">
                    <div class="col-12 responsive-table">
                        <table class="table table-sm table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nombre del Producto</th>
                                    <th>Precio</th>
                                    <th>Opciones</th>
                                </tr>
                            </thead>
                            <tbody id="tblmenu"></tbody>
                        </table>
                    </div>
                </div>    
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" id="btnCreate" class="btn btn-primary">Guardar</button>
                <button type="button" id="btnUpdate" class="btn btn-primary">Actualizar</button>
            </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="modal2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xs">
            <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 text-center">
                        <h3 class="text-danger" >Advertencia!!</h3>
                        <p>Estas seguro de eliminar esta Sucursal?</p>
                    </div>
                </div>
                    
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" id="btnDelete" class="btn btn-danger">Eliminar</button>
            </div>
            </div>
        </div>
    </div> 

    <div class="modal fade" id="modalProducto" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xs">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Nuevo Platillo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
                
            </div>
            <div class="modal-body">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="home-tab" data-toggle="tab" data-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Datos Generales</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="profile-tab" data-toggle="tab" data-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Extras</button>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label >Nombre de Producto</label>
                                <input type="text" class="form-control" id="nombreproducto">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Descripcion</label>
                                <input type="text" class="form-control" id="descripcionproducto">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="">precio</label>
                                <input type="number" class="form-control" id="precioproducto">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="">Imagen</label>
                                <img src="" class="img img-thumbnail" id="img" alt="" />
                                <input type="file" class="form-control" id="btnimagen">
                            </div>
                        </div>
                    </div>   
                </div>
                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="">Nombre de Extra</label>
                                <input type="text" class="form-control" id="extranombre">
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label for="">Precio Extra</label>
                                <input type="number" class="form-control" id="extraprecio">
                            </div>
                        </div>
                        <div class="col-3">
                            <button type="button" class="btn btn-success mt-3 btn-sm" id="extrAdd" >+</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th>Nombre</th>
                                        <th>Precio</th>
                                        <th>Quitar</th>
                                    </tr>
                                </thead>
                                <tbody id="tablaextras"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                </div> 
            </div>
            <div class="modal-footer">
                <button type="button" id="btnAddPlatillo" class="btn btn-primary">Guardar</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            </div>
            </div>
        </div>
    </div> 

   

<?= $this->endSection() ?>
   
<?= $this->section('js') ?>
    <script src="<?php echo base_url('dist/js/custom/menu/index.js')?>"></script>
<?= $this->endSection() ?>
