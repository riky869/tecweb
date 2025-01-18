const btnOpen = document.getElementById('btnOpen');
const menu = document.querySelector('#menu');
const bread = document.querySelector('#breadcrumb');

// Funzione per aprire il menu
var btnOpenState = 'false';
bread.classList.add('active');
btnOpen.addEventListener('click', function () {
  btnOpenState = btnOpenState === 'false' ? 'true' : 'false';
  if (btnOpenState === 'false') {
    menu.classList.remove('active');
    bread.classList.add('active');
  } else {
    menu.classList.add('active');
    bread.classList.remove('active');
  }
  btnOpen.setAttribute('aria-expanded', btnOpenState);
});
