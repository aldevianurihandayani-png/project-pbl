<script>
  function closeAllMenus() {
    const sidebar = document.getElementById('sidebar');
    const dropdown = document.querySelector('.notif-dropdown .dropdown-content');
    if (sidebar?.classList.contains('show')) sidebar.classList.remove('show');
    if (dropdown?.classList.contains('show-dropdown')) dropdown.classList.remove('show-dropdown');
  }

  // Toggle notif dropdown
  const notifDropdown = document.querySelector('.notif-dropdown');
  if (notifDropdown) {
    notifDropdown.addEventListener('click', function (e) {
      e.stopPropagation();
      const c = this.querySelector('.dropdown-content');
      closeAllMenus();
      c.classList.toggle('show-dropdown');
    });
  }

  // Close on outside click
  window.addEventListener('click', function (e) {
    if (!e.target.closest('.sidebar') && !e.target.closest('.notif-dropdown')) closeAllMenus();
  });
</script>
