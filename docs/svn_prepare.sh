#No incluto los archivos core.php y databases.php
svn propset svn:ignore "core.php
database.php
svn-commit*" ../app/config

#Ignoro el directorio tmp
svn propset svn:ignore "*" ../app/tmp -R 
svn propset svn:ignore "*" ../tmp -R 

#Ignoro el contenido de las versiones packed de css y js 
svn propset svn:ignore "*" ../app/webroot/css/packed
svn propset svn:ignore "*" ../app/webroot/js/packed

#Ignoro los archivos del proyecto quanta
svn propset svn:ignore "pragtico.webprj
pragtico.session" ../

#Ignoro los archivos de plantillas del proyecto quanta
svn propset svn:ignore ".dirinfo
.tmpl" ./plantillas

#Ignoro los archivos de manuales propios del desarrollador
svn propset svn:ignore "*" ./developer_manuals

#Ignoro los archivos de data propios del desarrollador
svn propset svn:ignore "*" ./developer_data

#Agrego como repositorio externo a la rama 1.2 de cakePHP
svn propset svn:externals "cake https://svn.cakephp.org/repo/branches/1.2.x.x/cake" ../

#Le pongo los datos de cada revision dentro del archivo
svn propset svn:keywords "Revision LastChangedBy Date" ../app/app_*.php
svn propset svn:keywords "Revision LastChangedBy Date" ../app/controllers/*.php
svn propset svn:keywords "Revision LastChangedBy Date" ../app/controllers/components/*.php
svn propset svn:keywords "Revision LastChangedBy Date" ../app/models/*.php
svn propset svn:keywords "Revision LastChangedBy Date" ../app/models/behaviors/*.php
find ../app/views/elements/ -type f -name *.php|xargs svn propset svn:keywords "Revision LastChangedBy Date"
find ../app/tests/ -type f -name *.php|xargs svn propset svn:keywords "Revision LastChangedBy Date"
svn propset svn:keywords "Revision LastChangedBy Date" ./plantillas/*
