const regexCallback = (elem, regex) => {
  return regex.test(elem.value);
};

const checks = {
  crea_people_name_err: [
    {
      callback: regexCallback,
      regex: /^[a-zA-Z\s]+$/,
      error: 'Il nome può contenere solo lettere e spazi.'
    }
  ],
  crea_people_image_err: [
    {
      callback: regexCallback,
      regex: /\.(jpeg|jpg|gif|png)$/,
      error: "L'immagine deve essere un file jpeg, jpg, gif o png."
    }
  ],
  crea_film_name_err: [
    {
      callback: regexCallback,
      regex: /^[a-zA-Z0-9\s]+$/,
      error: 'Il nome del film può contenere solo lettere, numeri e spazi.'
    }
  ],
  crea_film_original_name_err: [
    {
      callback: regexCallback,
      regex: /^[a-zA-Z0-9\s]+$/,
      error: 'Il nome originale del film può contenere solo lettere, numeri e spazi.'
    }
  ],
  crea_film_original_language_err: [
    {
      callback: regexCallback,
      regex: /^[a-zA-Z\s]+$/,
      error: 'La lingua originale può contenere solo lettere e spazi.'
    }
  ],
  crea_film_release_date_err: [
    {
      callback: regexCallback,
      regex: /^\d{4}-\d{2}-\d{2}$/,
      error: 'La data di uscita deve essere nel formato YYYY-MM-DD.'
    }
  ],
  crea_film_runtime_err: [
    {
      callback: regexCallback,
      regex: /^\d+$/,
      error: 'La durata deve essere un numero.'
    }
  ],
  crea_film_phase_err: [
    {
      callback: regexCallback,
      regex: /^\d+$/,
      error: 'La fase deve essere un numero.'
    }
  ],
  crea_film_budget_err: [
    {
      callback: regexCallback,
      regex: /^\d+(\.\d{1,2})?$/,
      error: 'Il budget deve essere un numero con massimo due cifre decimali.'
    }
  ],
  crea_film_revenue_err: [
    {
      callback: regexCallback,
      regex: /^\d+(\.\d{1,2})?$/,
      error: 'Il ricavo deve essere un numero con massimo due cifre decimali.'
    }
  ],
  crea_film_description_err: [
    {
      callback: regexCallback,
      regex: /^.{8,1000}$/,
      error: 'La descrizione deve essere tra 8 e 1000 caratteri.'
    }
  ],
  crea_film_image_err: [
    {
      callback: regexCallback,
      regex: /\.(jpeg|jpg|gif|png)$/,
      error: "L'immagine deve essere un file jpeg, jpg, gif o png."
    }
  ],
  add_people_film_err: [
    {
      callback: regexCallback,
      regex: /^[a-zA-Z0-9\s]+$/,
      error: 'Il nome del film può contenere solo lettere, numeri e spazi.'
    }
  ],
  add_people_people_err: [
    {
      callback: regexCallback,
      regex: /^[a-zA-Z\s]+$/,
      error: 'Il nome può contenere solo lettere e spazi.'
    }
  ],
  add_people_role_err: [
    {
      callback: regexCallback,
      regex: /^[a-zA-Z\s]+$/,
      error: 'Il ruolo può contenere solo lettere e spazi.'
    }
  ],
  add_cat_film_err: [
    {
      callback: regexCallback,
      regex: /^[a-zA-Z0-9\s]+$/,
      error: 'Il nome del film può contenere solo lettere, numeri e spazi.'
    }
  ],
  add_cat_category_err: [
    {
      callback: regexCallback,
      regex: /^[a-zA-Z\s]+$/,
      error: 'La categoria può contenere solo lettere e spazi.'
    }
  ],
  login_username_err: [
    {
      callback: regexCallback,
      regex: /^[a-zA-Z0-9_]{4,}$/,
      error: 'Il nome utente può contenere solo lettere, numeri e underscore ed essere lungo almeno 4 caratteri.'
    }
  ],
  login_password_err: [
    {
      callback: regexCallback,
      regex: /^.{4,}$/,
      error: 'La password deve essere lunga almeno 4 caratteri.'
    }
  ],
  signup_username_err: [
    {
      callback: regexCallback,
      regex: /^[a-zA-Z0-9_]+$/,
      error: 'Il nome utente può contenere solo lettere, numeri e underscore.'
    }
  ],
  signup_password_err: [
    {
      callback: regexCallback,
      regex: /^.{4,}$/,
      error: 'La password deve essere lunga almeno 4 caratteri.'
    }
  ],
  signup_name_err: [
    {
      callback: regexCallback,
      regex: /^[a-zA-Z\s]+$/,
      error: 'Il nome può contenere solo lettere e spazi.'
    }
  ],
  signup_last_name_err: [
    {
      callback: regexCallback,
      regex: /^[a-zA-Z\s]+$/,
      error: 'Il cognome può contenere solo lettere e spazi.'
    }
  ],
  crea_rec_title_err: [
    {
      callback: regexCallback,
      regex: /^.{2,30}$/,
      error: 'Il titolo deve essere tra 2 e 30 caratteri.'
    }
  ],
  crea_rec_content_err: [
    {
      callback: regexCallback,
      regex: /^.{8,1000}$/,
      error: 'Il contenuto deve essere tra 8 e 1000 caratteri.'
    }
  ],
  crea_rec_rating_err: [
    {
      callback: regexCallback,
      regex: /^[1-10]$/,
      error: 'La valutazione deve essere un numero tra 1 e 10.'
    }
  ],
  mod_rec_title_err: [
    {
      callback: regexCallback,
      regex: /^.{2,30}$/,
      error: 'Il titolo deve essere tra 2 e 30 caratteri.'
    }
  ],
  mod_rec_content_err: [
    {
      callback: regexCallback,
      regex: /^.{8,1000}$/,
      error: 'Il contenuto deve essere tra 8 e 1000 caratteri.'
    }
  ],
  mod_rec_rating_err: [
    {
      callback: regexCallback,
      regex: /^[1-10]$/,
      error: 'La valutazione deve essere un numero tra 1 e 10.'
    }
  ]
};

document.addEventListener('DOMContentLoaded', () => {
  Object.keys(checks).forEach(key => {
    const errElem = document.getElementById(key);
    if (errElem) {
      const inputId = errElem.getAttribute('data-input');
      const inputElem = document.getElementById(inputId);
      if (inputElem) {
        inputElem.onchange = () => {
          checks[key].forEach(c => {
            if (c['callback'](inputElem, c['regex'])) {
              errElem.innerHTML = '';
              inputElem.removeAttribute('aria-invalid');
            } else {
              errElem.innerHTML = c['error'];
              inputElem.setAttribute('aria-invalid', 'true');
              return;
            }
          });
        };
      }
    }
  });

  const btnOpen = document.getElementById('btnOpen');
  const menu = document.querySelector('#menu');
  const top = document.querySelector('#top-nav');

  // Funzione per aprire il menu
  var btnOpenState = false;
  btnOpen.addEventListener('click', function () {
    btnOpenState = !btnOpenState;
    if (!btnOpenState) {
      menu.classList.remove('active');
      top.classList.remove('active');
    } else {
      menu.classList.add('active');
      top.classList.add('active');
    }
    btnOpen.setAttribute('aria-expanded', btnOpenState);
  });
});
