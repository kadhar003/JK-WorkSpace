# Render.com Deployment Guide for JK Workspace

## Prerequisites

- GitHub repository with this code pushed
- Render.com account (free tier works for testing)
- Environment variables configured

## Deployment Steps

### 1. Create Services on Render

#### Option A: Using Render Dashboard (Manual)

**Step 1: Create MySQL Database**
- Go to Render Dashboard
- Click "New +" → "MySQL"
- Name: `jk-workspace-db`
- MySQL Version: 8.0
- Region: Same as web service
- Click "Create Database"
- Copy the connection string (you'll need: Host, Username, Password)

**Step 2: Create Redis Cache**
- Click "New +" → "Redis"
- Name: `jk-workspace-redis`
- Region: Same as web service
- Click "Create"

**Step 3: Create Web Service**
- Click "New +" → "Web Service"
- Connect your GitHub repository
- Build Command: `composer install && npm install && npm run build`
- Start Command: Leave blank (Docker image handles it)
- Environment Variables:
  ```
  APP_ENV=production
  APP_DEBUG=false
  APP_KEY=your-generated-key-here
  DB_CONNECTION=mysql
  DB_HOST=<mysql-service-url>
  DB_PORT=3306
  DB_DATABASE=jk_workspace
  DB_USERNAME=root
  DB_PASSWORD=<your-password>
  REDIS_HOST=<redis-service-url>
  REDIS_PORT=6379
  REDIS_PASSWORD=
  ```

#### Option B: Using render.yaml (Recommended)

1. Push the `render.yaml` file to your repository
2. Go to Render Dashboard → "New +" → "Web Service"
3. Connect your GitHub repository
4. Select the repository containing `render.yaml`
5. Render will automatically detect and deploy all services

### 2. Environment Variables Setup

Set these environment variables in Render Dashboard:

**Critical:**
- `APP_KEY` - Generate with: `php artisan key:generate`
- `APP_ENV` - Set to `production`
- `APP_DEBUG` - Set to `false`
- `APP_URL` - Set to your Render domain

**Database:**
- `DB_CONNECTION` - `mysql`
- `DB_HOST` - From MySQL service
- `DB_PORT` - `3306`
- `DB_DATABASE` - `jk_workspace`
- `DB_USERNAME` - `root`
- `DB_PASSWORD` - Strong password

**Redis:**
- `REDIS_HOST` - From Redis service
- `REDIS_PORT` - `6379`

**Mail (Optional):**
- `MAIL_MAILER` - `smtp` or `log`
- `MAIL_HOST` - Your SMTP provider
- `MAIL_PORT` - SMTP port
- `MAIL_USERNAME` - SMTP username
- `MAIL_PASSWORD` - SMTP password

**Razorpay (If using payments):**
- `RAZORPAY_KEY` - Your public key
- `RAZORPAY_SECRET` - Your secret key

### 3. First Deployment

After pushing to GitHub and configuring on Render:

1. Render will automatically trigger a build
2. Watch the "Events" tab for build logs
3. Fix any errors shown in the build output
4. Once deployed, visit your Render domain

### 4. Running Migrations

After first deployment:

```bash
# SSH into the web service or use Render Shell
php artisan migrate --force
php artisan db:seed --force  # Optional
```

Or configure a **one-off job** in Render:
- Go to service dashboard
- Click "Shell"
- Run: `php artisan migrate --force`

## Troubleshooting

### Build Fails: "apk add" Error
**Solution:** Already fixed in updated Dockerfile. The issue was invalid Alpine Linux package names. Redeploy with the latest code.

### Database Connection Error
1. Verify MySQL service is running
2. Check `DB_HOST`, `DB_USERNAME`, `DB_PASSWORD` match your MySQL service
3. Ensure Web Service has proper environment variables
4. Check Render logs for connection errors

### Migrations Not Running
1. Create a **one-off job** in Render:
   - Service → Shell → Run migrations manually
2. Or add post-deploy hook in your service settings

### Application Stuck in Build
1. Check build logs in Render dashboard
2. Verify all environment variables are set
3. Ensure Dockerfile and docker-compose.yml are valid
4. Clear build cache: Delete and recreate service

### Redis Connection Issues
1. Ensure Redis service is running
2. Verify `REDIS_HOST` uses the private service URL, not public
3. Check firewall/networking settings

### File Upload Issues
1. File uploads go to `storage/app` - persistent storage works
2. Public files in `public/` may need static file configuration
3. Use S3/cloud storage for production file handling

## Performance Optimization

1. **Enable Caching**
   ```bash
   php artisan config:cache
   php artisan route:cache
   ```

2. **Use Redis** for:
   - Session storage: `SESSION_DRIVER=redis`
   - Cache: `CACHE_STORE=redis`
   - Queues: `QUEUE_CONNECTION=redis`

3. **Queue Processing**
   - Deploy queue worker as separate service
   - Or use Render background workers

4. **Database Optimization**
   - Add indexes to frequently queried columns
   - Monitor slow query logs in MySQL

## Database Backup

Before production:
1. Set up automated backups in Render MySQL dashboard
2. Download backups regularly
3. Test restore procedures

## SSL/HTTPS

Render provides free SSL:
1. Auto-generated for all services
2. Set `APP_URL=https://your-domain.onrender.com`
3. Update CSRF settings if needed

## Monitoring

1. Check **Logs** regularly
2. Monitor **Metrics** for resource usage
3. Set up **Notifications** for deploy failures
4. Use Laravel's built-in logging

## Production Checklist

- [ ] APP_DEBUG = false
- [ ] APP_ENV = production
- [ ] APP_URL = https://your-domain
- [ ] Database credentials set correctly
- [ ] Redis connected and working
- [ ] Mail SMTP configured
- [ ] Migrations run successfully
- [ ] Assets built and deployed
- [ ] SSL certificate working
- [ ] Backups configured
- [ ] Monitoring enabled
- [ ] Error logs reviewed

## Additional Resources

- [Render Documentation](https://render.com/docs)
- [Laravel Deployment Guide](https://laravel.com/docs/deployment)
- [Docker Documentation](https://docs.docker.com/)
- [Render MySQL Docs](https://render.com/docs/deploy-mysql)
- [Render Redis Docs](https://render.com/docs/deploy-redis)
