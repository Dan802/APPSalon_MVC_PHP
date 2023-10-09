let paso = 1;
const pasoInicial = 1;
const pasoFinal = 3;

// Los objetos se pueden reescribir a pesar de ser const
const cita = {  // simil carrito de compras pero para servicios
    id: '', nombre: '', fecha: '', hora: '', servicios: []
}

document.addEventListener('DOMContentLoaded', function() {
    iniciarapp();
})

function iniciarapp() {
    mostrarseccion(); // Muestra y oculta el contenido de las secciones
    tabs(); //Cambia la sección cuando se presionen los tabs
    botonesPaginador(); // Agrega o quita los btnes del paginador
    paginaSiguiente(); // btnes paginador
    paginaAnterior(); // btnes paginador
    consultarAPI(); // Consulta la API

    nombreCliente(); // Añade el nombre del cliente al objeto de cita
    idCliente(); 
    seleccionarFecha();
    seleccionarHora();

    // mostrarResumen(); // Muestra el resumen de la cita
    // siempre se va a llamar en el paginador
}

function mostrarseccion() {

    // Ocultar la sección que tenga la clase de mostar
    const seccionAnterior = document.querySelector('.mostrar');
    if(seccionAnterior) { //Si existe entonces...
        seccionAnterior.classList.remove('mostrar');
    }

    // Seleccionar la sección con el paso...
    const pasoSelector = `#paso-${paso}`;   //Seleccion por Id personalizado
    const seccion = document.querySelector(pasoSelector);
    seccion.classList.add('mostrar'); // Mostrar la sección con el paso indicado

    // Quita la clase de actual(el resaltado)
    const tabAnterior = document.querySelector('.actual');
    if(tabAnterior) {
        tabAnterior.classList.remove('actual');
    }

    // Resalta el tab actual
    const tab = document.querySelector(`[data-paso="${paso}"]`);
    tab.classList.add('actual');
}

function tabs() {
    // El código aquí solo se ejecuta una vez
    const botones = document.querySelectorAll('.tabs button');

    // 1. FORMA EFICIENTE de obtener varios botones con la misma clase
    botones.forEach( boton => {
        // El código aquí se ejecuta el numero de elementos dentro del arreglo botones

        // Le añadimos un addeventlistener a cada boton
        boton.addEventListener('click', function(e) {
            
            paso = parseInt(e.target.dataset.paso); // actualizamos paso = boton clickeado
            mostrarseccion();
            botonesPaginador();
        })
    })
    
    // #region 2. FORMA INEFICIENTE
    /*  
    botones.forEach(nombreFuncion);

    function nombreFuncion (element) { //el foreach retorna element
        //A cada boton se le asigna un eventlistener
        element.addEventListener('click', function() { 
                console.log('diste click');
        });
    }*/
    // #endregion
}

function botonesPaginador() {
    const btnSiguiente = document.querySelector('#siguiente');
    const btnAnterior = document.querySelector('#anterior');

    if(paso === 1) {
        btnAnterior.classList.add('ocultar');
        btnSiguiente.classList.remove('ocultar');
    } else if( paso === 2) {
        btnAnterior.classList.remove('ocultar');
        btnSiguiente.classList.remove('ocultar');
    } else {
        btnAnterior.classList.remove('ocultar');
        btnSiguiente.classList.add('ocultar');
        mostrarResumen();
    }

    mostrarseccion();
}

function paginaAnterior() {
    const btnAnterior = document.querySelector('#anterior');

    btnAnterior.addEventListener('click', function() {

        if(paso <= pasoInicial) return;
            paso--;
            botonesPaginador();
    })
}

function paginaSiguiente() {
    const btnSiguiente = document.querySelector('#siguiente');

    btnSiguiente.addEventListener('click', function() {

        if(paso >= pasoFinal) return;
            paso++;
            botonesPaginador();
    })
}

async function consultarAPI() {

    // try-catch: NO sobresaturar el codigo pues consume mucha memoria, solo en partes críticas
    try {   
        const url = `${location.origin}/api/servicios`;

        // Await solo funciona con funciones async
        const resultado = await fetch(url); //fetch: Busca la info

        // Para ejecturar el código siguiente se debe terminar de ejecutar el await
        const servicios = await resultado.json(); 
        mostrarServicios(servicios);

    } catch(error) {
        console.log(error);
    }
}

