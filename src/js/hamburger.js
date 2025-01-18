const btnOpen = document.getElementById('btnOpen');
const menu = document.querySelector('#menu');
const bread = document.querySelector('#breadcrumb');

// Funzione per aprire il menu
var btnOpenState = 'false';
btnOpen.addEventListener('click', function () {
  btnOpenState = btnOpenState === 'false' ? 'true' : 'false';
  if (btnOpenState === 'false') {
    menu.classList.remove('active');
    bread.classList.remove('active');
  } else {
    menu.classList.add('active');
    bread.classList.add('active');
  }
  btnOpen.setAttribute('aria-expanded', btnOpenState);
});
