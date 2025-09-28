window.toggleSidebar = function () {
    const sidebar = document.getElementById('sidebar');
    const content = document.getElementById('mainContent');
    const header = document.getElementById('mainHeader');
    const texts = document.querySelectorAll('.sidebar-text');
    const title = document.getElementById('sidebarTitle');

    sidebar.classList.toggle('sidebar-expanded');
    sidebar.classList.toggle('sidebar-collapsed');

    content.classList.toggle('content-expanded');
    content.classList.toggle('content-collapsed');

    header.classList.toggle('header-expanded');
    header.classList.toggle('header-collapsed');

    
window.addEventListener('scroll', () => {
    if (window.scrollY > 10) {
        header.classList.add('shadow-lg');
    } else {
        header.classList.remove('shadow-lg');
    }
});


    // Adjust header position when sidebar collapses
    if (sidebar.classList.contains('sidebar-collapsed')) {
        header.classList.remove('left-60');
        header.classList.add('left-20');
    } else {
        header.classList.remove('left-20');
        header.classList.add('left-60');
    }

    texts.forEach(el => {
        el.style.display = sidebar.classList.contains('sidebar-collapsed') ? 'none' : 'inline';
    });

    if (title) {
        title.style.display = sidebar.classList.contains('sidebar-collapsed') ? 'none' : 'block';
    }
};

// resources/js/admin.js
document.addEventListener('DOMContentLoaded', function () {
  // pick all toggle buttons
  const toggles = document.querySelectorAll('.submenu-toggle');

  toggles.forEach(btn => {
    btn.addEventListener('click', function (e) {
      const targetId = btn.dataset.target;
      if (!targetId) return;
      const menu = document.getElementById(targetId);
      if (!menu) return;

      // ACCORDION: close other menus (optional). Remove this block if you don't want accordion.
      document.querySelectorAll('ul[id$="Menu"]').forEach(ul => {
        if (ul.id !== targetId) {
          ul.classList.add('hidden');
          const c = ul.previousElementSibling?.querySelector('.caret');
          if (c) c.classList.remove('rotate-180');
        }
      });

      // toggle current menu
      menu.classList.toggle('hidden');

      // rotate caret inside the button
      const caret = btn.querySelector('.caret');
      if (caret) caret.classList.toggle('rotate-180');
    });
  });
});




