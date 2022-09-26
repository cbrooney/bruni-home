# NAS im raspi freigeben
## sambda installieren und konfigurieren
- sudo apt-get install samba samba-common-bin
- sudo nano /etc/samba/smb.conf
- folgendes einfügen:
`[pimylifeupshare]
path = /home/pi/shared
writeable=Yes
create mask=0777
directory mask=0777
public=no`
- bzw das:
`[share]
  Comment = Raspberry Pi Shared Folder
  Path = /media/usb/netzwerk/share
  Browseable = yes
  Writeable = Yes
  only guest = no
  create mask = 0777
  directory mask = 0777
  Public = yes
  Guest ok = yes`
- samba user erstellen:
- sudo smbpasswd -a pi -n
- option -n => kein password
- sudo systemctl restart smbd

# Externe Festplatte mounten
## finde uuid der Festplatte => für fstab
ls -la /dev/disk/by-uuid/

## Ordner zum mounten 
/media/usb/netzwerk

## mount Festplatte
sudo mount /dev/sda1 /media/usb/netzwerk -o uid=pi,gid=pi

## fstab:
sudo nano /etc/fstab

## Zeile einfügen
UUID=B066D04966D01248 /media/usb/netzwerk ntfs defaults,auto,users,rw,nofail,noatime,uid=pi,gid=pi 0 0

### alte Versionen, nicht geklappt
UUID=B066D04966D01248 /media/usb/netzwerk vfat auto,nofail,noatime,users,rw,uid=pi,gid=pi 0 0

## Domainnamen finden, um sich einloggen zu können 
?? 

## Finde type des laufwerks
sudo blkid /dev/sda1


# nginx einrichten:
- siehe config file

# show services:
- sudo service --status-all