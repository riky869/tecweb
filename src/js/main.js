const regexCallback = (elem, { regex }) => {
  return regex.test(elem.value);
};

const lengthCallback = (elem, { min, max }) => {
  return (elem.value.length >= min || min == -1) && (elem.value.length <= max || max == -1);
};

const minMaxCharsCheck = (min = -1, max = -1) => {
  return {
    callback: lengthCallback,
    args: {
      min: min,
      max: max
    },
    error: `il testo deve essere lungo ${min !== -1 ? `almeno ${min} caratteri` : ''}${min !== -1 && max !== -1 ? ' e ' : ''}${max !== -1 ? `al massimo ${max} caratteri` : ''}.`
  };
};

const minMaxNumCheck = (min = -1, max = -1) => {
  return {
    callback: (elem, { min, max }) => {
      const value = parseFloat(elem.value);
      if (!Number.isInteger(value)) {
        return false;
      }
      return !isNaN(value) && (min === -1 || value >= min) && (max === -1 || value <= max);
    },
    args: {
      min: min,
      max: max
    },
    error: `deve essere intero e ${min !== -1 ? `almeno ${min}` : ''}${min !== -1 && max !== -1 ? ' e ' : ''}${max !== -1 ? `al massimo ${max}` : ''}.`
  };
};

