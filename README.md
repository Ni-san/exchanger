Exchanger
============================

Test task based on Yii 2 Basic Project Template.

REQUIREMENTS
------------

PHP 5.4.0
PostgreSQL

INSTALLATION
------------

In apache2.conf set DocumentRoot to 'web' directory and enable mod_rewrite.

CONFIGURATION
-------------

Create PostgreSQL database and change config/db.php

Then execute 'php yii migrate/up' in console.

Anytime execute 'php yii migrate/down 1' in console to remove test users.
