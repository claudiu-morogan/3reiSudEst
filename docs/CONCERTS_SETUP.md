# Concerts Module Setup Guide

The concerts module has been successfully added to your 3 Sud Est website! Follow these steps to activate it.

## 1. Run Database Migration

You need to add the concerts table to your database. Choose one of these methods:

### Option A: Using Docker (Recommended)

```bash
# Access the MySQL container
docker exec -it 3sudest_db mysql -u3sudest_user -psecure_password_123 3sudest

# Then run:
source /var/www/html/sql/concerts_migration.sql
exit
```

### Option B: Using phpMyAdmin

1. Visit http://localhost:8081
2. Login with:
   - Username: `root`
   - Password: `root_password_123`
3. Select the `3sudest` database
4. Go to "SQL" tab
5. Copy and paste the contents of `sql/concerts_migration.sql`
6. Click "Go"

### Option C: Manual SQL Command

From your project directory:

```bash
docker exec -i 3sudest_db mysql -u3sudest_user -psecure_password_123 3sudest < sql/concerts_migration.sql
```

## 2. Verify Installation

After running the migration:

1. **Check Public Page**: Visit http://localhost:8080/concerts
   - You should see the concerts page with sample data
   - Two sample concerts should be visible

2. **Check Admin Panel**: Visit http://localhost:8080/admin/?page=concerts
   - Login with `admin` / `admin123`
   - You should see the concerts management interface
   - The sample concerts should appear in the table

3. **Check Navigation**:
   - The "Concerte" link should appear in both public and admin navigation menus

## 3. Concerts Module Features

### Public Page (`/concerts`)

**Upcoming Concerts Section**:
- Shows all future concerts sorted by date
- Beautiful card layout with date badge
- Event details: title, venue, city, time
- Ticket purchase button (if URL provided)
- Location map button (if URL provided)
- Status badges: Sold Out, Cancelled
- Price information display
- Responsive design

**Past Concerts Section**:
- Shows previous concerts (limited to 10)
- Grayed out design to distinguish from upcoming
- Chronological order (newest first)

### Admin Interface (`/admin/?page=concerts`)

**Full CRUD Operations**:
- ✅ Create new concerts
- ✅ Edit existing concerts
- ✅ Delete concerts
- ✅ Publish/unpublish concerts

**Concert Fields**:
- Title (required)
- Date (required)
- Time (optional)
- Venue/Location (required)
- City (required)
- Country (default: România)
- Description
- Poster image URL
- Ticket purchase link
- Venue map link (Google Maps, etc.)
- Price information
- Status: Upcoming, Sold Out, Cancelled, Completed
- Published toggle

**Admin Features**:
- Table view with all concerts
- Sort by date
- Status badges
- Quick edit/delete actions
- Form validation
- Success/error messages

## 4. Adding Your First Concert

1. Go to http://localhost:8080/admin/?page=concerts
2. Click "+ Adaugă Concert"
3. Fill in the form:
   - **Titlu Concert**: e.g., "3 Sud Est - Concert Live București"
   - **Data**: Select the event date
   - **Ora**: Select start time (optional)
   - **Status**: Choose "Viitor" for upcoming concerts
   - **Locație/Sală**: Venue name (e.g., "Arena Națională")
   - **Oraș**: City name (e.g., "București")
   - **Țară**: Default is România
   - **Informații Preț**: e.g., "150-300 RON"
   - **Descriere**: Event description
   - **URL Poster**: Link to concert poster image
   - **Link Bilete**: Ticketing platform URL
   - **Link Hartă Locație**: Google Maps link
4. Check "Publicat" to make it visible on public site
5. Click "Adaugă Concert"

## 5. Concert Status Meanings

- **Viitor (Upcoming)**: Concert is scheduled and tickets available
- **Sold Out**: Concert is happening but tickets are sold out
- **Anulat (Cancelled)**: Concert has been cancelled
- **Trecut (Completed)**: Concert already happened

## 6. Sample Concerts

The migration includes 2 sample concerts for testing:

1. **3 Sud Est - Concert de Revelion**
   - Date: December 31, 2025
   - Location: Sala Palatului, București
   - Status: Upcoming

2. **Concert Extraordinar - Turneu Național**
   - Date: November 15, 2025
   - Location: Arena Transilvania, Cluj-Napoca
   - Status: Upcoming

You can edit or delete these samples from the admin panel.

## 7. Tips & Best Practices

### For Best Results:

1. **Add poster images**: Concerts with poster images are more engaging
2. **Include ticket links**: Make it easy for fans to purchase tickets
3. **Update status**: Mark concerts as "Sold Out" when tickets are gone
4. **Archive old concerts**: Set past concerts to "Trecut" status
5. **Add descriptions**: Provide context about special guests, setlist, etc.
6. **Use map links**: Help fans find the venue easily

### Image Hosting:

Since direct uploads aren't implemented yet, use external image hosting:
- Cloudinary: https://cloudinary.com (free tier available)
- imgbb: https://imgbb.com (free, no account needed)
- Google Drive (public links)
- Your own server

### Ticketing Platform Integration:

Popular Romanian ticketing platforms:
- iaBilet.ro
- Eventim.ro
- Bilete.ro
- MyTicket.ro

Just paste the event URL into the "Link Bilete" field.

## 8. Troubleshooting

**Concerts page shows 404**:
- Make sure Docker containers are restarted after code changes
- Run `docker-restart.bat`

**"Table 'concerts' doesn't exist" error**:
- Database migration not run yet
- Follow step 1 to run the migration

**Sample concerts not appearing**:
- Check if they're marked as published in admin panel
- Verify dates are in the future (or change Status to "Upcoming")

**Navigation link not showing**:
- Clear browser cache
- Restart Docker containers

## 9. Code Structure

**Files Created**:
- `sql/concerts_migration.sql` - Database schema
- `app/models/Concert.php` - Concert model with CRUD operations
- `admin/views/concerts.php` - Admin CRUD interface
- `views/concerts.php` - Public concerts page

**Files Modified**:
- `index.php` - Added concerts route and Concert model require
- `admin/index.php` - Added concerts to admin routes and navigation
- `views/layout/header.php` - Added "Concerte" navigation link

## 10. Future Enhancements

Consider adding these features later:
- Image upload functionality
- Calendar view of concerts
- Concert archive/history page
- Email notifications for new concerts
- Integration with Google Calendar
- Ticket availability countdown
- Venue seating maps
- Photo galleries from past concerts
- Fan reviews/comments

---

## Quick Reference

**Public Concerts Page**: http://localhost:8080/concerts
**Admin Management**: http://localhost:8080/admin/?page=concerts
**Migration File**: `sql/concerts_migration.sql`

The concerts module is now fully integrated and ready to use! 🎸
