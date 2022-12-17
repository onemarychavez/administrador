const tabla = document.getElementById('tabla')
const btnNew = document.getElementById('btnNew')
const btnCreate = document.getElementById('btnCreate')
const btnUpdate = document.getElementById('btnUpdate')
const empresa = document.getElementById('empresa')
const empresam= document.getElementById('empresas')
const modal = $('#modal')
const modal2 = $('#modal2')
const nombre = document.getElementById('nombre')
const departamento = document.getElementById('departamentos')
const municipio = document.getElementById('municipios')
const direccion = document.getElementById('direccion')
const encargado = document.getElementById('encargado')
const telefono = document.getElementById('telefono')
const btnDelete = document.getElementById('btnDelete')
let keysucursal=null

const clearForm = ()=>{
    nombre.value = null
    direccion.value = null
    encargado.value=null
    telefono.value = null
    empresa.selectedIndex = "0"
    departamento.selectedIndex = "0"
    municipio.selectedIndex = "0"
    keysucursal=null
} 

const validateForm = ()=>{
    let ok = true
    const campos= ['empresa','nombre','departamento','municipio','direccion','telefono','encargado'];
    campos.forEach(e=>{
        let input = document.getElementById(e) 
        console.log(input)
        if(input.value.trim().length >0){
            input.classList.remove("is-invalid")
        }else{
            input.classList.add("is-invalid")
            ok=false
        }
    })
    return ok
}


const listEmpresas = async ()=>{
    try {
        const request = await fetch('/empresa/list')
        const data = await request.json()
        if(request.ok){
            let html = ''
            data.forEach((e,i)=>{
                html+=`<option value="${e.key}">${e.nombre}</option>`
            })
            empresa.innerHTML = `<option value="0">Todas las Sucursales</option>${html}`
            empresam.innerHTML = html
        }else{
            toastr.error(data.message)
        }

    } catch (error) {
        toastr.error(error)
    }
}
const listDepa = async ()=>{
    try {
        const request = await fetch('/departamento/list')
        const data = await request.json()
        if(request.ok){
            let html = ''
            data.forEach((e,i)=>{
                html+=`<option value="${e.key}">${e.nombre}</option>`
            })
            
            departamento.innerHTML = html
        }else{
            toastr.error(data.message)
        }

    } catch (error) {
        toastr.error(error)
    }
}

const municipios = [];
const listMuni = async ()=>{
    try {
        const request = await fetch('/municipio/list')
        const data = await request.json()
        if(request.ok){
            let html = ''
            const keydepartamento = parseInt(departamento.value);
            data.forEach((e,i)=>{
                municipios.push(e);
                if(e.keyDepartamento === keydepartamento){
                    html+=`<option value="${e.key}">${e.nombre}</option>`
                }
                
            })
            
            municipio.innerHTML = html
        }else{
            toastr.error(data.message)
        }

    } catch (error) {
        toastr.error(error)
    }
}

const cambiarMunicipios = (keydepartamento)=>{
    const filtro = municipios.filter(m => m.keyDepartamento === keydepartamento);
    let html = '';
    filtro.forEach((e,i)=>{
        html+=`<option value="${e.key}">${e.nombre}</option>`
    });
    municipio.innerHTML = html;

}


const listSucursales = async ($url)=>{
    try {
        const request = await fetch($url)
        let html =''
        const data = await request.json()
        if(request.ok){
            data.forEach((r,i)=>{
                html += `<tr>
                    <td>${i+1}</td>
                    <td>${r.nombre}</td>
                    <td>${r.empresa}</td>
                    <td><button type="button" class="btn btn-warning" onClick="show(${r.key})" >Editar</button>
                    </tr>`
            })
        }else{
            toastr.info(data.message)
        }   
        tabla.innerHTML=html
    } catch (error) {
        toastr.error(error)
    }
}

const showModal = (id)=>{
    keysucursal = id;
    modal2.modal('show')
}

const createSucursal = async()=>{
    try {
        if(!validateForm()){
            toastr.error('Debes completar los campos obligatorios')
            return
        }
        const data = {
            nombre:nombre.value.trim().toUpperCase(),
            empresa:parseInt(empresam.value),
            direccion:direccion.value.trim().toUpperCase(),
            telefono:telefono.value.trim().toUpperCase(),
            encargado:encargado.value.trim().toUpperCase(),
            gps:gps.value.trim()
        }  
        const request = await fetch('/sucursal',{
            method:'POST',
            body:JSON.stringify(data)
        }) 
        const msn = await request.json()
        if(request.ok){
            if(parseInt(empresa.value)>0){
                listSucursales(`/sucursal/empresa/${parseInt(empresa.value)}`)
            }else{
                listSucursales('/sucursal/list')
            }
            if(parseInt(departamento.value)>0){
                listSucursales(`/sucursal/departamento/${parseInt(departamento.value)}`)
            }else{
                listSucursales('/sucursal/list')
            }
            if(parseInt(municipio.value)>0){
                listSucursales(`/sucursal/municipio/${parseInt(municipio.value)}`)
            }else{
                listSucursales('/sucursal/list')
            }
            modal.modal('hide')
            clearForm()
        }else{
            toastr.error(msn.message)
        }    
    } catch (error) {
        toastr.error(error)
    }
}

