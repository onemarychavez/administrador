const tabla = document.getElementById('tabla')
const modal = $('#modal')
const nombres = document.getElementById('nombre')
const apellidos = document.getElementById('apellido')
const telefono = document.getElementById('telefono')
const btnNew = document.getElementById('btnNew')
const btnUpdate = document.getElementById('btnUpdate')
let repartidor = null


const showCodigo = (codigo)=>{
    document.getElementById('codediv').innerHTML = `<div class="row">
        <div class="col-2">
            <h4>${codigo}</h4>
        </div>
    </div>`
}

const getRepartidor = async (id)=>{
    try{
        const request = await fetch(`/repartidor/${id}`)
        if(request.ok){
            const data = await request.json()
            repartidor = data 
            nombres.value = repartidor.nombre
            apellidos.value = repartidor.apellidos
            telefono.value= repartidor.telefono
            showCodigo(repartidor.codigo)
            btnNew.style.display = 'none'
            btnUpdate.style.display = ''
            modal.modal('show')
        }
    }catch(error){
        console.error(error)
    }
}


const getRepartidores = async () => {
    try{
        const request = await fetch('/repartidor/list')
        if(request.ok){
            const data = await request.json()

            let html = ''
            data.forEach((d,i)=>{
                html += `<tr><td>${(i+1)}</td>
                <td>${d.codigo}</td>
                <td>${d.nombre+' '+d.apellidos}</td>
                <td>${d.telefono}</td>
                <td>
                <button type="button" class="btn btn-warning" onClick="getRepartidor(${d.key})" >Editar</button>
                <button type="button" class="btn btn-danger ml-2" onClick="deleteRepartidor(${d.key})" >Eliminar</button></td>
                </tr>`
            })
            tabla.innerHTML = html
        }else{
            tabla.innerHTML= null
        }
       
    }catch(error){
        console.error(error)
    }
} 

const deleteRepartidor = async (key)=>{
    try{
       const request  = await fetch(`/repartidor/${key}`,{
        method:'DELETE'
       }) 
       if(request.ok){
        getRepartidores()
       }
    } catch (error) {
        console.error(error)
    }
}

const createRepartidor = async ()=>{
    try {
        const request = await fetch('/repartidor',{
            method:'POST',
            body: JSON.stringify({
                nombre: nombres.value.trim().toUpperCase(),
                apellido:apellidos.value.trim().toUpperCase(),
                telefono:telefono.value.trim()
            })
        })

        if(request.ok){
            getRepartidores()
            hideModel()
        }

    } catch (error) {
        console.error()
    }
}

const updateRepartidor = async ()=>{
    try {
        const request = await fetch('/repartidor',{
            method:'PUT',
            body: JSON.stringify({
                id:repartidor.key,
                nombre: nombres.value.trim().toUpperCase(),
                apellido:apellidos.value.trim().toUpperCase(),
                telefono:telefono.value.trim()
            })
        })

        if(request.ok){
            getRepartidores()
            hideModel()
        }

    } catch (error) {
        console.error()
    }
}

const hideModel=()=>{
    repartidor= null
    nombres.value=null
    apellidos.value = null
    telefono.value = null
    document.getElementById('codediv').innerHTML=''
    modal.modal('hide')
}

const showModal=()=>{
    nombres.value=null
    apellidos.value = null
    telefono.value = null
    document.getElementById('codediv').innerHTML=''
    btnNew.style.display = ''
    btnUpdate.style.display = 'none'
    modal.modal('show')
}

document.addEventListener("DOMContentLoaded", (event)=> {
    getRepartidores()
    btnNew.onclick = (e)=>{
        e.preventDefault()
        createRepartidor()
    }

    btnUpdate.onclick = (e)=>{
        e.preventDefault()
        updateRepartidor()
    }
});