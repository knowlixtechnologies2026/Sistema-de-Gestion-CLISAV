function initBuscadorPaciente(opciones) {
    const pacientes = opciones.pacientes;
    const inputTexto = document.getElementById(opciones.inputTextoId);
    const inputOculto = document.getElementById(opciones.inputOcultoId);
    const lista = document.getElementById(opciones.listaId);

    function normalizar(texto) {
        return texto.toLowerCase();
    }

    function renderizar(filtro) {
        lista.innerHTML = '';

        if (filtro.trim() === '') {
            lista.style.display = 'none';
            return;
        }

        const filtroNorm = normalizar(filtro);
        const coincidencias = pacientes
            .filter(function (p) { return normalizar(p.texto).indexOf(filtroNorm) !== -1; })
            .slice(0, 8);

        if (coincidencias.length === 0) {
            const li = document.createElement('li');
            li.textContent = 'Sin resultados';
            li.classList.add('sin-resultados');
            lista.appendChild(li);
            lista.style.display = 'block';
            return;
        }

        coincidencias.forEach(function (p) {
            const li = document.createElement('li');
            li.textContent = p.texto;
            li.addEventListener('click', function () {
                inputTexto.value = p.texto;
                inputOculto.value = p.id;
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
        if (inputTexto.value.trim() !== '') {
            renderizar(inputTexto.value);
        }
    });

    document.addEventListener('click', function (e) {
        if (e.target !== inputTexto && !lista.contains(e.target)) {
            lista.style.display = 'none';
        }
    });
}

function validarBusquedaPaciente(inputOcultoId, mensajeErrorId) {
    const inputOculto = document.getElementById(inputOcultoId);
    const mensajeError = document.getElementById(mensajeErrorId);

    if (inputOculto.value === '') {
        mensajeError.style.display = 'block';
        return false;
    }

    mensajeError.style.display = 'none';
    return true;
}