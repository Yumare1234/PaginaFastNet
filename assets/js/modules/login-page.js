import { showMessage } from '../formMessages.js';
import { initPasswordToggle } from '../passwordToggle.js';

async function handleLoginSubmit(event) {
  event.preventDefault();

  const email = document.getElementById('email')?.value.trim();
  const password = document.getElementById('password')?.value.trim();

  if (!email || !password) {
    showMessage({
      text: 'Por favor completa todos los campos.',
      type: 'error'
    });
    return;
  }

  const formData = new FormData();
  formData.append('email', email);
  formData.append('password', password);

  try {
    const response = await fetch('../../../src/api/login.php', {
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
      throw new Error(result.message || 'Credenciales incorrectas.');
    }

    showMessage({
      text: result.message || '¡Inicio de sesión exitoso!',
      type: 'success',
      duration: 2200
    });

    setTimeout(() => {
      window.location.href = '../../home.php';
    }, 700);
  } catch (error) {
    showMessage({
      text: error.message,
      type: 'error'
    });
  }
}

export function initLoginPage() {
  initPasswordToggle();

  const loginForm = document.getElementById('loginForm');
  if (!loginForm) return;

  loginForm.addEventListener('submit', handleLoginSubmit);
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initLoginPage);
} else {
  initLoginPage();
}
