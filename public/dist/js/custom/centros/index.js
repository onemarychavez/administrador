const table = document.getElementById('tabla')
const modal = $('#modal')
const nombres = document.getElementById('nombres')
const detalle= document.getElementById('dispositivo')
const existencia = document.getElementById('cantidad')

const estado = document.getElementById('asignado')
const btncreate = document.getElementById('btncreate')
const btnupdate = document.getElementById('btnupdate')
let idcentro= 0

const listarInventario = async ()=>{
    try {
        let url = `/centro/inventario`
        const request = await fetch(url)
        if(request.ok){
            let data = await request.json()
            let html =''
            data.forEach(element => {
                html+=`<option value="${element.id_dispositivo}">${element.dispositivo}</option>`
            });
            invemtario.innerHTML=html
        }else{
            console.log('erroro al obtener usuario')
        }
    } catch (error) {
        console.log(error)
    }
}
const listarestado = async ()=>{
    try {
        let url = `/centro/usuario`
        const request = await fetch(url)
        if(request.ok){
            let data = await request.json()
            let html =''
            data.forEach(element => {
                html+=`<option value="${element.id_usuario}">${element.username}</option>`
            });
            usuario.innerHTML=html
        }else{
            console.log('error al obtener usuario')
        }
    } catch (error) {
        console.log(error)
    }
}

const clean =()=>{
    nombres.value = null 
    dispositivo.value = null
    cantidad.value=null
    asignado.value=null
    idcentro=0
}

const Nuevo = ()=>{
   clean()
   btncreate.style.display=''
   btnupdate.style.display='none'
    modal.modal('show')
}



const create = async()=>{
    try {
        const request = await fetch('/centro',{
            method:'POST',
            body:JSON.stringify({
                nombres:nombres.value.trim(),
                dispositivo:dispositivo.value,
                cantidad:cantidad.value,
                asignado:asignado.value
            })
        })

        if(request.ok){
            listarCentros()
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
        let url = `/centro/${idcentro}`
        const request = await fetch(url,{
            method:'PUT',
            body:JSON.stringify({
                nombres:nombres.value.trim(),
                dispositivo:dispositivo.value,
                cantidad:cantidad.value,
                asignado:asignado.value
            })
        })

        if(request.ok){
            listarCentros()
            modal.modal('hide')
            clean()
           
        }else{
            console.error('error al crear el usuario')
        }
    } catch (error) {
        console.error(error)
    }
}



const detalleCentro = async (keycentro)=>{
    try {
        let url = `/centro/${keycentro}`
        const request = await fetch(url)
        if(request.ok){
            let data = await request.json()
            idcentro= keycentro
            nombres.value = data.nombre_centro 
            dispositivo.value = data.id_dispositivo
            cantidad.value=data.cantidad
            asignado.value=data.id_usuario
            btncreate.style.display='none'
            btnupdate.style.display=''
            modal.modal('show')
        }else{
            console.log('erroro al obtener el centro')
        }
    } catch (error) {
        console.log(error)
    }
}

const eliminar = async (idcentro) => {
    try {
        let url = `/centro/${idcentro}`
        const request = await fetch(url,{
            method:'DELETE'
        })
        if(request.ok){
            listarCentros()
        }else{
            console.log('error al ELIMINAR el centro')
        }
    } catch (error) {
        console.log(error)
    }
}





const listarCentros =  async ()=>{
    try {
        
        const request = await fetch('/centro/list')

        if(request.ok){
            let datos = await request.json()
            let html =''
            datos.forEach((e,i)=> {
                html+=`<tr>
                <td>${i+1}</td>
                <td>${e.nombre_centro}</td>
                <td>${e.id_dispositivo}</td>
                <td>${e.cantidad}</td>
                <td>${e.id_usuario}</td>
                <td><div class="btn-group"><button type="button" class="btn btn-warning"
                onClick="detalleInventario(${e.id_centro})"
                >editar</button><button type="button" class="btn btn-danger ml-2"
                onClick="eliminar(${e.id_centro})"
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