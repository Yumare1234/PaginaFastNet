export function showMessage({ text, type = 'info', duration = 2600, onClose } = {}) {
  let toast = document.getElementById('fastnet-toast');

  if (!toast) {
    toast = document.createElement('div');
    toast.id = 'fastnet-toast';
    toast.style.cssText = [
      'position: fixed',
      'top: 22px',
      'right: 22px',
      'max-width: 360px',
      'min-width: 260px',
      'padding: 14px 16px',
      'border-radius: 12px',
      'box-shadow: 0 18px 40px rgba(0,0,0,0.25)',
      'display: flex',
      'align-items: center',
      'gap: 10px',
      'color: #fff',
      'font-family: Arial, sans-serif',
      'font-size: 14px',
      'font-weight: 700',
      'opacity: 0',
      'transform: translateY(-10px)',
      'transition: opacity 0.25s ease, transform 0.25s ease',
      'z-index: 99999'
    ].join(';');

    document.body.appendChild(toast);
  }

  const icons = {
    success: '✓',
    error: '!',
    info: 'i'
  };

  const colors = {
    success: 'linear-gradient(135deg, #16a34a, #15803d)',
    error: 'linear-gradient(135deg, #ef4444, #dc2626)',
    info: 'linear-gradient(135deg, #f59e0b, #d97706)'
  };

  toast.style.background = colors[type] || colors.info;
  toast.innerHTML = '<span style="display:inline-flex;align-items:center;justify-content:center;width:22px;height:22px;border-radius:50%;background:rgba(255,255,255,0.2);font-weight:900;">' + (icons[type] || 'i') + '</span><span>' + text + '</span>';
  toast.style.opacity = '1';
  toast.style.transform = 'translateY(0)';

  clearTimeout(toast.closeTimer);
  toast.closeTimer = setTimeout(() => {
    toast.style.opacity = '0';
    toast.style.transform = 'translateY(-10px)';
    if (typeof onClose === 'function') onClose();
  }, duration);
}
