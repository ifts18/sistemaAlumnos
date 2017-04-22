# sistemaAlumnos

Sistema del instituto para el manejo de la información relacionada a los
alumnos, materias y cursadas.

La estructura del proyecto es:

```
./direcciones.php                     # Menu de selección de opciones
./carga1.php                          # Mesas disponibles para inscripción
./EditFinales.php                     # Permite editar los finales a anotarse
./ABM_Modal/MateriasPorAlumnos.php    # Materias firmadas con puntaje y finales aprobados con puntaje
./Recuperar.php                       # Permite modifcar password
./ListarMesasFinales.php              # Permite visualizar cuantos alumnos hay anotados por mesa y poder generar los reportes por cada mesa
./Busquedaalumno.php    
./AltaUsuario.php                     # Permite dar de alta a un alumno
./alumnos_rep.php                     # Buqueda de alumnos por DNI, Apellido, año 
./AltaMesa1.php                       # Sirve para dar de alta una mesa
./ABM_Modal/Equivalencias.php         # Cargar equivalencias de alumnos
./ABM_Modal/Alumnos.php               # Alta y modificacion de usuarios alumnos
```

Docker
------

```
docker run --name ifts18-db -e MYSQL_ROOT_PASSWORD=terciario18 -p 3306:3306 -d mysql
```
