# Quick Fix for Internal Server Error

## The Problem

You're getting an Internal Server Error on http://localhost:8080/biography

## Most Likely Cause

The new Biography model file was created but the web server hasn't reloaded the PHP files yet.

## Solution

### Option 1: Restart Docker Container (Recommended)

```bash
docker-compose restart web
```

Wait 5 seconds, then try again: http://localhost:8080/biography

### Option 2: Test the Biography Model Directly

Visit: http://localhost:8080/test-bio.php

This will show you the exact error message.

### Option 3: Check Error Logs

```bash
docker-compose logs -f web
```

Then refresh http://localhost:8080/biography and watch for errors.

### Option 4: Full Docker Restart

```bash
docker-compose down
docker-compose up -d
```

## Common Issues

### 1. Biography Model Not Found
**Symptom**: "Class 'Biography' not found"
**Fix**: The restart will fix this

### 2. Database Table Doesn't Exist
**Symptom**: "Table 'biography_sections' doesn't exist"
**Fix**: Check if schema was imported
```bash
docker-compose exec db mysql -u3sudest_user -psecure_password_123 3sudest -e "SHOW TABLES LIKE 'biography%';"
```

### 3. Syntax Error in PHP
**Symptom**: Parse error messages
**Fix**: Check test-bio.php output

## After Restart, Test All Pages

- ✅ Home: http://localhost:8080
- ✅ Biography: http://localhost:8080/biography
- ✅ Discography: http://localhost:8080/discography
- ✅ News: http://localhost:8080/news
- ✅ Gallery: http://localhost:8080/gallery

All should work after restart!
