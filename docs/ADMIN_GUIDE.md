# Admin Panel Guide

## Access

- **URL**: http://localhost:8080/admin/
- **Login**: http://localhost:8080/admin/login.php
- **Default Credentials**:
  - Username: `admin`
  - Password: `admin123`

**IMPORTANT**: Change the admin password in production!

## Features

### 1. Dashboard
- Overview of the admin panel
- Quick navigation to all content sections

### 2. Discography Management

**URL**: http://localhost:8080/admin/?page=discography

**Features**:
- Add, edit, and delete albums
- Fields:
  - Title (required)
  - Release year (required)
  - Cover image URL
  - Description
  - Spotify URL
  - Apple Music URL
  - Sort order (for custom ordering)
  - Published status (show/hide on public site)
- Album cover preview
- Published/Draft badge status

### 3. News Management

**URL**: http://localhost:8080/admin/?page=news

**Features**:
- Add, edit, and delete news articles
- Fields:
  - Title (required)
  - Excerpt (short summary for previews)
  - Content (required) - main article text
  - Featured image URL
  - Published status
- Automatic slug generation from title (Romanian-aware)
- Image thumbnail preview
- Date display

### 4. Gallery Management

**URL**: http://localhost:8080/admin/?page=gallery

**Features**:
- Add, edit, and delete gallery images
- Fields:
  - Title (required)
  - Description
  - Image URL (required)
  - Thumbnail URL (optional, uses main image if not provided)
  - Category (general, concert, backstage, press, promo)
  - Sort order
  - Published status
- Grid layout display
- Image preview on form
- Category filtering support

### 5. Biography Management

**URL**: http://localhost:8080/admin/?page=biography

**Features**:
- Add, edit, and delete biography sections
- Fields:
  - Section title (required) - e.g., "Începuturile", "Succesul Internațional"
  - Content (required) - section text
  - Year from (optional) - start year of this period
  - Year to (optional) - end year, leave empty for "present"
  - Sort order
  - Published status
- Timeline-based organization
- Multi-section support for chronological biography

## Common Features

All admin sections include:

- **CRUD Operations**: Create, Read, Update, Delete
- **Draft Mode**: Unpublished items are hidden from public site but visible in admin
- **Sort Order**: Custom ordering for display
- **Inline Editing**: Click "Editează" to modify existing items
- **Confirmation Dialogs**: Safety prompts before deletion
- **Success/Error Messages**: Visual feedback for all operations
- **Responsive Design**: Works on desktop and tablet
- **Romanian Language**: All UI text in Romanian

## Data Models

### Album Model
- Full CRUD operations
- Track management (not yet in UI, available via model)
- Cascading delete (deletes tracks when album deleted)

### News Model
- Full CRUD operations
- Automatic slug generation (Romanian character support: ă, â, î, ș, ț)
- Slug uniqueness checking

### Gallery Model
- Full CRUD operations
- Physical file cleanup on delete (if files stored locally)
- Category-based organization

### Biography Model
- Full CRUD operations
- Timeline support with year ranges
- Chronological ordering

## Security Features

- Session-based authentication
- Password hashing (bcrypt)
- SQL injection protection (prepared statements)
- Input sanitization
- Protected .env file access
- CSRF protection (available via CSRF.php, ready for integration)

## Future Enhancements

Potential improvements for future implementation:

1. **File Upload**: Direct image upload instead of URL input
2. **WYSIWYG Editor**: Rich text editor for content fields
3. **Bulk Actions**: Select and delete/publish multiple items
4. **Search/Filter**: Search through content in admin tables
5. **Track Management UI**: Add/edit album tracks from discography page
6. **Image Management**: Upload and manage media library
7. **User Management**: Add/remove admin users, role permissions
8. **Activity Log**: Track admin actions and changes
9. **Backup/Export**: Database export functionality
10. **Analytics**: View counts, popular content tracking

## Tips

1. **Images**: For best results, use external image hosting (Cloudinary, imgbb, etc.) or set up proper upload handling
2. **Ordering**: Lower sort_order numbers appear first
3. **Drafts**: Use unpublished status to prepare content before going live
4. **Slugs**: News slugs are auto-generated from titles and handle Romanian characters
5. **Consistency**: Try to keep image aspect ratios consistent for best visual appearance

## Troubleshooting

**Can't login?**
- Check database connection in .env (manual) or docker-compose.yml (Docker)
- Verify admin user exists in database
- Check browser console for JavaScript errors

**Changes not appearing?**
- Make sure item is marked as "Published"
- Clear browser cache
- Check database was actually updated

**500 Errors?**
- Check Docker logs: `docker compose logs -f web`
- Verify .htaccess RewriteBase is set to `/` (for Docker)
- Check file permissions in container

## Logout

Click the red "Logout" button in the top-right corner of any admin page.
