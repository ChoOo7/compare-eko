#On separe webserver et webserver configurated pour ne pas se retaper la compiler de php dès que l'on ajoute un vhost

sudo docker build -t compare-php ./webserver/
sudo docker build -t compare-elasticsearch ./elasticsearch/