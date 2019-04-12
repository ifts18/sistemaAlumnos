# sistemaAlumnos

Sistema del instituto para el manejo de la información relacionada a los
alumnos, materias y cursadas.

La estructura del proyecto se encuentra en:

```
https://docs.google.com/spreadsheets/d/1YOR5hKvphPS1UZT8Jj4wP9JYvNNx5fkkjRCh1fBY9ok/edit?usp=sharing
```

## Configuración

Para correr el sistema es necesario tener instalado:
```
*Apache instalado (MAMPP, LAMPP, XAMPP, otro) v 7.2.5 o mayor
*MySQL
*PHP v5 o más

```

### :one: Repositorio

Descarga el repositorio en la carpeta ```htdocs``` o la carpeta donde apunte el servidor web local.
Algunos apuntes sobre la configuración: ```https://docs.google.com/document/d/1FqsUC6L2Vn4Lhi_NlyvaWLL6oTSfc3VraVlWVaNEro8/edit?usp=sharing```

**Trabajaremos siempre utilizando como base la branch develop**

Cada tarea nueva será implementada en una branch nueva llamada ```feature/``` + nombre de la tarea nueva a implementar, por ejemplo ```feature/ModificarNotaActa```

Una vez que el trabajo está terminado es importante que lo revise otro compañere para evitar bugs o fallos que pueden haber sido pasados por alto por el desarrollador. Para ello crearemos un PULL REQUEST y asignaremo el mismo a quien creamos correspondiente. Luego esa persona debera probar y dar el ok al pull request para que pueda ser mergeado con DEVELOP. Es importante chequear que lo que estamos por subir a develop sea realmente los cambios que queremos.  

**Tareas** 
Las tareas se ven en la solapa Projects en github. Cada proyecto es un board. Dentro del board se encuentran las tareas a implementar. Las etapas de una tarea son To-Do(definida, lista para ser implemetada), In progress(en proceso de implementacion), Code Review(finalizada, lista para ser evaluada por un compañere) y Done(finalizada, aprobada y mergeada a develop).

*es importante mantener actualizado el board*

### :two: Buenas Practicas

-Tratar de documentar las funciones (pensá en el que viene después que vos :pray:)
-Evita hardcodeos
-Usa nombres de variables y funciones acordes

### :three: Base De datos

La base de datos deberá ser importada localmente con el mismo nombre que indica el archivo de conección y dar de alta el usuario con privilegios globales.

Cuando necesitemos modificar la base de datos de producción debemos primero hacer un back-up de la misma. 

### :four: Correr con docker
1. Instalar docker
2. Ejecutarlo `docker-compose up`
3. La BD ya va a estar creada con el usuario del sistema, solo resta importarla y darle permisos. El servidor de bd esta en `localhost:8083`
4. Ingresar a `http://localhost:8081`


## :sos: Dudas?

Si Google no puede responderlo preguntale a tus compañeres :smiley:
