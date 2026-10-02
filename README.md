# Galaxy Stream

A deployable PHP 8 + SQLite music/video streaming site.

## Requirements
- PHP 8.0+
- PDO SQLite extension enabled
- Writable permissions for `data/` and `uploads/`
- A web server such as Apache or Nginx

## Install
1. Upload all files to your hosting account.
2. Make sure `data/` and `uploads/` are writable by PHP.
3. Open `config.php` and change the upload password.
4. Visit `index.php`. The SQLite database and tables are created automatically.
5. Click Upload, choose Music or Video, enter the password, and post the file.

## Important
The default password is `CHANGE_THIS_PASSWORD`. Change it before deploying.

Recommended PHP settings for large videos:
- upload_max_filesize = 1024M
- post_max_size = 1100M
- max_execution_time = 600
- max_input_time = 600

The application stores:
- database: `data/galaxy.sqlite`
- music: `uploads/music/`
- videos: `uploads/videos/`
- cover art: `uploads/covers/`

Back up both `data/galaxy.sqlite` and `uploads/` to preserve your content.

## Security
This version uses a simple shared upload password. For a public production site, replace it with authenticated admin accounts and CSRF protection, and keep the password outside the web root/environment variables where your host supports them.
