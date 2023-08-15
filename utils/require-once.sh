# touch ./docker/php-fpm/.bash_history
# sudo chown 0777 ./docker/php-fpm/.bash_history
# bash history wurde verschoben
mkdir ../bruni-home-private
touch ../bruni-home-private/.bash_history
sudo chown 0777 ../bruni-home-private/.bash_history


mkdir /home/bruni-home/bruni-home-private/files

touch .env.local

cp .env.local.docker-compose.dist .env.local.docker-compose

# only live
# rm ./public/bilder
ln -sT /media/usb/netzwerk/share/raspi ./public/bilder
#ln -sf

# ls -la public zeigt: bilder -> /media/usb/netzwerk/share/rasp

'/media/usb/netzwerk/share/Bilder und Videos'