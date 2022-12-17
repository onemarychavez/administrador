const empresa = document.getElementById('empresa')
const categoria = document.getElementById('categoria')
const btnNew = document.getElementById('btnNew')
const modal = $('#modal')
const alerta = $('#modal2')
const btnCreate = document.getElementById('btnCreate')
const btnUpdate = document.getElementById('btnUpdate')
const empresamodal = document.getElementById('empresas')
const categoriamodal = document.getElementById('categoriam')
const nombreMenu = document.getElementById('nombremenu')
const modalProducto = $('#modalProducto')
const btnAddProducto = document.getElementById('btnaddproducto')
const tablaMenu = document.getElementById('tblmenu')
const platilloNombre = document.getElementById('nombreproducto')
const platilloDescripcion = document.getElementById('descripcionproducto')
const platilloPrecio = document.getElementById('precioproducto')
const tabla = document.getElementById('tabla')
const extraNombre = document.getElementById('extranombre')
const extraPrecio = document.getElementById('extraprecio')
const btnAddExtra = document.getElementById('extrAdd')
const tablaExtra = document.getElementById('tablaextras')
const btnImagen = document.getElementById('btnimagen')
const btnAddPlatillo = document.getElementById('btnAddPlatillo')
const img = document.getElementById('img')
let categorias = null
let empresas =null
let menu = {
    nombre:'',
    platillos:[]
}
let platillo = {
   nombre:'',
   descripcion:'',
   precio:0.00,
   imagen:'',
   imagen_name:'',
   extras:[] 
}

toastr.options.closeMethod = 'fadeOut'
toastr.options.closeDuration = 4000;
toastr.options.closeEasing = 'swing'

 const cleanForm = ()=>{
    platillo = {
        empresa:0,
        categoria:0, 
        nombre:'',
        descripcion:'',
        precio:0.00,
        imagen:'',
        extras:[] 
    }
 }

const cleanPlatilloForm = ()=>{
    cleanForm()
    platilloNombre.value = null 
    platilloDescripcion.value = null 
    platilloPrecio.value = null
    img.src = ''
    btnImagen.value = null 
    extraNombre.value = null
    extraPrecio.value = null 
    tablaExtra.innerHTML = ''
}

const validateFormPlatillo = ()=>{
    let ok = true;
    const campos = [
       platilloNombre,
       platilloDescripcion,
       platilloPrecio
    ]
    campos.forEach(item => {
        if(item.value.trim().length<=0){
            ok = false 
        }
    });
    if(Number(platilloPrecio.value)<=0){
        ok = false
    }

    const [file] = btnImagen.files
    if(!file){
       ok = false
    }
    return ok
}


const validateFormExtras = ()=>{
    let respuesta = true
    const campos =['extranombre','extraprecio']
    campos.forEach(c=>{
        let input = document.getElementById(c)
        if(input.value.trim().length <=0){
            respuesta = false
        }
    })
    if(Number(document.getElementById('extraprecio').value)<0){
        respuesta= false
    }
    return respuesta 
}


const listEmpresas = async ()=>{
    try {
        const request = await fetch('/empresa/list')
        const data = await request.json()
        empresas = data
        if(request.ok){
            let html = ''
            data.forEach((e,i)=>{
                html+=`<option value="${e.key}">${e.nombre}</option>`
            })
            
            empresamodal.innerHTML = html
        }else{
            toastr.error(data.message)
        }

    } catch (error) {
        toastr.error(error)
    }
}

const getCategorias = async ()=>{
    try {

        const request = await fetch('/categoria')
        if(request.ok){
            categorias = await request.json()
            let html =''
            categorias.forEach(e => {
                html+=`<option value="${e.key}">${e.nombre}</option>`
            });
           
            categoriamodal.innerHTML = html
        }
        
    } catch (error) {
        toastr.error(error.message)
    }
}

const armarExtras = ()=>{
    let html=''
    platillo.extras.forEach((ext,i)=>{
        html+=`<tr>
        <td>${i+1}</td>
        <td>${ext.nombre}</td>
        <td>$${ext.precio}</td>
        <td><button type="button" class="btn btn-sm btn-danger" onClick="Quitar('ext.nombre')">x</button></td>
        </tr>`
    })
    tablaExtra.innerHTML = html
}

