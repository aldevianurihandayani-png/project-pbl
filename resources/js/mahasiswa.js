document.addEventListener('click', (e)=>{
  const sb = document.getElementById('sidebar');
  const btn = e.target.closest('#toggleSidebar');
  if(btn){ sb.classList.toggle('show'); return; }
  if(!sb.classList.contains('show')) return;
  if(!e.target.closest('#sidebar')) sb.classList.remove('show');
});
