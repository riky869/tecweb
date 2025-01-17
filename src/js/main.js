var regexes = {
  crea_people_name_err: /^[a-zA-Z\s]+$/, // Only letters and spaces
  crea_people_image_err: /\.(jpeg|jpg|gif|png)$/, // Image file extensions
  crea_film_name_err: /^[a-zA-Z0-9\s]+$/, // Letters, numbers, and spaces
  crea_film_original_name_err: /^[a-zA-Z0-9\s]+$/, // Letters, numbers, and spaces
  crea_film_original_language_err: /^[a-zA-Z\s]+$/, // Only letters and spaces
  crea_film_release_date_err: /^\d{4}-\d{2}-\d{2}$/, // Date in YYYY-MM-DD format
  crea_film_runtime_err: /^\d+$/, // Only numbers
  crea_film_phase_err: /^\d+$/, // Only numbers
  crea_film_budget_err: /^\d+(\.\d{1,2})?$/, // Numbers with optional decimal
  crea_film_revenue_err: /^\d+(\.\d{1,2})?$/, // Numbers with optional decimal
  crea_film_description_err: /^.{1,500}$/, // Any character, 1 to 500 in length
  crea_film_image_err: /\.(jpeg|jpg|gif|png)$/, // Image file extensions
  add_people_film_err: /^[a-zA-Z0-9\s]+$/, // Letters, numbers, and spaces
  add_people_people_err: /^[a-zA-Z\s]+$/, // Only letters and spaces
  add_people_role_err: /^[a-zA-Z\s]+$/, // Only letters and spaces
  add_cat_film_err: /^[a-zA-Z0-9\s]+$/, // Letters, numbers, and spaces
  add_cat_category_err: /^[a-zA-Z\s]+$/, // Only letters and spaces
  login_username_err: /^[a-zA-Z0-9_]+$/, // Letters, numbers, and underscores
  login_password_err: /^.{6,}$/, // Any character, at least 6 in length
  signup_username_err: /^[a-zA-Z0-9_]+$/, // Letters, numbers, and underscores
  signup_password_err: /^.{6,}$/, // Any character, at least 6 in length
  signup_name_err: /^[a-zA-Z\s]+$/, // Only letters and spaces
  signup_last_name_err: /^[a-zA-Z\s]+$/, // Only letters and spaces
  crea_rec_title_err: /^.{1,100}$/, // Any character, 1 to 100 in length
  crea_rec_content_err: /^.{1,1000}$/, // Any character, 1 to 1000 in length
  crea_rec_rating_err: /^[1-5]$/, // Numbers 1 to 5
  mod_rec_title_err: /^.{1,100}$/, // Any character, 1 to 100 in length
  mod_rec_content_err: /^.{1,1000}$/, // Any character, 1 to 1000 in length
  mod_rec_rating_err: /^[1-5]$/ // Numbers 1 to 5
};
