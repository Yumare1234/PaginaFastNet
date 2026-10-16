document.addEventListener('DOMContentLoaded', () => {
  const toggleButtons = document.querySelectorAll('.toggle-password');

  toggleButtons.forEach((button) => {
    const targetId = button.dataset.target;

    if (!targetId) return;

    const targetInput = document.getElementById(targetId);

    if (!targetInput) return;

    button.addEventListener('click', () => {
      const isPassword = targetInput.type === 'password';
      targetInput.type = isPassword ? 'text' : 'password';
      button.textContent = isPassword ? '🙈' : '👁️';
      button.setAttribute(
        'aria-label',
        isPassword ? 'Ocultar contraseña' : 'Mostrar contraseña'
      );
    });
  });
});
