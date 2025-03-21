let paso=1;
const pasoInicial = 1;
const pasoFinal = 3;

const cita = {
    id: '',
    nombre: '',
    fecha: '',
    hora: '',
    servicios: []
}

document.addEventListener('DOMContentLoaded', function(){
    iniciarApp();
});


function iniciarApp(){
    mostrarSeccion();//Muestra y oculta las secciones
    tabs(); //Cambia la funcion cuando se presionen los tabs
    botonesPaginador(); //Agrega o quita botones de pagina
    paginaSiguiente();
    paginaAnterior();

    consultarAPI(); //Consulta la API en el backend de PHP

    idCliente();
    nombreCliente(); //Añade el nombre del cliente al objeto de cita
    seleccionarFecha(); //Añade la fecha de la cita en el objeto cita
    seleccionarHora(); //Añade la fecha de la cita en el objeto cita
    
    mostrarResumen(); //Muestra el resumen de la cita

}

function mostrarSeccion(){
    //ocultar la seccion activa
    const seccionAnterior= document.querySelector('.mostrar');
    if(seccionAnterior){
        seccionAnterior.classList.remove('mostrar');
    }
    //seleccionar la seccion con el paso correspondiente
    const seccion = document.querySelector(`#paso-${paso}`);
    seccion.classList.add('mostrar');

    //Eliminar resaltado de la tab anterior
    const tabAnterior= document.querySelector('.actual');
    if(tabAnterior){
        tabAnterior.classList.remove('actual');
    }

    //Resaltar el tab actual
    const tab = document.querySelector(`[data-paso="${paso}"]`);
    tab.classList.add('actual');
}

function tabs(){
    const botones=document.querySelectorAll('.tabs button');

    botones.forEach( boton => { 
        boton.addEventListener('click', function(e){
            paso= parseInt(e.target.dataset.paso);

            mostrarSeccion();
            botonesPaginador(); 

        });
    })
}

function botonesPaginador(){
    const nextPage=document.querySelector('#siguiente');
    const prevPage=document.querySelector('#anterior');


    if(paso == 1){
        prevPage.classList.add('ocultar');
        nextPage.classList.remove('ocultar');
    } else if (paso == 2){
        nextPage.classList.remove('ocultar');
        prevPage.classList.remove('ocultar');

    }
    else if(paso == 3){
        nextPage.classList.add('ocultar');
        prevPage.classList.remove('ocultar');
        mostrarResumen();


    }
    mostrarSeccion();

}

function paginaAnterior(){
    const paginaAnterior = document.querySelector('#anterior');
    paginaAnterior.addEventListener('click', function() {
        
        if(paso <= pasoInicial) return;
        paso--;

        botonesPaginador();
    })
}
function paginaSiguiente(){
    const nextPage = document.querySelector('#siguiente');   
    nextPage.addEventListener('click', function() {
        
        if(paso >= pasoFinal) return;
        paso++;
        
        botonesPaginador();
    })
}

async function consultarAPI(){

    try{
        //${location.origin} como opcion
        //Si hospedamos el backend con el js con dejar solo /api/servicios valdria
        const url= '/api/servicios';
        const resultado = await fetch(url);
        const servicios= await resultado.json();
        
        mostrarServicios(servicios);


    } catch (error){
        console.log(error);
    }


}

function mostrarServicios(servicios){
    servicios.forEach(servicio =>{
        const { id, nombre, precio}= servicio; 

        //Para cada servicio recuperado, encapsulamos su nombre y su precio en un parrafo y con su clase correspondiente
        const nombreServicio = document.createElement('P');
        nombreServicio.classList.add('nombre-servicio');
        nombreServicio.textContent = nombre;

        const precioServicio = document.createElement('P');
        precioServicio.classList.add('precio-servicio');
        precioServicio.textContent = `${precio} €`;

        //Generamos un div para contener estos servicios
        const servicioDiv = document.createElement('DIV');
        servicioDiv.classList.add('servicio');
        servicioDiv.dataset.idServicio = id;
        servicioDiv.onclick = function () {
            seleccionarServicio(servicio);
        };

        //Añadimos las caracteristicas de los servicios al div
        servicioDiv.appendChild(nombreServicio);
        servicioDiv.appendChild(precioServicio)
        
        document.querySelector('#servicios').appendChild(servicioDiv);

    });
}

function seleccionarServicio(servicio){
    const { id } = servicio;

    //Se extrae el array de servicios
    const { servicios } = cita

    const divServicio= document.querySelector(`[data-id-servicio="${id}"]`);

    //Comprobar si un servicio fue agregado
    if(servicios.some(agregado => agregado.id === id )){
        //Si ya estaba, se elimina
        cita.servicios = servicios.filter(agregado => agregado.id !== id)
        divServicio.classList.remove('seleccionado');

    } else {
        //Se añade el servicio
        //Se copia el array de servicios y se añade el servicio al array
        cita.servicios = [...servicios, servicio];
        divServicio.classList.add('seleccionado');
        
    }
}

function idCliente(){
    cita.id = document.querySelector('#id').value;

}

function nombreCliente(){
    //Se recupera el value del campo con id=nombre y se asigna a la cita
    cita.nombre = document.querySelector('#nombre').value;
}

