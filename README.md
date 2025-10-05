# 3reiSudEst

Acest repository este un fan-site construit cu PHP + MySQL (potrivit pentru rulare locală, ex: XAMPP). A fost actualizat pentru a include un UI întunecat cu accente neon, autentificare simplă, editor WYSIWYG cu upload de imagini și un sanitizator HTML simplu.

## Ce e nou (rezumat)
- Tema dark cu accente neon (fișiere în `css/style.css`).
- Autentificare la rută `/autentificare` (pag. `autentificare/index.php`).
- Editor WYSIWYG Quill în admin cu upload direct de imagini (endpoint: `admin/upload.php`) — imaginile sunt salvate în `uploads/`.
- Sanitizare a HTML-ului introdus în admin: `inc/sanitize.php` (curățare DOM-based). Pentru producție recomand HTMLPurifier.
- Helper `base_url()` în `inc/config.php` pentru construirea dinamică a URL-urilor.

## Setup rapid (local)
1. Copiați proiectul în folderul serverului (ex: `C:\xampp\htdocs\3reiSudEst`).
2. Porniți Apache și MySQL (XAMPP).
3. Creați o bază de date numită `3reisudest` sau modificați `DB_NAME` în `.env`/`inc/config.php`.
4. Importați `sql/init.sql` în phpMyAdmin pentru a crea tabelele și datele de exemplu.
5. Opțional: creați un fișier `.env` în root (vezi `.env.example`) pentru setările DB.
6. Accesați: `http://localhost/3reiSudEst/` (sau calea unde ați copiat proiectul).

## Creare cont admin (rapid)
Crearea unui admin se face recomandat din linia de comandă (script CLI):

```powershell
php scripts/create_admin.php <username> <password>
```

După aceea folosiți `http://localhost/3reiSudEst/autentificare` pentru a vă autentifica.

## Upload imagini din editor (Quill)
- În admin (Dashboard / Edit) folosiți butonul imagine din toolbar Quill.
- Imaginea va fi încărcată la `POST /3reiSudEst/admin/upload.php` (doar admin autentificat).
- Fișierele sunt validate (jpeg/png/gif) și limitate la 2MB. Se salvează în `uploads/` (aflat în `.gitignore`).
- Endpoint-ul returnează JSON cu `url` absolut construit dinamic (folosind `base_url()`).

## Unde se află lucrurile importante
- `index.php` — router simplu + front controller
- `inc/config.php` — setări și helper `base_url()`
- `inc/db.php` — funcții PDO pentru news și users
- `inc/sanitize.php` — sanitizator HTML (DOM-based)
- `admin/` — login, dashboard, edit, delete, upload
- `autentificare/` — pagina de login disponibilă la `/autentificare`
- `uploads/` — fișiere încărcate (ignorate de git)
- `css/style.css` — variabile de temă și reguli vizuale

## Siguranță și recomandări
- Sanitizatorul din `inc/sanitize.php` este util pentru mediul local. Pentru producție recomand instalarea și folosirea HTMLPurifier (Composer) pentru sanitizare robustă.
- Folosiți HTTPS și reguli de hardening (CSP, rate limiting) în producție.

## Personalizare vizuală
- Modificați culorile în `css/style.css` (variabile `:root`) pentru a regla paleta neon/dark.
- Logo: `assets/logo.svg` — înlocuiți cu versiunea voastră păstrând numele sau actualizați calea.

## Testare rapidă (lint)
- PHP lint pentru fișiere modificate: `php -l path/to/file.php`

## Contribuții & issues
- Deschide un issue pe repo sau trimite un PR pentru modificări.

---
Documentația va fi actualizată pe măsură ce adăugăm funcționalități (ex: HTMLPurifier, optimizare imagini, upload avatars). Dacă vrei, pot adăuga un CHANGELOG sau un fișier `docs/` cu instrucțiuni detaliate.

