const table = document.getElementById('tabla')
const modal = $('#modal')
const nombres = document.getElementById('nombres')
const apellidos = document.getElementById('apellidos')
const usuario = document.getElementById('usuario')
const clave = document.getElementById('clave')
const rol = document.getElementById('rol')
const btncreate = document.getElementById('btncreate')
const btnupdate = document.getElementById('btnupdate')
let idusuario= 0

const listarRoles = async ()=>{
    try {
        let url = `/usuario/rol`
        const request = await fetch(url)
        if(request.ok){
            let data = await request.json()
            let html =''
            data.forEach(element => {
                html+=`<option value="${element.keyrol}">${element.rol}</option>`
            });
            rol.innerHTML=html
        }else{
            console.log('erroro al obtener usuario')
        }
    } catch (error) {
        console.log(error)
    }
}

const clean =()=>{
    nombres.value = null 
    apellidos.value = null
    usuario.value=null
    clave.value = null
    rol.selectedIndex= 0
    idusuario=0
}

const Nuevo = ()=>{
   clean()
   btncreate.style.display=''
   btnupdate.style.display='none'
    modal.modal('show')
}



const create = async()=>{
    try {
        const request = await fetch('/usuario',{
            method:'POST',
            body:JSON.stringify({
                nombres:nombres.value.trim(),
                apellidos:apellidos.value.trim(),
                password:clave.value,
                username:usuario.value.trim(),
                rol:rol.value
            })
        })

        if(request.ok){
            listarUsuarios()
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
        let url = `/usuario/${idusuario}`
        const request = await fetch(url,{
            method:'PUT',
            body:JSON.stringify({
                nombres:nombres.value.trim(),
                apellidos:apellidos.value.trim(),
                password:clave.value,
                username:usuario.value.trim(),
                rol:rol.value
            })
        })

        if(request.ok){
            listarUsuarios()
            modal.modal('hide')
            clean()
           
        }else{
            console.error('error al crear el usuario')
        }
    } catch (error) {
        console.error(error)
    }
}



const detalleUsuario = async (keyusuario)=>{
    try {
        let url = `/usuario/${keyusuario}`
        const request = await fetch(url)
        if(request.ok){
            let data = await request.json()
            idusuario= keyusuario
            nombres.value = data.nombres 
            apellidos.value = data.apellidos 
            usuario.value=data.usuario
            clave.value = data.clave
            rol.value=data.keyrol 
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

const eliminar = async (idusuario) => {
    try {
        let url = `/usuario/${idusuario}`
        const request = await fetch(url,{
            method:'DELETE'
        })
        if(request.ok){
            listarUsuarios()
        }else{
            console.log('erroro al ELIMINAR usuario')
        }
    } catch (error) {
        console.log(error)
    }
}





const listarUsuarios =  async ()=>{
    try {
        
        const request = await fetch('/usuario/list')

        if(request.ok){
            let datos = await request.json()
            let html =''
            datos.forEach((e,i)=> {
                html+=`<tr>
                <td>${i+1}</td>
                <td>${e.nombres+' '+e.apellidos}</td>
                <td>${e.usuario}</td>
                <td>${e.rol}</td>
                <td><div class="btn-group"><button type="button" class="btn btn-warning"
                onClick="detalleUsuario(${e.key})"
                >editar</button><button type="button" class="btn btn-danger ml-2"
                onClick="eliminar(${e.key})"
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
    listarRoles()
   listarUsuarios()
});