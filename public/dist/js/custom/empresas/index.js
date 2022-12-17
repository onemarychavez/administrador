const modal = $('#modal')
const modalaler = $('#modal2')
const nombre = document.getElementById('nombre')
const representante = document.getElementById('representante')
const razon = document.getElementById('razon')
const giro = document.getElementById('giro')
const nrc = document.getElementById('nrc')
const telefono = document.getElementById('telefono')
const imglogo = document.getElementById('logo')
const btnlogo = document.getElementById('logobtn')
const tabla = document.getElementById('tabla')
const btnew = document.getElementById('btnNew')
const categoria = document.getElementById('categoria')
const btnCreate = document.getElementById('btnCreate')
const btnUpdate = document.getElementById('btnUpdate')
const btndelete = document.getElementById('btnDelete')
toastr.options.closeMethod = 'fadeOut';
toastr.options.closeDuration = 4000;
toastr.options.closeEasing = 'swing';
let idempresa =null 

const clearModal = ()=>{
    nombre.value = null
    representante.value = null
    razon.value = null
    giro.value = null
    nrc.value = null
    telefono.value = null 
    imglogo.src= null 
    btnlogo.value = null 
    idempresa=null;
    btnCreate.style.display = ''
    btnUpdate.style.display='none'
}

const camposObligatorios = ()=>{
    const campos = ['nombre','representante','razon','giro','nrc','telefono']
    let ok = true
    campos.forEach(element => {
        let campo = document.getElementById(element)
        if(campo.value.trim().length>0){
            campo.classList.remove("is-invalid")
        }else{
            ok = false
            campo.classList.add("is-invalid")
        }                       
    });
    return ok
}

let categorias = []

const getCategorias = async ()=>{
    try {

        const request = await fetch('/categoria')
        if(request.ok){
            categorias = await request.json()
            let html =''
            categorias.forEach(e => {
                html+=`<option value="${e.key}">${e.nombre}</option>`
            });
            categoria.innerHTML = html
        }
        
    } catch (error) {
        toastr.error(error.message)
    }
}

const toBase64 = file => new Promise((resolve,reject)=>{
    const reader = new FileReader()
    reader.readAsDataURL(file)
    reader.onload = ()=>resolve(reader.result)
    reader.onerror = error => reject(error)
}) //transforma la imagen a base64 para guardar 


const crearEmpresa = async ()=>{
    try {
        if(!camposObligatorios()){
            toastr.error('Debes completar los campos obligatorios')
            return
        }
        let  datos = {
            nombre:nombre.value.trim(),
            razon:razon.value.trim(),
            representante:representante.value.trim(),
            telefono:telefono.value.trim(),
            nrc:nrc.value.trim(),
            giro:giro.value.trim(),
            logo:'',
            categorias:[...categoria.options].filter(option=>option.selected).map(option=>option.value),
        }

        if(btnlogo.files[0]!=null){
            let files = btnlogo.files[0]
            let img = await toBase64(files)
            datos.logo = img.split("base64,")[1],
            datos.img_name = files.name
        }
        
        const request = await fetch('/empresa',{
            method:'POST',
            body:JSON.stringify(datos)
        })
        if(request.ok){
            listEmpresas()
            toastr.success('Empresa Guardada')
            modal.modal('hide')
        }else{
            const error = await request.json()
            toastr.error(error.message)
        }

    } catch (error) {
        console.log(error)
        toastr.error(error)
    }
}

const listEmpresas = async ()=>{
    try {
        const request = await fetch('/empresa/list')
        const data = await request.json()
        if(request.ok){
            let html = ''
            data.forEach((e,i)=>{
                html+=`<tr>
                <td>${(i+1)}</td>
                <td>${e.nombre}</td>
                <td>${e.nrc}</td>
                <td><button type="button" onClick="getEmpresa(${e.key})" class="btn btn-warning" >editar</button>
                </tr>`
            })
            tabla.innerHTML = html
        }else{
            toastr.error(data.message)
        }

    } catch (error) {
        toastr.error(error)
    }
}

const getEmpresa = async (id)=>{
    try {
        idempresa = id 
        if(id== null){
            return 
        }
        const request = await fetch(`/empresa/${id}`)
        const data = await request.json()
        if(request.ok){
            nombre.value = data.nombre
            representante.value = data.representante
            razon.value = data.razon
            telefono.value = data.telefono
            giro.value = data.giro
            nrc.value = data.nrc
            if(data.logo.trim().length >0){
                imglogo.src = data.logo.trim()
            }
            btnCreate.style.display = 'none'
            btnUpdate.style.display=''
            console.log(data.categoria)
            if(data.categoria.length > 0){
                for(option of categoria.options){
                    console.log(option.value)
                    let res = data.categoria.filter(c=>c==option.value)

                    if(res.length > 0){
                        option.selected = true
                    }
                } 
            }
            modal.modal('show')
        }else{
            toastr.error(data.message)
        }
    } catch (error) {
        toastr.error(error)
    }
}

const Delete = (id)=>{
    idempresa=id 

    modalaler.modal('show')
}

const deleteEmpresa = async ()=>{
    try {
        if(idempresa == null){
            return 
        }
    
        const request = await fetch(`/empresa/${idempresa}`,{
            method:'DELETE'
        })
    
        const data = await request.json()
        modalaler.modal('hide')
        if(request.ok){
            idempresa=null
            listEmpresas()
            toastr.success('empresa eliminada')
        }else{
            toastr.error(data.message)
        }
    } catch (error) {
        toastr.error(error)
    }
}

const updateEmpresa = async ()=>{
    try {
        if(idempresa == null){
            return 
        }
        if(!camposObligatorios()){
            toastr.error('Debes completar los campos obligatorios')
            return
        }
        let  datos = {
            nombre:nombre.value.trim(),
            razon:razon.value.trim(),
            representante:representante.value.trim(),
            telefono:telefono.value.trim(),
            nrc:nrc.value.trim(),
            giro:giro.value.trim(),
            logo:'',
            categorias:[...categoria.options].filter(option=>option.selected).map(option=>option.value),
        }

        if(btnlogo.files[0]!=null){
            let files = btnlogo.files[0]
            let img = await toBase64(files)
            datos.logo = img.split("base64,")[1],
            datos.img_name = files.name
        }
        
        const request = await fetch(`/empresa/${idempresa}`,{
            method:'PUT',
            body:JSON.stringify(datos)
        })
        if(request.ok){
            listEmpresas()
            toastr.success('Empresa Actualizada')
            idempresa=null
            modal.modal('hide')
        }else{
            const error = await request.json()
            toastr.error(error.message)
        }
    } catch (error) {
        toastr.error(error)
    }
}


document.addEventListener("DOMContentLoaded", (event)=> {
    getCategorias()
    listEmpresas()
    btnew.onclick = (e)=>{
        e.preventDefault()
        clearModal()
        modal.modal('show')
    }
    btnlogo.onchange = (e)=>{
        const [file] = btnlogo.files
        if(file){
            imglogo.src = URL.createObjectURL(file)
        }
    }

    if(btnCreate !== null ){
        btnCreate.onclick = (e)=>{
            e.preventDefault()
            crearEmpresa()
        }
    }
    

    btndelete.onclick = (e)=>{
        e.preventDefault()
        deleteEmpresa()
    }

    btnUpdate.onclick = (e)=>{
        e.preventDefault();
        updateEmpresa()
    }
});