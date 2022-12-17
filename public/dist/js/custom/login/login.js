const btnLogin = document.getElementById('btnlogin')
const usuario = document.getElementById('username')
const password = document.getElementById('password')
toastr.options.closeMethod = 'fadeOut';
toastr.options.closeDuration = 4000;
toastr.options.closeEasing = 'swing';

const validateCredentials = async ()=>{
    try {

        const request = await fetch('/',{
            method:'POST',
            body:JSON.stringify({
                usuario:usuario.value.trim(),
                clave:password.value.trim()
            })
        })
        if(request.ok){
            window.location.href = '/home'
        }else{
            let msg = await request.json()
            toastr.error(msg.message)
        }
        
    } catch (error) {
        console.error(error)
        toastr.error('error al validar la credenciales')
    }
}




document.addEventListener("DOMContentLoaded", (event)=> {
    btnLogin.onclick = (e)=>{
        e.preventDefault()

        if(usuario.value.trim().length <=0 || password.value.trim().length <=0){
            toastr.error('ingresa tus credenciales para continuar')
        }else{
            validateCredentials()
        }

    }
});