[program:nginx]
depends_on = php-fpm
command = nginx -g "pid /run/nginx.pid; daemon off;"
stopasgroup = true

[program:php-fpm]
command = php-fpm
stopasgroup = true
