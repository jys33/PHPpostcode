Postcode Crud App
==================
La aplicación es un ejemplo de registro, actualización, eliminación y lectura de datos (las cuatro funciones básicas de persistencia de Bases de Datos). Se utiliza ajax para cargar el select de localidades en el formulario de registro, para lo cual se tienen archivos .json que contienen las localidades de cada provincia extraidos de la página del [Correo Argentino](https://www.correoargentino.com.ar/formularios/cpa), así como también la base de datos con todas las localidades por provincia de la República Argentina.

Para poder ejecutar la App
==================
1. Ejecutar el archivo db.sql
2. Registrar usuarios
3. Probar la App

PD:
Dentro de la carpeta json, se encuetra el archivo help.php, el cuál se utilizó para insertar en la base de datos las localidades de acuerdo a las provincias, uno por uno se fue leyendo cada archivo .json según la provincia y se fue cambiando la fk para que haga referencia al id en la tabla provincia.

DataTables Responsive
https://datatables.net/extensions/responsive/examples/display-types/bootstrap4-modal.html
