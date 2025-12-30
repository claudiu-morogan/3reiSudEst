# Fix Romanian Character Encoding Issues

If Romanian characters (ă, â, î, ș, ț) are displaying as weird symbols (Ã, È, etc.), follow these steps.

## Quick Fix

Run this command to fix the concerts table:

```bash
fix-encoding.bat
```

Or manually:

```bash
docker exec -i 3sudest_db mysql -u3sudest_user -psecure_password_123 3sudest < sql/fix_concerts_encoding.sql
```

## Understanding the Problem

Romanian characters can display incorrectly when:
1. Database table charset is not utf8mb4
2. Database connection doesn't set UTF-8 encoding
3. HTML page doesn't declare UTF-8 charset
4. Data was inserted with wrong encoding

## What's Already Configured Correctly

✅ **HTML Pages**: `<meta charset="UTF-8">` is set in all templates
✅ **Database Connection**: PDO uses `charset=utf8mb4` in DSN
✅ **PHP Encoding**: Database.php executes `SET NAMES utf8mb4`
✅ **Schema**: All tables use `DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci`

## If Concerts Still Show Wrong Characters

The issue is likely that the table was already created before with wrong encoding. Follow these steps:

### Step 1: Verify Current Encoding

Check the current table encoding:

```bash
docker exec -it 3sudest_db mysql -u3sudest_user -psecure_password_123 3sudest

# In MySQL:
SHOW CREATE TABLE concerts;
```

Look for the charset. If it says `latin1` or anything other than `utf8mb4`, that's the problem.

### Step 2: Fix the Table

Run the fix script (drops and recreates the table):

```bash
docker exec -i 3sudest_db mysql -u3sudest_user -psecure_password_123 3sudest < sql/fix_concerts_encoding.sql
```

**WARNING**: This will delete any existing concerts you've added. Save them first if needed!

### Step 3: Verify the Fix

Visit http://localhost:8080/concerts and check if Romanian characters display correctly:
- București (not BucureÈ™ti)
- România (not RomÃ¢nia)
- Național (not NaÈ›ional)
- Întâlnire (not ÃŽntÃ¢lnire)

## If Other Tables Have Issues

If biography, news, gallery, or discography have encoding problems, you may need to fix those tables too.

### Check All Tables

```sql
-- In MySQL console:
SHOW TABLE STATUS WHERE Name LIKE '%';
```

Look at the `Collation` column. All should be `utf8mb4_unicode_ci`.

### Fix Specific Table (Example for Biography)

```sql
-- Backup data first!
SELECT * FROM biography_sections;

-- Fix the table
ALTER TABLE biography_sections CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- If data is still wrong, you may need to re-insert it
```

## Prevention: Always Use UTF-8

When creating new tables, always specify:

```sql
CREATE TABLE table_name (
  ...
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

When inserting data with Romanian characters:

```sql
-- Make sure connection uses UTF-8
SET NAMES utf8mb4;

-- Then insert
INSERT INTO table (field) VALUES ('București');
```

## MySQL Configuration

Check your MySQL server default charset:

```sql
SHOW VARIABLES LIKE 'character%';
SHOW VARIABLES LIKE 'collation%';
```

All should be `utf8mb4`.

## Docker MySQL Configuration

The docker-compose.yml already sets the correct MySQL charset:

```yaml
command: --default-authentication-plugin=mysql_native_password
         --character-set-server=utf8mb4
         --collation-server=utf8mb4_unicode_ci
```

## Manual Data Fix (If Needed)

If you have existing data that's displaying wrong but the table encoding is correct, the data itself may have been inserted incorrectly.

You'll need to:

1. Export the data
2. Fix the characters manually in a text editor (UTF-8 mode)
3. Truncate the table
4. Re-import with proper encoding

## Testing Romanian Characters

Test string: `ăâîșțĂÂÎȘȚ București România Iași Craiova Brașov Galați Constanța`

If this displays correctly on your page, encoding is working properly.

## Common Encoding Issues

**Issue**: Characters look like `Ã¢`, `È™`, `ÃŽ`
**Cause**: UTF-8 data displayed as Latin-1
**Fix**: Ensure all layers use UTF-8 (database, connection, HTML)

**Issue**: Characters show as `?` or `???`
**Cause**: Data inserted as Latin-1 but read as UTF-8
**Fix**: Re-insert data with proper encoding

**Issue**: Characters show as empty boxes `□`
**Cause**: Font doesn't support Romanian characters
**Fix**: Use a web-safe font (we use Inter and Montserrat which support Romanian)

## Browser Developer Tools Check

1. Open DevTools (F12)
2. Go to Network tab
3. Reload page
4. Click on the HTML document
5. Check Response Headers for: `Content-Type: text/html; charset=UTF-8`

If charset is missing or wrong, check your server configuration.

## Files to Check

If encoding issues persist, verify these files:

- ✅ `app/Database.php` - Lines 31-32 set UTF-8
- ✅ `views/layout/header.php` - Line 4 has charset meta tag
- ✅ `admin/index.php` - Line 30 has charset meta tag
- ✅ `admin/login.php` - Line 37 has charset meta tag
- ✅ `sql/schema.sql` - All tables use utf8mb4
- ✅ `sql/concerts_migration.sql` - Uses utf8mb4

## Still Having Issues?

1. **Restart Docker containers**: `docker-restart.bat`
2. **Clear browser cache**: Ctrl+Shift+Delete
3. **Check browser encoding**: View → Encoding → Unicode (UTF-8)
4. **Verify file encoding**: Ensure all .php files are saved as UTF-8 (no BOM)

## Contact/Debug

If problems persist, run this diagnostic:

```bash
# Check database encoding
docker exec -it 3sudest_db mysql -u3sudest_user -psecure_password_123 3sudest -e "SHOW VARIABLES LIKE 'char%';"

# Check table encoding
docker exec -it 3sudest_db mysql -u3sudest_user -psecure_password_123 3sudest -e "SHOW CREATE TABLE concerts;"

# Test data
docker exec -it 3sudest_db mysql -u3sudest_user -psecure_password_123 3sudest -e "SELECT city FROM concerts LIMIT 1;"
```

The output should show `utf8mb4` everywhere and Romanian characters should display correctly in the terminal.
