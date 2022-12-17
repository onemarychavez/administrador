const table = document.getElementById('tabla')
const modal = $('#modal')
const nombres = document.getElementById('nombres')
const detalle= document.getElementById('detalle')
const existencia = document.getElementById('existencia')

const estado = document.getElementById('estado')
const btncreate = document.getElementById('btncreate')
const btnupdate = document.getElementById('btnupdate')
let idinventario= 0

const listarestado = async ()=>{
    try {
        let url = `/inventario/estado`
        const request = await fetch(url)
        if(request.ok){
            let data = await request.json()
            let html =''
            data.forEach(element => {
                html+=`<option value="${element.id_estado_dispositivo}">${element.estado_dispositivo}</option>`
            });
            estado.innerHTML=html
        }else{
            console.log('erroro al obtener usuario')
        }
    } catch (error) {
        console.log(error)
    }
}

const clean =()=>{
    nombres.value = null 
    detalle.value = null
    existencia.value=null
    estado.selectedIndex= 0
    idinventario=0
}

const Nuevo = ()=>{
   clean()
   btncreate.style.display=''
   btnupdate.style.display='none'
    modal.modal('show')
}



const create = async()=>{
    try {
        const request = await fetch('/inventario',{
            method:'POST',
            body:JSON.stringify({
                nombres:nombres.value.trim(),
                detalle:detalle.value.trim(),
                existencia:existencia.value,
                estado:estado.value
            })
        })

        if(request.ok){
            listarInventarios()
            modal.modal('hide')
            clean()
           
        }else{
            console.error('error al crear el usuario')
        }
    } catch (error) {
        console.error(error)
    }
}

const update = async ()=>{
    try {
        let url = `/inventario/${idinventario}`
        const request = await fetch(url,{
            method:'PUT',
            body:JSON.stringify({
                nombres:nombres.value.trim(),
                detalle:detalle.value.trim(),
                existencia:existencia.value,
                estado:estado.value
            })
        })

        if(request.ok){
            listarInventarios()
            modal.modal('hide')
            clean()
           
        }else{
            console.error('error al crear el usuario')
        }
    } catch (error) {
        console.error(error)
    }
}



const detalleInventario = async (keyinventario)=>{
    try {
        let url = `/inventario/${keyinventario}`
        const request = await fetch(url)
        if(request.ok){
            let data = await request.json()
            idinventario= keyinventario
            nombres.value = data.dispositivo 
            detalle.value = data.detalles
            existencia.value=data.existencias
            estado.value=data.id_estado_dispositivo
            btncreate.style.display='none'
            btnupdate.style.display=''
            modal.modal('show')
        }else{
            console.log('erroro al obtener usuario')
        }
    } catch (error) {
        console.log(error)
    }
}

const eliminar = async (idinventario) => {
    try {
        let url = `/inventario/${idinventario}`
        const request = await fetch(url,{
            method:'DELETE'
        })
        if(request.ok){
            listarInventarios()
        }else{
            console.log('erroro al ELIMINAR el producto')
        }
    } catch (error) {
        console.log(error)
    }
}





const listarInventarios =  async ()=>{
    try {
        
        const request = await fetch('/inventario/list')

        if(request.ok){
            let datos = await request.json()
            let html =''
            datos.forEach((e,i)=> {
                html+=`<tr>
                <td>${i+1}</td>
                <td>${e.dispositivo}</td>
                <td>${e.detalles}</td>
                <td>${e.existencias}</td>
                <td><div class="btn-group"><button type="button" class="btn btn-warning"
                onClick="detalleInventario(${e.id_dispositivo})"
                >editar</button><button type="button" class="btn btn-danger ml-2"
                onClick="eliminar(${e.id_dispositivo})"
                >eliminar</button></div></td>
                </tr>`
            });

            table.innerHTML = html

        }else{
            console.log('erroro al obtener')
        }


    } catch (error) {
        console.error(error)
    }
}










document.addEventListener("DOMContentLoaded", (event)=> {
    listarestado()
   listarInventarios()
});