function seleccionarFecha(){
    const inputFecha = document.querySelector('#fecha');

    inputFecha.addEventListener('input', function(e) {

        const dia= new Date(e.target.value).getUTCDay(); //Se recupera el numero de día de la semana que se ha seleccionado

        //Solo se permite seleccionar dias de L-V, S (6) y D (0) no
        if( [6,0].includes(dia) ){
            e.target.value = '';
            mostrarAlerta('El establecimiento no abre sábados y domingos', 'error','.formulario');
        }else{
            cita.fecha = e.target.value;
        }

    });
}

function seleccionarHora(){
    const inputHora = document.querySelector('#hora');
    inputHora.addEventListener('input', function(e) {
        const horaCita = e.target.value;
        const hora = horaCita.split(":")[0];
        if(hora < 9 || hora >= 20){
            e.target.value = '';
            mostrarAlerta('Hora no válida' , 'error','.formulario');
        }else{
            cita.hora= e.target.value;
        }
    });
}

function mostrarAlerta(mensaje, tipo, elemento, desaparece = true){
    //Se recupera alerta previa, si ya existe impedimos mostrar mas alertas repetidas
    const alertaPrevia= document.querySelector('.alerta');
    if(alertaPrevia) {
        alertaPrevia.remove();
    }
    
    
    const alerta = document.createElement('DIV');
    alerta.textContent = mensaje;
    alerta.classList.add('alerta');
    alerta.classList.add(tipo);

    const referencia = document.querySelector(elemento);
    referencia.appendChild(alerta);

    if(desaparece){
        setTimeout(() => {
            alerta.remove();
        }, 3000);
    }
}


function mostrarResumen(){
    const resumen = document.querySelector('.contenido-resumen');

    
    //Limpiar contenido Resumen
    while(resumen.firstChild){
        resumen.removeChild(resumen.firstChild);
    }

    if (Object.values(cita).includes('') || cita.servicios.length === 0){
        mostrarAlerta("Hay algún dato sin completar", 'error','.contenido-resumen',false);
        return;
    }

    //Formatear el div de resumen

    const { nombre, fecha, hora, servicios} = cita;

    
    //Heading para resumen servicios
    const headingServicios = document.createElement('H3');
    headingServicios.textContent = "Resumen de Servicios";
    resumen.appendChild(headingServicios);


    //Iterando y mostrando los servicios
    servicios.forEach(servicio =>{
        const { id, nombre, precio } = servicio;
        const contenedorServicio = document.createElement('DIV');
        contenedorServicio.classList.add('contenedor-servicio');

        const textoServicio= document.createElement('P');
        textoServicio.textContent = nombre;
        
        const precioServicio= document.createElement('P');
        precioServicio.innerHTML = `<span>Precio: </span> ${precio};`;

        contenedorServicio.appendChild(textoServicio);
        contenedorServicio.appendChild(precioServicio);


        resumen.appendChild(contenedorServicio);
    });

    //Heading para resumen servicios
    const headingCita = document.createElement('H3');
    headingCita.textContent = "Resumen de Cita";
    resumen.appendChild(headingCita);


    
    const nombreCliente= document.createElement('P');
    nombreCliente.innerHTML = `<span>Nombre: </span> ${nombre}`;

    //Formatear Fecha
    const objFecha = new Date(fecha);
    const opciones= { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    const fechaFormateada = objFecha.toLocaleDateString('es-ES',opciones);

    const fechaCita= document.createElement('P');
    fechaCita.innerHTML = `<span>Fecha: </span> ${fechaFormateada}`;

    const horaCita= document.createElement('P');
    const jornada = hora >= '12:00' ? 'PM' : 'AM';
    horaCita.innerHTML = `<span>Hora:</span> ${hora} ${ jornada }`;


    //Boton crear cita
    const botonReservar = document.createElement('BUTTON');
    botonReservar.classList.add('boton');
    botonReservar.textContent = 'Reservar cita';
    botonReservar.onclick = reservarCita;


    resumen.appendChild(nombreCliente);
    resumen.appendChild(fechaCita);
    resumen.appendChild(horaCita);

    resumen.appendChild(botonReservar);
}

async function reservarCita(){
    const { nombre, fecha, hora, servicios, id } = cita;


    const idServicios = servicios.map( servicio => servicio.id);

    const datos = new FormData();
    datos.append('fecha', fecha);
    datos.append('hora', hora);
    datos.append('usuarioId', id);
    datos.append('servicios', idServicios);

    try {
            
        //Peticion hacia la api
        const url='/api/citas';

        const respuesta = await fetch(url, {
            method: 'POST',
            body: datos
        });


        const resultado = await respuesta.json();

        if(resultado.resultado){
            //mostrarAlerta('Cita reservada con exito' , 'exito','.contenido-resumen');
            Swal.fire({
                icon: "success",
                title: "Cita creada",
                text: "Tu cita fue reservada correctamente",
                button: 'OK'
            }).then( () => {
                setTimeout(()=> {
                    window.location.reload();
                }, 1000)
            })
        } 
        
    } catch (error) {
        Swal.fire({
            icon: "error",
            title: "Error",
            text: "Hubo un problema al intentar reservar su cita. Inténtelo de nuevo más tarde",
            button: 'OK'
        });
    }


    // ver datos del FormData
//    console.log([...datos]);
}