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

##Configuración

Para correr el sistema es necesario tener instalado:
```
*Apache instalado (MAMPP, LAMPP, XAMPP, otro)
*MySQL
*PHP v5 o más

```

###:one: Repositorio

Descarga el repositorio en la carpeta ```htdocs``` o la carpeta donde apunte el servidor web local.
*Trabajaremos siempre utilizando como base la branch develop*

Cada tarea nueva será implementada en una branch nueva llamada ```feature/``` + nombre de la tarea nueva a implementar, por ejemplo ```feature/ModificarNotaActa```

Las tareas se ven en la solapa Projects en github. Cada proyecto es un board. Dentro del board se encuentran las tareas a implementar. Las etapas de una tarea son To-Do(definida, lista para ser implemetada), In progress(en proceso de implementacion), Code Review(finalizada, lista para ser evaluada por un compañere) y Done(finalizada, aprobada y mergeada a develop).

*es importante mantener actualizado el board*

###:two: Buenas Practicas

Previo a 2018 el codigo del sistema es "spaghetti":spaghetti:, codigo legacy sin documentación. A partir del 2018 nos enfocaremos en mantenerlo lo más claro y reusable posible. Esto se hará en etapas.

-Documentación: Cada funcion/query/objeto creado deberá indicar su proposito de uso. Si existe alguna particularidad en el codigo/ bug conocido o simil deberá incluirse descripción.  

-Estilos: Se empezará a migrar los estilos a hojas externas, evitando a toda costa los estilos inline. Los estilos deberán usar nombres de clases acordes. En el futuro se implementará un linter para mantener el css dentro de los estandares de CSS de Google.

-HTML: Se migrará progresivamente a HTML5. En el futuro se implementará un linter para mantener el css dentro de los estandares de HTML de Google.

-JS: Deberá ser examinado. Debe estar en archivo separado no inline. En el futuro se implementará un linter para mantener el css dentro de los estandares de JS de Google.

-PHP: Se migrará a usar templating para reducir la repetición de código.



###:three: Base De datos

La base de datos deberá ser importada localmente con el mismo nombre que indica el archivo de conección y dar de alta el usuario con privilegios globales.

##:sos: Dudas?

Si Google no puede responderlo preguntale a tus compañeres ;)