const toBase64 = file => new Promise((resolve,reject)=>{
    const reader = new FileReader()
    reader.readAsDataURL(file)
    reader.onload = ()=>resolve(reader.result)
    reader.onerror = error => reject(error)
}) //transforma la imagen a base64 para guardar 

const extrasAdd = ()=>{
    if(!validateFormExtras()){
        return 
    }
    const ext = {
        nombre:extraNombre.value.trim().toUpperCase(),
        precio:Number(extraPrecio.value)
    } 

    let existe = platillo.extras.filter(extra=>extra.nombre === ext.nombre)
    if(existe.length >0){
        return 
    }
    extraNombre.value = null 
    extraPrecio.value = null
    platillo.extras = [...platillo.extras,ext]
    armarExtras()
}

const listPlatillos = () =>{
    let html = '' 
    menu.platillos.forEach((p,i)=>{
        html+=`<tr>
            <td>${i+1}</td>
            <td>${p.nombre}</td>
            <td>$${p.precio}</td>
            <td><button type="button" class="btn bt-sm btn-danger">x</button></td>
        </tr>`
    })
    tablaMenu.innerHTML = html
}

const agregarPlatillo = async ()=>{
    if(!validateFormPlatillo()){
        return
    }
    let files = btnImagen.files[0]
    let img = await toBase64(files)
    platillo.nombre = platilloNombre.value.trim().toUpperCase()
    platillo.descripcion = platilloDescripcion.value.trim().toUpperCase()
    platillo.precio = Number(platilloPrecio.value)
    platillo.imagen = img.split("base64,")[1] 
    platillo.imagen_name = files.name.trim()
    //agregar el platillo 

    menu.platillos = [...menu.platillos,platillo]
    listPlatillos()
    modalProducto.modal('hide')
    cleanPlatilloForm()
}


const listMenu = async (url)=>{
    try {
        const request = await fetch(url)
        const data = await request.json()
        if(request.ok){
            let html = ''
            data.forEach((d,i)=>{
                
                html+=`<tr>
                    <td>${i+1}</td>
                    <td>${d.empresa}</td>
                    <td>${d.nombre}</td>
                    <td>${d.categoria}</td>
                     </tr>`
            })
            tabla.innerHTML = html
        }else{
            if(request.status !==404){
                toastr.error(data.message)
            }
        }
    } catch (error) {
        toastr.error(error)
    }
}

const createMenu = async ()=>{
    try {
        menu.nombre = nombreMenu.value.trim().toUpperCase()
        menu.categoria = parseInt(categoriamodal.value)
        menu.empresa = parseInt(empresamodal.value)
       const request = await fetch('/menu',{
        method:'POST',
        body:JSON.stringify(menu)
       })

       const data = await request.json()
       if(request.ok){
            listMenu('/menu/list')
            modal.modal('hide')
            cleanPlatilloForm()
            toastr.success("Menu Creado")
       }else{
            toastr.error(data.message)
       }
    } catch (error) {
        console.log(error)
        toastr.error(error)
    }
}

document.addEventListener("DOMContentLoaded", (event)=> {
    listEmpresas()
    listMenu('/menu/list')
    getCategorias()
    btnNew.onclick =(e)=>{
        e.preventDefault()
        btnCreate.style.display =''
        btnUpdate.style.display = 'none'
        modal.modal('show')
    }

    btnAddProducto.onclick = (e)=>{
        e.preventDefault()
        modalProducto.modal('show')
    }

    btnImagen.onchange = e =>{
        const [file] = btnImagen.files
        if(file){
            img.src = URL.createObjectURL(file)
        }
    }

    btnAddExtra.onclick = e =>{
        e.preventDefault()
        extrasAdd()
    }

    btnAddPlatillo.onclick = e =>{
        e.preventDefault()
        agregarPlatillo()
    }

    btnCreate.onclick = (e)=>{
        e.preventDefault()
        createMenu()
    }
});