function mostrarServicios(servicios) {
    
    servicios.forEach( servicio => {
        // servicios forEach retorna por cada servicio un Objeto así 
        // {id : "", nombre : "", precio : ""} 
        
        // object destructuring to assign its properties to separate variables
        const {id, nombre, precio} = servicio;

        //Mostrar los Servicios en el UI
            // 1. Creamos el div y los dos p para mostrar los servicios
        const servicioDiv = document.createElement('P');
        servicioDiv.classList.add('servicio');
        servicioDiv.dataset.idServicio = id;        //Atributo personalizada en el html
        servicioDiv.onclick = function() {
            // callback para saber a cual servicio le he dado click
            seleccionarServicio(servicio);
        };

        const nombreServicio = document.createElement('P');
        nombreServicio.classList.add('nombre-servicio');
        nombreServicio.textContent = nombre;
        
        const precioServicio = document.createElement('P');
        precioServicio.classList.add('precio-servicio');
        precioServicio.textContent = `$${precio}`;

            // 2. "encapsulamos" los p dentro del div
        servicioDiv.appendChild(nombreServicio);
        servicioDiv.appendChild(precioServicio);

            // 3. Mostrarlos en pantalla
        document.querySelector('#servicios').appendChild(servicioDiv);
    });
}

function seleccionarServicio(servicio) {
    // 1. Añadimos feedback visual al usuario
    const { id } = servicio;    // Id del servicio al que el usuario le dio click
    const divServicio = document.querySelector(`[data-id-servicio="${id}"]`); // Identificar HTML del elemento al que se le da click
    divServicio.classList.toggle('seleccionado');
    
    // 2. cita esta definido como:
    // const cita = { nombre: '', fecha: '', hora: '', servicios: []}
    
    // 3. object destructuring, es decir, me devuelve los servicios en cita
    const { servicios } = cita; 
    // console.log(servicios) = servicios: Array [] 
    // length:0


    // 4.Comprobar si un servicio ya fue agregado
    // some recorre el arreglo de servicios y ejecuta un callback en cada iteración
    // La función de callback recibe como argumento el elemento actual y su índice en el arreglo.
    // entonces el callback es la condición a evaluar
    if( servicios.some( elementoActualenServicios => elementoActualenServicios.id === id) ) { 

        // El servicio ya estaba agregado, es decir, lo debo eliminar
        cita.servicios = servicios.filter( elementoActualenServicios => elementoActualenServicios.id !== id );

    } else {
        // El servicio no estaba agregado, es decir, lo debo agregar

        // 3. Tomamos una copia del diccionario de servicios y le añadimos el nuevo servicio
        // Es decir, similar a servicios += 1; 
        cita.servicios = [...servicios, servicio];
        // Lo de arriba es lo mismo que: cita.servicios.push(servicio);

        // Es muy importante lo de Object destructuring porque o si no 
        // JS da error al añadir servicio al arreglo
        
        // Ahora cita = { nombre: '', fecha: '', hora: '', 
        // servicios: [{ id: "1", nombre: "Corte...", precio: "90.00" }, { id: "2"...}, ...]
    }

    console.log(cita);
}

function nombreCliente() {
    const nombre = document.querySelector('#nombre').value;
    cita.nombre = nombre;
}

function idCliente() {
    cita.id = document.querySelector('#id').value;;
}

function seleccionarFecha() {

    const inputFecha = document.querySelector('#fecha');

    inputFecha.addEventListener('input', function (e) {

        const dia = new Date(e.target.value).getUTCDay();  // Devuelve enteros del 0 al 6

        if ( [6, 0].includes(dia)) { // Si es sabado(6) o domingo(0) entonces...
            e.target.value = '';
            mostrarAlerta('Solo abrimos entre semana, sorry', 'error', '.formulario');
        } else {
            cita.fecha = e.target.value;
        }
    });
}

function seleccionarHora() {

    const inputHora = document.querySelector('#hora');

    inputHora.addEventListener('input', function (e) {
        const horaCita = e.target.value; // Retorna la hora en formato 24:00
        const hora = horaCita.split(":")[0]; 

        if(hora < 10 || hora > 18) {
            // horas no validas 
            e.target.value = '';
            mostrarAlerta('Hora no válida', 'error', '.formulario');
        } else {
            cita.hora = horaCita;
        }
    });
}

function mostrarAlerta(mensaje, tipo, elemento, desaparece = true ) {
    //Previene que se generen más de una alerta
    const alertaPrevia = document.querySelector('.alerta');
    if(alertaPrevia) {
        alertaPrevia.remove;
    }

    // Scripting para crear la alerta
    const alerta = document.createElement('DIV');
    alerta.textContent = mensaje;
    alerta.classList.add('alerta');
    alerta.classList.add(tipo);

    const referencia = document.querySelector(elemento);
    referencia.appendChild(alerta);

    // Eliminar la alerta
    if(desaparece) {
        setTimeout(() => {
            alerta.remove();
        }, 3000);
    }
}

function obtenerFechaFormateada (fechaOriginal) {
    // Formatear la fecha en español
    const fechaObj = new Date(fechaOriginal);
    const mes = fechaObj.getMonth();
    const dia = fechaObj.getDate() + 1; // +1 porque tiene desfase de un día
    const year = fechaObj.getFullYear();

    const fechaUTC = new Date( Date.UTC(year, mes, dia + 1) );

    const opciones = {weekday : 'long', year : 'numeric', month : 'long', day: 'numeric'};
    const fechaFormateada = fechaUTC.toLocaleDateString('es-MX', opciones);

    return fechaFormateada;
}

function mostrarResumen() {
    const resumen = document.querySelector('.contenido-resumen');

    // Limpiar el contenido de resumen
    while (resumen.firstChild) {
        resumen.removeChild(resumen.firstChild);
    }

    // Si hacen falta datos o servicios entonces...
    if ( Object.values(cita).includes('') || cita.servicios.length === 0 ) {
        mostrarAlerta('Faltan datos de Servicios, Fecha u Hora', 'error', '.contenido-resumen', false);
        return;
    }

    const {servicios } = cita;
   
    // Heading para servicios en resumen
    const headingServicios = document.createElement('H3');
    headingServicios.textContent = 'Resumen de Servicios';
    resumen.appendChild(headingServicios);
    
    // Iterando y mostrando los servicios
    servicios.forEach(servicio => {
        const {id, precio, nombre} = servicio;
        const contenedorServicio = document.createElement('DIV');
        contenedorServicio.classList.add('contenedor-servicio');

        const textoServicio = document.createElement('P');
        textoServicio.textContent = nombre;

        const precioServicio = document.createElement('P');
        precioServicio.innerHTML = `<span>Precio: </span>$${precio}`;
        
        contenedorServicio.appendChild(textoServicio);
        contenedorServicio.appendChild(precioServicio);

        resumen.appendChild(contenedorServicio);
    });

    // Heading para Cita en resumen
    const headingCita = document.createElement('H3');
    headingCita.textContent = 'Resumen de Cita';
    resumen.appendChild(headingCita);

    const  { nombre, fecha, hora } = cita;

    const nombreCliente = document.createElement('P');
    nombreCliente.innerHTML = `<span>Nombre: </span> ${nombre}`;

    const fechaCita = document.createElement('P');
    fechaFormateada = obtenerFechaFormateada(fecha);
    fechaCita.innerHTML = `<span>Fecha: </span> ${fechaFormateada}`;

    const horaCita = document.createElement('P');
    horaCita.innerHTML = `<span>Hora: </span> ${hora}`;
    
    resumen.appendChild(nombreCliente);
    resumen.appendChild(fechaCita);
    resumen.appendChild(horaCita);
    
    // Boton para crear una cita
    const botonReservar = document.createElement('BUTTON');
    botonReservar.classList.add('boton');
    botonReservar.textContent = 'Reservar Cita';
    botonReservar.onclick = reservarCita;

    resumen.appendChild(botonReservar);
}

async function reservarCita() {

    const { fecha, hora, servicios, id } = cita;

    // .map: Solo guarda las coincidencias en al vble idServicios
    const idServicios = servicios.map( servicio => servicio.id );

    // formData: Enviamos datos via $_POST a la API (a APIController.php) (es como el submit de un formulario)
    const dataToSendViaPost = new FormData(); 
    
    dataToSendViaPost.append('fecha', fecha);
    dataToSendViaPost.append('hora', hora);
    dataToSendViaPost.append('usuarioId', id);
    dataToSendViaPost.append('servicios', idServicios);

    //console.log([...datos]); // forma correcta de inspeccionar los datos enviados

    // URL de la API (similar al action de un formulario)
    const url = `${location.origin}/api/citas`;

    // Thunder: Se necesita cambiar el localhost para probar con thunder
    // const url = 'http://127.0.0.1:3000/api/citas' 

    try {

        // Petición hacia la API
        // Los datos son mandados a traves de $_POST
        const response = await fetch( url, {
            method : 'POST',
            body : dataToSendViaPost 
        })
    
        // resultado_BD = resultado(bool) y $db->insert_id 
        const resultado_BD_JSON = await response.json();
    
        if(resultado_BD_JSON.resultado) {
            Swal.fire({
                icon: 'success',
                title: 'Cita Creada',
                text: 'Tu cita fue creada correctamente',
                button: 'OK'
            })
                .then( () => {  //Callback para recargar la página
                    setTimeout(() => {
                        window.location.reload();
                    }, 500); }) 
                
        }
    } catch(error) {

        Swal.fire({
            icon: 'error',
            title: "Cita Creadan't",
            text: 'Hubo un error al crear la cita',
            button: 'OK'
        })

        console.log(error);
    }
}