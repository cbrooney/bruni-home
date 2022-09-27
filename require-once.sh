touch ./docker/php-fpm/.bash_history

cp .env.local.docker-compose.dist .env.local.docker-compose

rm ./public/bilder
ln -sT /media/usb/netzwerk/share/raspi ./public/bilder

# ls -la public zeigt: bilder -> /media/usb/netzwerk/share/rasp
