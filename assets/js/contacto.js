document.addEventListener('DOMContentLoaded', function() {
    const contactForm = document.getElementById('contactForm');
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    const submitSpinner = document.getElementById('submitSpinner');
    const formResponse = document.getElementById('formResponse');

    contactForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Limpiar mensajes anteriores
        document.querySelectorAll('.error-message').forEach(el => el.textContent = '');
        formResponse.textContent = '';
        formResponse.className = 'mt-3';
        
        // Mostrar spinner de carga
        submitText.textContent = 'Enviando...';
        submitSpinner.classList.remove('d-none');
        submitBtn.disabled = true;
        
        // Obtener datos del formulario
        const formData = new FormData(contactForm);
        
        // Enviar datos al servidor
        fetch('php/contacto.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Mostrar mensaje de éxito
                formResponse.textContent = data.message;
                formResponse.classList.add('alert', 'alert-success');
                contactForm.reset();
            } else {
                // Mostrar errores
                if (data.errors) {
                    for (const [field, message] of Object.entries(data.errors)) {
                        const errorElement = document.getElementById(`error-${field}`);
                        if (errorElement) {
                            errorElement.textContent = message;
                        }
                    }
                }
                formResponse.textContent = data.message || 'Error al enviar el mensaje';
                formResponse.classList.add('alert', 'alert-danger');
            }
        })
        .catch(error => {
            formResponse.textContent = 'Error de conexión. Por favor intenta nuevamente.';
            formResponse.classList.add('alert', 'alert-danger');
        })
        .finally(() => {
            // Restaurar botón
            submitText.textContent = 'Enviar';
            submitSpinner.classList.add('d-none');
            submitBtn.disabled = false;
        });
    });
});