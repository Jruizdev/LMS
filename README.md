## Descripción General del LMS

El Sistema de Gestión del Aprendizaje (LMS) es una plataforma web diseñada para gestionar el ciclo de vida de la formación interna y externa de los empleados. Facilita la creación de cursos, la asignación de tareas, el seguimiento del progreso y la certificación. El sistema está desarrollado con un backend en PHP y un frontend basado en componentes mediante Bootstrap 5.3.3.

## Arquitectura del Sistema

La aplicación sigue una arquitectura modular de PHP donde la lógica de negocio se encapsula en el modulos/directorio. Utiliza un enfoque de dos bases de datos para separar los datos centrales de las entidades del contenido específico de los cursos.

## Áreas funcionales principales

### 1. Entrada y navegación de la aplicación

El sistema utiliza index.phpcomo puerta de enlace de autenticación principal. Tras iniciar sesión correctamente, los usuarios son redirigidos a inicio.php, que funciona como un panel personalizado que muestra la actividad reciente, los cursos pendientes y las estadísticas.

### 2. Roles de usuario y seguridad

El LMS admite tres roles principales: Empleado , Instructor y Administrador . El control de acceso se aplica mediante mecanismos de validación de sesión.
* **Autenticación:** Gestionada medianteValidarSesion() y ValidarAdmin().
* **Gestión de sesiones:** puntos de entrada como; catalogo_cursos.php y cursos_internos.php.

### 3. Gestión de los cursos

El sistema distingue entre Cursos Internos (CI) y Cursos Externos (CE).

| **Característica** | **Cursos Internos (CI)** | **Cursos Externos (CE)** |
|----------------|----------------------|----------------------|
| **Fuente** | Creado internamente mediante editor.php | Registrado por proveedores externos |
| **Lógica** | modulos/CursosInternos.php | modulos/CursosExternos.php |
| **Vista de administrador** | cursos_internos.php | cursos_externos.php |
| **Seguimiento** | Control de versiones y visores interactivos | Validez (Vigencia) y certificados |

### 4. Seguimiento e Informes

Los administradores e instructores pueden supervisar el progreso a través de vistas específicas:
* **Cursos pendientes:** usuarios_cursos_pendientes.php proporciona una visión general de los empleados que no han finalizado la formación asignada.
* **Perfiles de usuario:** consultar_pendientes.php permite consultar el historial de tareas pendientes de empleados específicos.
* **Cursos aprobados:** cursos_aprobados.php lista de la formación completada con sus respectivas puntuaciones.

## Arquitectura de frontend

El frontend utiliza un sistema de componentes de datos personalizado . En lugar de escribir HTML repetitivo, los archivos PHP definen contenedores con data-componenteatributos, que js/plantillas.js se hidratan mediante <template>etiquetas.


