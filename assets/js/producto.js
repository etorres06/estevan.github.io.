const productoId = 'producto456';
const storageKey = `resenas_${productoId}`;

const reviewForm = document.getElementById('formResena');
const ratingInput = document.getElementById('calificacion');
const reviewText = document.getElementById('opinion');
const reviewList = document.getElementById('listaResenas');
const starRating = document.getElementById('estrellasContainer');

// estrellas interactivas
starRating.querySelectorAll('i').forEach(star => {
  star.addEventListener('click', () => {
    const value = parseInt(star.getAttribute('data-value'));
    ratingInput.value = value;

    starRating.querySelectorAll('i').forEach((s, index) => {
      if (index < value) {
        s.classList.remove('bi-star');
        s.classList.add('bi-star-fill', 'text-warning');
      } else {
        s.classList.remove('bi-star-fill', 'text-warning');
        s.classList.add('bi-star');
      }
    });
  });
});

// enviar reseña
reviewForm.addEventListener('submit', e => {
  e.preventDefault();

  const titulo = document.getElementById('titulo').value.trim();
  const nombre = document.getElementById('nombre').value.trim();
  const texto = reviewText.value.trim();
  const calificacion = parseInt(ratingInput.value);

  if (!calificacion || !texto || !titulo || !nombre) {
    alert('Por favor completa todos los campos antes de enviar tu reseña.');
    return;
  }

  const reseña = {
    puntuacion: calificacion,
    texto,
    titulo,
    nombre
  };

  const reseñasGuardadas = JSON.parse(localStorage.getItem(storageKey)) || [];
  reseñasGuardadas.push(reseña);
  localStorage.setItem(storageKey, JSON.stringify(reseñasGuardadas));
  mostrarReseña(reseña);

  reviewForm.reset();
  ratingInput.value = 0;
  starRating.querySelectorAll('i').forEach(s => {
    s.classList.remove('bi-star-fill', 'text-warning');
    s.classList.add('bi-star');
  });

  const modal = bootstrap.Modal.getInstance(document.getElementById('modalResena'));
  modal.hide();
});

// mostrar reseña individual como tarjeta
function mostrarReseña(reseña) {
  const div = document.createElement('div');
  div.className = 'review-card';

  const estrellas = '★'.repeat(reseña.puntuacion) + '☆'.repeat(5 - reseña.puntuacion);

  div.innerHTML = `
    <div class="d-flex align-items-center mb-3">
      <div class="avatar">${reseña.nombre.charAt(0).toUpperCase()}</div>
      <div class="ms-3">
        <div class="fw-semibold text-dark mb-0">${reseña.nombre}</div>
        <div class="text-warning">${estrellas}</div>
      </div>
    </div>
    <div class="fw-semibold text-dark-emphasis mb-2">${reseña.titulo}</div>
    <div class="text-secondary small">${reseña.texto}</div>
  `;

  reviewList.appendChild(div);
}

// mostrar todas las reseñas al cargar
window.addEventListener('DOMContentLoaded', () => {
  const reseñasGuardadas = JSON.parse(localStorage.getItem(storageKey)) || [];
  reseñasGuardadas.forEach(mostrarReseña);
});