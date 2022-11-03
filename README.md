# proyecto

-primer paso a realizar para poder abrir el proyectoes el siguiente:

1-abrir la capeta donde esta ubicado el xampp, luego la carpeta apache. luego la carpeta conf,
luego la carpeta extra y por ultimo abre el archivo httpd-vhosts.mod en cualquier editor de texto
y agregamos las siguientes lineas de codigo 

<VirtualHost *:80>
    DocumentRoot "C:/xampp/htdocs/sistema-php/marketplace"
    ServerName marketplace.com
</VirtualHost>

<VirtualHost *:80>
    DocumentRoot "C:/xampp/htdocs/sistema-php/api-marketplace"
    ServerName api.marketplace.com
</VirtualHost>

<VirtualHost *:80>
    DocumentRoot "C:/xampp/htdocs/sistema-php/admin"
    ServerName adminsystem.com
</VirtualHost>

2-luego para habilitar los dominios virtuales vamos a abir las siguientes carpetas, abrimos la carpeta windows,
luego abrimos la carpeta de system32, luego abrimos la carpeta drivers, luego abrimos la carpeta etc y por ultimo 
abrimos el archivo hots en un editor de texto y ponemos el siguiente 

    127.0.0.1       marketplace.com
    127.0.0.1       api.marketplace.com
    127.0.0.1       adminsystem.com
 por ultimo guardamos los cambios realizados y procedemos a abir el proyectro
 
 3- el link para realizar la compra es marketplace.com
 -el link para loguearse con el rol administrador y ver el panel de control para ver todos los datos y poder eliminar,editar y agregar
 
 en caso de no lograr realizar la instalacion del proyecto localmente porfavor comunicarce a este nuemro 3194672644 para explicarle 
 el proceso mediante una videollamada o mostrarle en funcionamiento desde mi pc del inventario
 
 4- Los crendiaces de admin son:
 Usuario:
 -laura@gmail.com
 -administrador@gmail.com
 Clave:
 -laura
 -123456
 5- la pagina para entrar a administristrador es : http://adminsystem.com/categories
