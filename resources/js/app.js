import './bootstrap';

/* =========================================================
   TOAST NOTIFICATIONS
   ========================================================= */
window.Toast = {
    show(message, type = 'success', duration = 3500) {
        const icons = { success: '✅', error: '❌', info: 'ℹ️', warning: '⚠️' };
        const el = document.createElement('div');
        el.className = `toast toast-${type}`;
        el.innerHTML = `<span>${icons[type] ?? '🔔'}</span><span>${message}</span>`;
        document.body.appendChild(el);
        setTimeout(() => {
            el.style.transition = 'opacity .4s, transform .4s';
            el.style.opacity = '0';
            el.style.transform = 'translateY(-8px)';
            setTimeout(() => el.remove(), 400);
        }, duration);
    },
    success: (msg, d) => window.Toast.show(msg, 'success', d),
    error:   (msg, d) => window.Toast.show(msg, 'error',   d),
    info:    (msg, d) => window.Toast.show(msg, 'info',    d),
    warning: (msg, d) => window.Toast.show(msg, 'warning', d),
};

/* =========================================================
   LIVEWIRE HOOKS
   ========================================================= */
document.addEventListener('livewire:init', () => {

    // Flash messages dispatched from Livewire
    Livewire.on('toast', ({ message, type }) => {
        window.Toast.show(message, type ?? 'success');
    });

    Livewire.on('toast-success', ({ message }) => window.Toast.success(message));
    Livewire.on('toast-error',   ({ message }) => window.Toast.error(message));

    // Scroll to top on Livewire page navigation
    Livewire.hook('commit', ({ component, succeed }) => {
        succeed(({ snapshot }) => {
            if (snapshot?.memo?.redirectUrl) {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        });
    });
});

/* =========================================================
   GENERAL UTILS
   ========================================================= */

// Scroll to top button
document.addEventListener('DOMContentLoaded', () => {
    const btn = document.getElementById('scroll-top-btn');
    if (!btn) return;
    window.addEventListener('scroll', () => {
        btn.classList.toggle('hidden', window.scrollY < 300);
    });
    btn.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
});
