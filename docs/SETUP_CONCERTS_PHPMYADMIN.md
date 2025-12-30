# Setup Concerts Module Using phpMyAdmin

## Quick Setup (5 Minutes)

### Step 1: Open phpMyAdmin

1. Open your browser
2. Go to: **http://localhost:8081**
3. Login:
   - Username: `root`
   - Password: `root_password_123`

### Step 2: Select Database

1. Click **`3sudest`** in the left sidebar
2. You'll see all your existing tables

### Step 3: Run Setup Script

1. Click the **"SQL"** tab at the top of the page
2. **Copy and paste** the ENTIRE script below:

```sql
-- Complete Setup: Create Concerts Table + Fix All Encoding

-- Set connection to UTF-8
SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;

-- Create concerts table
CREATE TABLE IF NOT EXISTS `concerts` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `venue` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'România',
  `event_date` DATE NOT NULL,
  `event_time` TIME DEFAULT NULL,
  `description` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `ticket_url` VARCHAR(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `venue_map_url` VARCHAR(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `poster_image` VARCHAR(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price_info` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` ENUM('upcoming', 'sold_out', 'cancelled', 'completed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'upcoming',
  `is_published` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_event_date` (`event_date`),
  INDEX `idx_status` (`status`),
  INDEX `idx_published` (`is_published`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample concerts
INSERT INTO `concerts`
  (`title`, `venue`, `city`, `country`, `event_date`, `event_time`, `description`, `price_info`, `status`)
VALUES
  (
    '3 Sud Est - Concert de Revelion',
    'Sala Palatului',
    'București',
    'România',
    '2025-12-31',
    '21:00:00',
    'Întâlnire de neuitat pentru fanii 3 Sud Est! Cea mai mare petrecere de Revelion cu hit-urile voastre preferate.',
    '150-300 RON',
    'upcoming'
  ),
  (
    'Concert Extraordinar - Turneu Național',
    'Arena Transilvania',
    'Cluj-Napoca',
    'România',
    '2025-11-15',
    '20:00:00',
    'Turneul național 3 Sud Est revine la Cluj! Nu ratați evenimentul anului!',
    '100-250 RON',
    'upcoming'
  )
ON DUPLICATE KEY UPDATE id=id;

-- Fix encoding for all existing tables
ALTER TABLE users CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE albums CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE tracks CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE news CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE gallery_items CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE biography_sections CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE concerts CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

3. Click the **"Go"** button at the bottom right
4. Wait for the green success message

### Step 4: Verify Success

You should see:
- ✅ "7 rows affected" or similar success messages
- ✅ A table showing the encoding verification results

### Step 5: Test the Website

1. **Public Concerts Page**: http://localhost:8080/concerts
   - You should see 2 sample concerts
   - Romanian characters should display correctly: **București**, **România**, **Național**

2. **Admin Panel**: http://localhost:8080/admin/?page=concerts
   - Login: `admin` / `admin123`
   - You should see the concerts management interface
   - The 2 sample concerts should be listed in the table

## What This Script Does

1. ✅ Creates the `concerts` table with proper UTF-8 encoding
2. ✅ Adds 2 sample concerts for testing
3. ✅ Fixes encoding for ALL existing tables
4. ✅ Verifies all tables use utf8mb4_unicode_ci

## Expected Results

### In phpMyAdmin

After running the script, you should see in the left sidebar:
- All your existing tables
- **NEW**: `concerts` table

Click on the `concerts` table and you'll see 2 sample records.

### On the Website

**Public Page** (http://localhost:8080/concerts):
- Hero section: "Concerte"
- "Concerte Viitoare" section with 2 concerts
- Each concert shows: date, title, venue, city, description, price

**Admin Page** (http://localhost:8080/admin/?page=concerts):
- Table with 2 concerts
- Buttons: "Editează" and "Șterge" for each concert
- Form to add new concerts

### Romanian Characters

You should see:
- ✅ București (not BucureÈ™ti)
- ✅ România (not RomÃ¢nia)
- ✅ Național (not NaÈ›ional)
- ✅ Întâlnire (not ÃŽntÃ¢lnire)

## Troubleshooting

### "Commands out of sync" Error

If you get this error:
1. Close the SQL tab
2. Refresh phpMyAdmin
3. Try running the script again

### "Duplicate entry" Error

This means the concerts already exist. This is OK - the script handles it.

### "Table already exists" Error

This is also OK - the `CREATE TABLE IF NOT EXISTS` handles it safely.

### Still Seeing Weird Characters

If encoding is fixed but characters still look wrong:

**The data was saved incorrectly before**. Solution:

1. Go to admin panel: http://localhost:8080/admin/?page=concerts
2. Delete the existing concerts
3. Add new concerts - they will save correctly now

## Alternative: Using WSL

If you have WSL and prefer command line:

1. Open WSL terminal (Windows key, type "wsl")
2. Navigate to project:
   ```bash
   cd /mnt/c/Users/claud/Desktop/3reiSudEst
   ```
3. Make script executable:
   ```bash
   chmod +x setup-concerts.sh
   ```
4. Run it:
   ```bash
   ./setup-concerts.sh
   ```

## Next Steps

After successful setup:

1. **Explore the admin panel** - Try adding your own concerts
2. **Customize sample concerts** - Edit the dates, venues, descriptions
3. **Delete samples** - Remove the sample concerts if you don't need them
4. **Add real concerts** - Create actual upcoming events

## Files Reference

- **This guide**: `SETUP_CONCERTS_PHPMYADMIN.md`
- **SQL script**: `sql/setup_concerts_and_fix_encoding.sql`
- **WSL script**: `setup-concerts.sh`

The concerts module is now ready to use! 🎸
