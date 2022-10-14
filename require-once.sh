touch ./docker/php-fpm/.bash_history
sudo chown 0777 ./docker/php-fpm/.bash_history
touch .env.local

cp .env.local.docker-compose.dist .env.local.docker-compose

# only live
rm ./public/bilder
ln -sT /media/usb/netzwerk/share/raspi ./public/bilder

# ls -la public zeigt: bilder -> /media/usb/netzwerk/share/rasp
