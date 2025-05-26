#  Artist Search App

A simple yet powerful web app that helps users search for music artists, even with typos, slang, phonetic errors, or partial names.

---

##  Features

- Real-time search suggestions
- Fuzzy matching using Levenshtein distance, phonetic similarity, and aliases
- Partial name support (e.g., "TheWeek" → "The Weeknd")
- Auto-suggestions for common mistypes (e.g., "Tailor Swift" → "Taylor Swift")
- Responsive UI with artist details like image, genre, and location

---

##  Folder Structure

artist-search-app/
├── public/ → index.html, CSS, JS
├── api/ → search.php (search logic)
├── config/ → db.php (connection)
├── sql/ → SQL dump of the artist database

---

##  Requirements

- [XAMPP](https://www.apachefriends.org/) or similar PHP + MySQL server.
- A database named `music_artists_db` with an `artists` table.

---

##  Setup Instructions

1. **Place the project folder in your XAMPP `htdocs` directory:**

   ```bash
   C:/xampp/htdocs/artist-search-app/

2. Start Apache and MySQL from XAMPP Control Panel.

3. Create the database:
Open phpMyAdmin (http://localhost/phpmyadmin)
Create a new database: music_artists_db
Import the artists table and sample data.

4. Visit the app in your browser:   
http://localhost/artist-search-app/public/index.html



