<?= $this->extend('base/index') ?>
<?= $this->section('body') ?>
<form action="#" >
    <div class="row">
        <div class="col-6">
            <div class="form-group">
                <label>Nombres</label>
                <input type="text" class="form-control" id="nombre"  />
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label >Apellidos</label>
                <input type="text" class="form-control" id="apellido">
            </div>
        </div>
        
    </div>
    <div class="row">
        <div class="col-6">
            <div class="row p-0">
                <div class="col-6">
                    <div class="form-group">
                        <label >Dui</label>
                        <input type="text" class="form-control" id="dui">
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label >Nit</label>
                        <input type="text" class="form-control" id="nit">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label >Rol</label>
                <select class="form-control" id="rol"></select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-6">
            <div class="form-group">
                <label>Usuario</label>
                <input type="text" class="form-control" id="usuario"  />
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" class="form-control" id="clave"  />
                
            </div>
        </div>
    </div> 
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" id="btnCreate" class="btn btn-primary">Guardar</button>
        
    </div> 
</form>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
    <script src="<?php echo base_url('dist/js/custom/registrar/index.js')?>"></script>
<?= $this->endSection() ?>