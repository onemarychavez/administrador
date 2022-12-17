
<?= $this->extend('base/index') ?>


<?= $this->section('body') ?>
    <div class="row">
        <div class="col-4 col-xs-12">
            <div class="card shadow">
                
                <div class="card-body text-center">
                    <h5 class="card-title">Pedidos Nuevo</h5>
                    <br>
                    <h3 id="p1">0</h3>
                </div>
            </div>
        </div>
        <div class="col-4 col-xs-12">
            <div class="card shadow">
                
                <div class="card-body text-center">
                    <h5 class="card-title">Pedidos Procesados</h5>
                    <br>
                    <h3 id="p2">0</h3>
                </div>
            </div>
        </div>
        <div class="col-4 col-xs-12">
            <div class="card shadow">
               
                <div class="card-body text-center">
                    <h5 class="card-title">Pedidos Completados</h5>
                    <br>
                    <h3 id="p3">0</h3>
                </div>
            </div>
        </div>
    </div>
    
<?= $this->endSection() ?>


<?= $this->section('js') ?>
<script>

const getStadisticas = async()=>{
    try {
        const request = await fetch('/home/estadistica');
        if(request.ok){
            
            const data = await request.json();
            console.log(data);
            document.getElementById('p1').innerText = data[0].nuevo;
            document.getElementById('p2').innerText = data[0].procesado;
            document.getElementById('p3').innerText = data[0].finalizado;
        }
    } catch (error) {
        console.log(error);
    }
}

document.addEventListener("DOMContentLoaded", (event)=> {
    getStadisticas();
});
</script>
    
<?= $this->endSection() ?>
