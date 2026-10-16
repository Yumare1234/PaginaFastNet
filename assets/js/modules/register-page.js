import { showMessage } from '../formMessages.js';
import { initPasswordToggle } from '../passwordToggle.js';

async function handleRegisterSubmit(event) {
  event.preventDefault();

  const nombre = document.getElementById('nombre')?.value.trim();
  const apellido = document.getElementById('apellido')?.value.trim();
  const email = document.getElementById('email')?.value.trim();
  const telefono = document.getElementById('telefono')?.value.trim();
  const direccion = document.getElementById('direccion')?.value.trim();
  const password = document.getElementById('password')?.value;
  const confirmar = document.getElementById('confirmar')?.value;
  const terminos = document.getElementById('terminos')?.checked;

  if (!nombre || !apellido || !email || !telefono || !password || !confirmar) {
    showMessage({
      text: 'Por favor completa todos los campos obligatorios.',
      type: 'error'
    });
    return;
  }

  if (password !== confirmar) {
    showMessage({
      text: 'Las contraseñas no coinciden.',
      type: 'error'
    });
    return;
  }

  if (!terminos) {
    showMessage({
      text: 'Debes aceptar los términos y condiciones.',
      type: 'error'
    });
    return;
  }

  const formData = new FormData();
  formData.append('nombre', nombre);
  formData.append('apellido', apellido);
  formData.append('email', email);
  formData.append('telefono', telefono);
  formData.append('direccion', direccion || '');
  formData.append('password', password);
  formData.append('confirmar', confirmar);

  try {
    const response = await fetch('../../../src/api/register.php', {
      method: 'POST',
      body: formData
    });

    const text = await response.text();
    let result = {};

    try {
      result = text ? JSON.parse(text) : {};
    } catch (error) {
      throw new Error(text || 'Error interno del servidor.');
    }

    if (!response.ok) {
      throw new Error(result.message || 'Error al registrar usuario.');
    }

    showMessage({
      text: result.message || '¡Cuenta creada exitosamente!',
      type: 'success',
      duration: 2200
    });

    setTimeout(() => {
      window.location.href = 'sesion.html';
    }, 700);
  } catch (error) {
    showMessage({
      text: error.message,
      type: 'error'
    });
  }
}

export function initRegisterPage() {
  initPasswordToggle();

  const registerForm = document.getElementById('registerForm');
  if (!registerForm) return;

  registerForm.addEventListener('submit', handleRegisterSubmit);
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initRegisterPage);
} else {
  initRegisterPage();
}
