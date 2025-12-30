# Fix Romanian Encoding Using phpMyAdmin

Since Docker commands aren't working from Windows Command Prompt, use phpMyAdmin instead.

## Method 1: Using phpMyAdmin (Easiest)

### Step 1: Access phpMyAdmin

1. Open your browser
2. Go to: **http://localhost:8081**
3. Login with:
   - Username: `root`
   - Password: `root_password_123`

### Step 2: Select Database

1. Click on **`3sudest`** in the left sidebar
2. You should see all your tables listed

### Step 3: Run the Fix Script

1. Click on the **"SQL"** tab at the top
2. Copy and paste this SQL code:

```sql
-- Set connection to UTF-8
SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;

-- Fix all tables
ALTER TABLE users CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE albums CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE tracks CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE news CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE gallery_items CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE biography_sections CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE concerts CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

3. Click **"Go"** button at the bottom
4. You should see a success message

### Step 4: Verify the Fix

1. Go to: http://localhost:8080/concerts
2. Check if Romanian characters display correctly:
   - Should see: **București**, **România**, **Național**
   - NOT: BucureÈ™ti, RomÃ¢nia, NaÈ›ional

## Method 2: Using WSL Terminal

If you have WSL installed, you can run Docker commands from there:

### Step 1: Open WSL Terminal

1. Press `Windows + R`
2. Type `wsl` and press Enter
3. Or search for "Ubuntu" or your WSL distribution in Start menu

### Step 2: Navigate to Project

```bash
cd /mnt/c/Users/claud/Desktop/3reiSudEst
```

### Step 3: Run Fix Script

```bash
chmod +x fix-encoding-wsl.sh
./fix-encoding-wsl.sh
```

Or run directly:

```bash
docker exec -i 3sudest_db mysql -u3sudest_user -psecure_password_123 3sudest < sql/fix_all_encoding.sql
```

## Method 3: Copy SQL File Content

If the above methods don't work:

### Step 1: Open SQL File

1. Navigate to: `c:\Users\claud\Desktop\3reiSudEst\sql\fix_all_encoding.sql`
2. Open it with Notepad or any text editor
3. Copy ALL the content

### Step 2: Paste in phpMyAdmin

1. Go to http://localhost:8081
2. Login as root
3. Select `3sudest` database
4. Click "SQL" tab
5. Paste the content
6. Click "Go"

## Troubleshooting

### "Table doesn't exist" Error

If you get an error about `concerts` table not existing:

1. Remove this line from the SQL:
   ```sql
   ALTER TABLE concerts CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

2. First create the concerts table using `sql/concerts_migration.sql`

### Still Showing Weird Characters

If encoding is fixed but characters still look wrong, the **data itself** was saved incorrectly.

**Solution**: Delete and re-enter the data through admin panel:

1. Go to http://localhost:8080/admin/?page=concerts
2. Delete existing concerts
3. Add new concerts - they will now save correctly

## Verify Encoding is Fixed

### Check in phpMyAdmin

1. Go to http://localhost:8081
2. Login and select `3sudest` database
3. Click on `concerts` table
4. Click "Structure" tab
5. Check "Collation" column - all should be `utf8mb4_unicode_ci`

### Check in Website

Visit these pages and verify Romanian characters:

- **Concerts**: http://localhost:8080/concerts
- **News**: http://localhost:8080/news
- **Biography**: http://localhost:8080/biography
- **Gallery**: http://localhost:8080/gallery

### Test String

Good Romanian characters should look like this:
```
București România Iași Brașov Constanța
ă â î ș ț Ă Â Î Ș Ț
```

Bad encoding looks like:
```
BucureÈ™ti RomÃ¢nia IaÈ™i BraÈ™ov ConstanÈ›a
Ã¤ Ã¢ Ã® È™ È›
```

## Prevention

After fixing encoding, all new data will be saved correctly automatically. The system is already configured properly for UTF-8:

✅ Database connection uses UTF-8
✅ HTML pages declare UTF-8
✅ All table schemas specify utf8mb4

You won't need to fix encoding again unless you manually create tables without specifying utf8mb4.

## Need More Help?

If you're still having issues:

1. **Check Docker is Running**: Make sure Docker Desktop is running
2. **Restart Containers**: Close and reopen Docker Desktop
3. **Clear Browser Cache**: Ctrl + Shift + Delete
4. **Try Different Browser**: Test in Chrome/Firefox/Edge

The phpMyAdmin method (Method 1) is the most reliable and doesn't require command line access.