const show = async (id)=>{
    try {
       
        const request = await fetch(`sucursal/${id}`) 
        const msn = await request.json()
        if(request.ok){
           keysucursal=msn.key 
           nombre.value = msn.nombre
           empresam.value = msn.keyempresa
           direccion.value = msn.direccion
           encargado.value = msn.encargado
           departamento.value = msn.keydepartamento
           municipio.value = msn.keymunicipio 
           telefono.value = msn.telefono 
           btnCreate.style.display='none'
            btnUpdate.style.display=''
            modal.modal('show')
        }else{
            toastr.error(msn.message)
        }    
    } catch (error) {
        toastr.error(error)
    }
}

const deleteSucursal = async()=>{
    try {

        if(keysucursal == null){
            return 
        }
        const request = await fetch(`/sucursal/${keysucursal}`,{
            method:'DELETE'
        })
        if(request.ok){
            keysucursal=null 
            if(parseInt(empresa.value)>0){
                listSucursales(`/sucursal/empresa/${parseInt(empresa.value)}`)
            }else{
                listSucursales('/sucursal/list')
            }
            if(parseInt(departamento.value)>0){
                listSucursales(`/sucursal/departamento/${parseInt(departamento.value)}`)
            }else{
                listSucursales('/sucursal/list')
            }
            if(parseInt(municipio.value)>0){
                listSucursales(`/sucursal/municipio/${parseInt(municipio.value)}`)
            }else{
                listSucursales('/sucursal/list')
            }
            modal2.modal('hide')
        }else{
            let msn = await request.json()
            toastr.error(msn.message)
        }

    } catch (error) {
        toastr.error(error)
    }
}

const updateSucursal = async ()=>{
    try {
        if(!validateForm()){
            toastr.error('Debes completar los campos obligatorios')
            return
        }
        const data = {
            nombre:nombre.value.trim().toUpperCase(),
            empresa:parseInt(empresam.value),
            direccion:direccion.value.trim().toUpperCase(),
            telefono:telefono.value.trim().toUpperCase(),
            encargado:encargado.value.trim().toUpperCase()
        }  
        const request = await fetch(`/sucursal/${keysucursal}`,{
            method:'PUT',
            body:JSON.stringify(data)
        }) 
        const msn = await request.json()
        if(request.ok){
            keysucursal=null
            if(parseInt(empresa.value)>0){
                listSucursales(`/sucursal/empresa/${parseInt(empresa.value)}`)
            }else{
                listSucursales('/sucursal/list')
            }
            if(parseInt(departamento.value)>0){
                listSucursales(`/sucursal/departamento/${parseInt(departamento.value)}`)
            }else{
                listSucursales('/sucursal/list')
            }
            if(parseInt(municipio.value)>0){
                listSucursales(`/sucursal/municipio/${parseInt(municipio.value)}`)
            }else{
                listSucursales('/sucursal/list')
            }
            modal.modal('hide')
            clearForm()
        }else{
            toastr.error(msn.message)
        }    
    } catch (error) {
        toastr.error(error)
    }
}

document.addEventListener("DOMContentLoaded", (event)=> {
    listEmpresas()
    listDepa()
    listMuni()
    listSucursales('/sucursal/list')
    btnNew.onclick = (e)=>{
        e.preventDefault()
        clearForm()
        btnCreate.style.display=''
        btnUpdate.style.display='none'
        modal.modal('show')
    }

    empresa.onchange = (e)=>{
        const keyempresa = parseInt(e.target.value)
        if(keyempresa > 0){
            listSucursales(`/sucursal/empresa/${keyempresa}`)
        }else{
            listSucursales('/sucursal/list')
        }
       
    }
    departamento.onchange = (e)=>{
        const keydepartamento = parseInt(e.target.value)
        cambiarMunicipios(keydepartamento);
        /*if(keydepartamento>0){
            listSucursales(`/sucursal/departamento/${keydepartamento}`)
        }else{
            listSucursales('/sucursal/list')
        }*/
       
    }
    /*municipio.onchange = (e)=>{
        const keymunicipio = parseInt(e.target.value)
      
        if(keymunicipio>0){
            listSucursales(`/sucursal/municipio/${keymunicipio}`)
        }else{
            listSucursales('/sucursal/list')
        }
    }*/

    btnCreate.onclick = (e)=>{
        e.preventDefault();
        createSucursal()
    }

    btnDelete.onclick = (e)=>{
        e.preventDefault()
        deleteSucursal()
    }
    
    btnUpdate.onclick = (e)=>{
        e.preventDefault()
        updateSucursal()
    }
});