const commonChecks = {
  name: [
    minMaxCharsCheck(2, 100),
    {
      callback: regexCallback,
      args: {
        regex: /^[a-zA-ZàèéìòùÀÈÉÌÒÙçÇ' ]+$/
      },
      error: 'può contenere solo lettere, spazi e apostrofi.'
    }
  ],
  film: [
    minMaxCharsCheck(2, 100),
    {
      callback: regexCallback,
      args: {
        regex: /^[a-zA-Z0-9\s'’\-!?]+$/
      },
      error: 'può contenere solo lettere, numeri, spazi, apostrofi, trattini, punti esclamativi o interrogativi.'
    }
  ],
  image: [
    {
      callback: regexCallback,
      args: {
        regex: /\.(jpeg|jpg|gif|png|webp)$/
      },
      error: 'deve essere un file jpeg, jpg, gif, webp o png.'
    }
  ],
  rec_title: [
    minMaxCharsCheck(2, 30),
    {
      callback: regexCallback,
      args: {
        regex: /^[a-zA-Z0-9\s.,:!?'-]+$/
      },
      error: "può contenere solo lettere, numeri, spazi e caratteri speciali come .,!?:'-"
    }
  ],
  rec_content: [
    {
      callback: regexCallback,
      args: {
        regex: /^(?!\s*$).+/
      },
      error: 'non può essere vuota o composta solo da spazi.'
    },
    minMaxCharsCheck(8, 1000)
  ],
  rec_rating: [minMaxNumCheck(1, 10)]
};

const checks = {
  crea_people_name_err: { optional: false, checks: commonChecks.name },
  crea_people_image_err: { optional: true, checks: commonChecks.image },
  crea_film_name_err: { optional: false, checks: commonChecks.film },
  crea_film_original_name_err: { optional: true, checks: commonChecks.film },
  crea_film_original_language_err: { optional: true, checks: commonChecks.film },
  crea_film_release_date_err: {
    optional: true,
    checks: [
      {
        callback: regexCallback,
        args: {
          regex: /^\d{4}-\d{2}-\d{2}$/
        },
        error: 'inserisci una data nel formato corretto (YYYY-MM-DD).'
      }
    ]
  },
  crea_film_runtime_err: { optional: true, checks: [minMaxNumCheck(10, 60 * 10)] },
  crea_film_phase_err: { optional: false, checks: [] },
  crea_film_budget_err: { optional: true, checks: [minMaxNumCheck(0, 100000000000)] },
  crea_film_revenue_err: { optional: true, checks: [minMaxNumCheck(0, 100000000000)] },
  crea_film_description_err: { optional: false, checks: [minMaxCharsCheck(8, 1000)] },
  crea_film_image_err: { optional: true, checks: commonChecks.image },
  add_people_film_err: { optional: false, checks: [minMaxNumCheck(0, -1)] },
  add_people_people_err: { optional: false, checks: [minMaxNumCheck(0, -1)] },
  add_people_role_err: {
    optional: false,
    checks: [
      minMaxCharsCheck(1, 50),
      {
        callback: regexCallback,
        args: {
          regex: /^[a-zA-ZàèéìòùÀÈÉÌÒÙçÇ\s]+$/
        },
        error: 'può contenere solo lettere, spazi e caratteri accentati.'
      }
    ]
  },
  add_cat_film_err: { optional: false, checks: [minMaxNumCheck(0, -1)] },
  add_cat_category_err: {
    optional: false,
    checks: [
      minMaxCharsCheck(1, 30),
      {
        callback: regexCallback,
        args: {
          regex: /^[a-zA-ZÀ-ÿ\s-]+$/
        },
        error: 'può contenere solo lettere, spazi e trattini.'
      }
    ]
  },
  crea_rec_title_err: { optional: false, checks: commonChecks.rec_title },
  crea_rec_content_err: { optional: false, checks: commonChecks.rec_content },
  crea_rec_rating_err: { optional: false, checks: commonChecks.rec_rating },
  mod_rec_title_err: { optional: false, checks: commonChecks.rec_title },
  mod_rec_content_err: { optional: false, checks: commonChecks.rec_content },
  mod_rec_rating_err: { optional: false, checks: commonChecks.rec_rating },
  login_username_err: { optional: false, checks: [minMaxCharsCheck(4, -1)] },
  login_password_err: { optional: false, checks: [minMaxCharsCheck(4, -1)] },
  signup_name_err: { optional: false, checks: commonChecks.name },
  signup_last_name_err: { optional: false, checks: commonChecks.name },
  signup_username_err: {
    optional: false,
    checks: [
      {
        callback: regexCallback,
        args: {
          regex: /^(?=.{3,20}$)[a-zA-Z0-9._]$/
        },
        error: 'deve essere lungo tra 3 e 20 caratteri, può contenere lettere, numeri, punti e underscore'
      }
    ]
  },
  signup_password_err: {
    optional: false,
    checks: [
      {
        callback: regexCallback,
        args: {
          regex: /^(?=.*[A-Za-z])(?=.*\d).{8,}$/
        },
        error: 'deve essere lunga almeno 8 caratteri e contenere almeno una lettera e un numero'
      }
    ]
  }
};

const forms = {
  create_people: ['crea_people_name_err', 'crea_people_image_err'],
  create_film: [
    'crea_film_name_err',
    'crea_film_original_name_err',
    'crea_film_original_language_err',
    'crea_film_release_date_err',
    'crea_film_runtime_err',
    'crea_film_phase_err',
    'crea_film_budget_err',
    'crea_film_revenue_err',
    'crea_film_description_err',
    'crea_film_image_err'
  ],
  add_people_to_film: ['add_people_people_err', 'add_people_role_err'],
  add_category_to_film: ['add_cat_film_err', 'add_cat_category_err'],
  crea_recensione: ['crea_rec_title_err', 'crea_rec_content_err', 'crea_rec_rating_err'],
  mod_recensione: ['mod_rec_title_err', 'mod_rec_content_err', 'mod_rec_rating_err'],
  login_form: ['login_username_err', 'login_password_err'],
  signup: ['signup_name_err', 'signup_last_name_err', 'signup_username_err', 'signup_password_err']
};

const checkRules = (inputElem, errElem, rule) => {
  return (
    (rule['optional'] && inputElem.value === '') ||
    rule['checks'].every(c => {
      if (!c['callback']) return true;

      if (c['callback'](inputElem, c['args'])) {
        errElem.innerHTML = '';
        errElem.classList.add('hidden');
        errElem.removeAttribute('role');
        inputElem.removeAttribute('aria-invalid');
        inputElem.removeAttribute('aria-describedby');
        return true;
      } else {
        errElem.innerHTML = c['error'];
        errElem.classList.remove('hidden');
        errElem.setAttribute('role', 'alert');
        inputElem.setAttribute('aria-invalid', 'true');
        inputElem.setAttribute('aria-describedby', errElem.id);
        inputElem.focus();
        return false;
      }
    })
  );
};

document.addEventListener('DOMContentLoaded', () => {
  Object.keys(checks).forEach(key => {
    const errElem = document.getElementById(key);
    if (errElem) {
      const inputElem = document.getElementById(errElem.getAttribute('data-input'));
      if (inputElem) {
        const event = () => {
          checkRules(inputElem, errElem, checks[key]);
        };
        inputElem.onchange = event;
        inputElem.onblur = event;
        inputElem.oninput = () => {
          if (!errElem.classList.contains('hidden')) {
            event();
          }
        };
      }
    }
  });

  Object.keys(forms).forEach(key => {
    const formElem = document.getElementById(key);
    if (formElem) {
      formElem.onsubmit = e => {
        const ok = forms[key].reduce((ok, c) => {
          const errElem = document.getElementById(c);
          if (errElem) {
            const inputElem = document.getElementById(errElem.getAttribute('data-input'));
            if (inputElem) {
              ok &= checkRules(inputElem, errElem, checks[c]);
            }
          }
          return ok;
        }, true);

        if (!ok) e.preventDefault();
      };
    }
  });

  const btnOpen = document.getElementById('btnOpen');
  const navBurger = document.getElementById('navBurger');
  const menu = document.querySelector('#menu');
  const top = document.querySelector('#top-nav');

  var btnOpenState = false;
  menu.classList.remove('active');
  top.classList.remove('active');
  btnOpen.setAttribute('aria-expanded', 'false');

  if (window.innerWidth > 786) {
    menu.setAttribute('aria-hidden', 'false');
    btnOpen.setAttribute('aria-hidden', 'true');
    navBurger.setAttribute('aria-hidden', 'true');
  } else {
    menu.setAttribute('aria-hidden', 'true');
    btnOpen.setAttribute('aria-hidden', 'false');
    navBurger.setAttribute('aria-hidden', 'false');
  }

  btnOpen.addEventListener('click', () => {
    btnOpenState = !btnOpenState;
    if (!btnOpenState) {
      menu.classList.remove('active');
      top.classList.remove('active');
    } else {
      menu.classList.add('active');
      top.classList.add('active');
    }
    menu.setAttribute('aria-hidden', !btnOpenState);
    btnOpen.setAttribute('aria-expanded', btnOpenState);
  });

  window.addEventListener('resize', () => {
    if (window.innerWidth > 768) {
      btnOpenState = false;
      btnOpen.setAttribute('aria-expanded', 'false');
      menu.classList.remove('active');
      top.classList.remove('active');
      menu.setAttribute('aria-hidden', 'false');
      btnOpen.setAttribute('aria-hidden', 'true');
      navBurger.setAttribute('aria-hidden', 'true');
    } else {
      btnOpen.setAttribute('aria-hidden', 'false');
      navBurger.setAttribute('aria-hidden', 'false');
      menu.setAttribute('aria-hidden', !btnOpenState);
    }
  });
});
