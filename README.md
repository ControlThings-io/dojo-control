Dependencies on Ubuntu:

    apt-get install apache2 mysql-server php5-mysql git

You must also create a database user in mysql:

    sudo mysql
    CREATE USER dojo_control@localhost IDENTIFIED BY 'letmein';
    GRANT ALL PRIVILEGES ON dojo_control.* TO dojo_control@localhost;

Copy/extract all the dojo-control files into your web root

    cd /var/www/html
    rm /var/www/index.html
    git clone https://github.com/ControlThingsPlatform/dojo-control.git ./

Fix the permissions for the snake game:

    chmod a+w snake/*.txt

Make sure the database credentials are correct in config.inc

    nano config.inc

Open a browser and visit http://IP_ADDR/reset-db.php

If that is successful, then go to http://IP_ADDR

Enjoy!
