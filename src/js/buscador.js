document.addEventListener('DOMContentLoaded', function() {
    iniciarApp();
});

function iniciarApp(){
    buscarPorFecha();
}

function buscarPorFecha(){
    const fechaInput= document.querySelector('#fecha');
    fechaInput.addEventListener('input', function(e){
        const fechaSeleccionada = e.target.value;

        //Se añade a url fecha seleccionada para recuperarla por url
        window.location = `?fecha=${fechaSeleccionada}`;

    })
}