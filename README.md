# 3reiSudEst

Acesta este un scaffold minim pentru un fan site despre trupa 3 Sud Est folosind PHP + MySQL destinat rulării locale (de ex. XAMPP).

## Setup rapid

1. Copiați acest folder în: C:\xampp\htdocs\3reiSudEst (dacă nu este deja acolo).
2. Porniți XAMPP (Apache + MySQL).
3. Creați o bază de date numită `3reisudest` sau modificați `inc/config.php` pentru a folosi alt nume.
4. Importați `sql/init.sql` în phpMyAdmin (sau rulați scriptul SQL) pentru a crea tabelele și datele de exemplu.
5. Verificați/actualizați `inc/config.php` cu credențialele bazei de date (de obicei user: `root`, parola goală local pe XAMPP).
6. Accesați: http://localhost/3reiSudEst/

## Fișiere importante

- `index.php` — router minimal și pagină principală
- `inc/config.php` — setări DB și admin (development only)
- `inc/db.php` — helper PDO
- `inc/header.php`, `inc/footer.php` — layout comun
- `pages/*` — pagini: `home`, `about`, `members`, `news`
- `admin/*` — login simplu și dashboard pentru a administra știri (doar exemplu)
- `css/style.css` — stil minim
- `sql/init.sql` — script SQL de inițializare

## Notă de securitate

Acest proiect este doar pentru dezvoltare locală și învățare. Nu folosiți credențiale hardcodate sau logica de autentificare din `admin/` pe un server public fără a o securiza (HTTPS, validare, hashing de parole, CSRF, rate limiting etc.).

## Creare cont admin (one-time)

1. Editați `admin/setup_user.php` și setați valorile `$user` și `$pass` la credentialele dorite.
2. Accesați `http://localhost/3reiSudEst/admin/setup_user.php` o singură dată. Scriptul va crea un user în tabela `users` cu parola hash-uită.
3. După confirmare, ștergeți sau redenumiți `admin/setup_user.php` pentru a nu lăsa un endpoint de creare cont expus.

După aceasta, folosiți pagina de login `http://localhost/3reiSudEst/admin/` pentru a vă autentifica.