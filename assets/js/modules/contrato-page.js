function handleContratoSubmit(event) {
  event.preventDefault();
  alert('¡Solicitud enviada con éxito! Un asesor se pondrá en contacto contigo.');
  window.location.href = '../../index.html';
}

function actualizarCoordenadas(map, marker, coordenadasInput, lat, lng) {
  const valor = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;
  coordenadasInput.value = valor;
  marker.setLatLng([lat, lng]).addTo(map);
  map.flyTo([lat, lng], 14, { duration: 1.2 });
}

export function initContratoPage() {
  const contratoForm = document.getElementById('contratoForm');
  if (contratoForm) {
    contratoForm.addEventListener('submit', handleContratoSubmit);
  }

  if (!window.L || !document.getElementById('caracasMap')) {
    return;
  }

  const map = window.L.map('caracasMap').setView([10.4806, -66.9036], 11);

  window.L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
  }).addTo(map);

  const marker = window.L.marker([10.4806, -66.9036]).addTo(map);
  const coordenadasInput = document.getElementById('coordenadas');

  map.on('click', function (event) {
    const { lat, lng } = event.latlng;
    actualizarCoordenadas(map, marker, coordenadasInput, lat, lng);
  });

  actualizarCoordenadas(map, marker, coordenadasInput, 10.4806, -66.9036);
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initContratoPage);
} else {
  initContratoPage();
}
