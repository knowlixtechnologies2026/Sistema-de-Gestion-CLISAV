function initBuscadorSelector(opciones) {
    const items = opciones.items;
    const inputTexto = document.getElementById(opciones.inputTextoId);
    const inputOculto = document.getElementById(opciones.inputOcultoId);
    const lista = document.getElementById(opciones.listaId);
    const mostrarTodoVacio = opciones.mostrarTodoVacio || false;
    const maxResultados = opciones.maxResultados || null; 

    function normalizar(texto) {
        return texto.toLowerCase();
    }

    function obtenerCoincidencias(filtro) {
        if (filtro.trim() === '') {
            return mostrarTodoVacio ? items.slice() : [];
        }
        const filtroNorm = normalizar(filtro);
        return items.filter(function (i) { return normalizar(i.texto).indexOf(filtroNorm) !== -1; });
    }

    function renderizar(filtro) {
        lista.innerHTML = '';

        if (filtro.trim() === '' && !mostrarTodoVacio) {
            lista.style.display = 'none';
            return;
        }

        let coincidencias = obtenerCoincidencias(filtro);
        if (maxResultados !== null) {
            coincidencias = coincidencias.slice(0, maxResultados);
        }

        if (coincidencias.length === 0) {
            const li = document.createElement('li');
            li.textContent = 'Sin resultados';
            li.classList.add('sin-resultados');
            lista.appendChild(li);
            lista.style.display = 'block';
            return;
        }

        coincidencias.forEach(function (i) {
            const li = document.createElement('li');
            li.textContent = i.texto;
            li.addEventListener('click', function () {
                inputTexto.value = i.texto;
                inputOculto.value = i.id;
                lista.innerHTML = '';
                lista.style.display = 'none';
            });
            lista.appendChild(li);
        });

        lista.style.display = 'block';
    }

    inputTexto.addEventListener('input', function () {
        inputOculto.value = '';
        renderizar(inputTexto.value);
    });

    inputTexto.addEventListener('focus', function () {
        if (inputTexto.value.trim() !== '' || mostrarTodoVacio) {
            renderizar(inputTexto.value);
        }
    });

    document.addEventListener('click', function (e) {
        if (e.target !== inputTexto && !lista.contains(e.target)) {
            lista.style.display = 'none';
        }
    });
}

function validarBusquedaSelector(inputOcultoId, mensajeErrorId) {
    const inputOculto = document.getElementById(inputOcultoId);
    const mensajeError = document.getElementById(mensajeErrorId);

    if (inputOculto.value === '') {
        mensajeError.style.display = 'block';
        return false;
    }

    mensajeError.style.display = 'none';
    return true;
}

function validarMultiplesBusquedas(pares) {
    let ok = true;
    pares.forEach(function (par) {
        const valido = validarBusquedaSelector(par[0], par[1]);
        if (!valido) ok = false;
    });
    return ok;
}