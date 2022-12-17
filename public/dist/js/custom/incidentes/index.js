const table = document.getElementById('tabla')
const modal = $('#modal')
const fecha = document.getElementById('fecha')
const centro = document.getElementById('centro')
const equipo = document.getElementById('equipo')
const tipo = document.getElementById('tincidente')
const descripcion = document.getElementById('descripcion')
const btncreate = document.getElementById('btncreate')
const btnupdate = document.getElementById('btnupdate')
const estado = document.getElementById('estado')
const incidenteid = {key:0}
const solucion = document.getElementById('solucion')
const Nuevo = ()=>{
    btncreate.style.display = ''
    btnupdate.style.display= 'none'
    const estadorow = document.getElementById('estadorow')
    estadorow.style.display = 'none'
    clear()
    modal.modal('show')
 }

const clear = ()=>{
    fecha.value = new Date().toISOString().slice(0, 10)
    fecha.disabled = false
    centro.disabled = false 
    equipo.disabled = false 
    tipo.disabled = false 
    estado.disabled = false 
    descripcion.disabled = false
    centro.selectedIndex = 0
    equipo.selectedIndex = 0
    tipo.selectedIndex = 0 
    estado.selectedIndex = 0
    descripcion.value = null 
    incidenteid.key = 0
}


const listarIncidentes = async ()=>{
    try {
        const request = await fetch('/incidente/list')
        
        if(request.ok){
            let data = await request.json()
            let html = ''
            data.forEach((e,i)=> {
                html+=`<tr>
                <td>${i+1}</td>
                <td>${e.fechaCreado}</td>
                <td>${e.dispositivo}</td>
                <td>${e.descripcion}</td>
                <td>${e.estado}</td>
                <td><button type="button" onClick="getInfoIncidente(${e.key})" class="btn btn-warning">editar</button></td>
                </tr>`
            });
            table.innerHTML=html
        }else{
            if(request.status === 404 || request.status === 500){
                let error = await request.json()
                console.error(error)
            }
        }

    } catch (error) {
        console.error(error)
    }
}

const getCentros = async ()=>{
    try {
        const request = await fetch('/centro/list')
        
        if(request.ok){
            let data = await request.json()
            let html = ''
            data.forEach((e,i)=> {
                html+=`<option value="${e.id_centro}">${e.nombre_centro}</option>`
            });
            centro.innerHTML=html
        }else{
            if(request.status === 404 || request.status === 500){
                let error = await request.json()
                console.error(error)
            }
        }
    } catch (error) {
        console.error(error)
    }
}

const getTipos = async() =>{
   
    try {
        const request = await fetch('/incidente/tipo')
        
        if(request.ok){
            let data = await request.json()
            let html = ''
            data.forEach((e,i)=> {
                html+=`<option value="${e.id_tipo}">${e.tipo}</option>`
            });
            tipo.innerHTML=html
        }else{
            if(request.status === 404 || request.status === 500){
                let error = await request.json()
                console.error(error)
            }
        }
    } catch (error) {
        console.error(error)
    }
    
}

const listarInventarios =  async ()=>{
    try {
        
        const request = await fetch('/inventario/list')

        if(request.ok){
            let datos = await request.json()
            let html =''
            datos.forEach((e,i)=> {
                html+=`<option value="${e.id_dispositivo}">${e.dispositivo}</option>`
            });

            equipo.innerHTML = html

        }else{
            console.log('erroro al obtener')
        }


    } catch (error) {
        console.error(error)
    }
} 

const create = async ()=>{
    try {
        const datos = {
            centro: parseInt(centro.value),
            dispositivo: parseInt(equipo.value),
            tipo: parseInt(tipo.value),
            descripcion: descripcion.value.trim()
        }
        const request = await fetch('/incidente',{
            method:'POST',
            body:JSON.stringify(datos)
        })

        if(request.ok){
            let data = await request.json()
            console.log(data)
            listarIncidentes()
            modal.modal('hide')
            clear()
        }else{
            let data = await request.json()
            console.error(data)
        }


    } catch (error) {
        console.error(error)
    }
}

const getEstados = async ()=>{
    try {
        try {
            const request = await fetch('/incidente/estado')
            
            if(request.ok){
                let data = await request.json()
                let html = ''
                data.forEach((e,i)=> {
                    html+=`<option value="${e.id_estado_incidentes}">${e.estado}</option>`
                });
                estado.innerHTML=html
            }else{
                if(request.status === 404 || request.status === 500){
                    let error = await request.json()
                    console.error(error)
                }
            }
        } catch (error) {
            console.error(error)
        }
    } catch (error) {
        console.error(error)
    }
}

const getInfoIncidente = async (id)=>{
    try {
        const request = await fetch(`/incidente/${id}`)
        if(request.ok){
            incidenteid.key = parseInt(id)
            let data = await request.json()
            fecha.value = new Date(data.fechaCreado).toISOString().slice(0, 10)
            centro.value = data.keyCentro
            equipo.value = data.keyDispositivo
            tipo.value = data.keyTipo 
            estado.value = data.keyEstado
            descripcion.value = data.descripcion
            btncreate.style.display = 'none'
            fecha.disabled = true
            centro.disabled = true
            equipo.disabled = true 
            tipo.disabled = true 
            //estado.disabled = true
            descripcion.disabled = true
            let propiedad = 'none'
            if(usuario === 1){
                propiedad = ''
            }
            document.getElementById('colsolucion').style.display =''
            solucion.value=data.solucion.trim()
            btnupdate.style.display= propiedad
            const estadorow = document.getElementById('estadorow')
            estadorow.style.display = ''
            modal.modal('show')

        }else{
            let data = await request.json()
            console.error(data)
        }
    } catch (error) {
        console.error(error)
    }
}

const updateEstado = async ()=>{
    try {
        const data = {
            estado:parseInt(estado.value),
            comentario:solucion.value.trim()
        }

        const request = await fetch(`/incidente/${incidenteid.key}`,{
            method:'PUT',
            body:JSON.stringify(data)
        })
        if(request.ok){
            listarIncidentes()
        modal.modal('hide')
        clear()
        }
        

    } catch (error) {
        console.error(error)
    }
}


document.addEventListener("DOMContentLoaded", (event)=> {
    getTipos()
    getEstados()
    listarInventarios()
    getCentros()
    fecha.value = new Date().toISOString().slice(0, 10)
    listarIncidentes()
});