Web service dedicación de curso
===============================

Este módulo local proporciona un servicio web que devuelve los registros de actividad de un usuario para un curso.

Configuration
-------------
To use this service you will need to create the following:

1. A web service on a Moodle installation
2. A user with sufficient permissions to use the web service
3. A token for that user

See [Using web services](https://docs.moodle.org/29/en/Using_web_services) in the Moodle documentation for information about creating and enabling web services. The user will need the following capabilities in addition to whichever protocol you enable:

- `report/log:viewtoday`
- `report/log:view`

Requirements
------------
- Moodle 3.1 (build 2016052300 or later)

Installation
------------
Copy the remote_courses folder into your /local directory and visit your Admin Notification page to complete the installation.

Author
------
Jose A. García Iváñez (jgivanez@diputacionalicante.es)
