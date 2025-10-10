# Laravel Chat-app

## Version 0.0.2 10-OCT-2025
En esta actualizacion se agregaron funcionalidades y se modificaron/migraron otras.

+ Añadi el editor de texto __CKEditor__ con el objetivo de permitir al usuario agregar cierto formato al texto/mensaje.
+ Añadi una funcion (showMessage.js), que muestra mesajes informativos al usuario, posiblemente se agregue alguna libreria para esto o se modifique la añadida.
+ Añadi varias funciones (icons.js), que retornar un svg para cuando se necesiten iconos de archivos.
+ Modifique algunos de los componentes __Blade__.
+ Modifique el __ChatController__.
+ Los __scripts__ los migre al archivo __message.js__.
+ Algunas de las funciones/helpers los migre a un archivo especifico __helpers.js__

## Version 0.0.1 06-OCT-2025
Primer commit de seguimiento, este commit sera la descripcion de los cambios hechos en el proyecto.

+ Por ahora los github actions los deshabilito ya que no se como funcionan, en futuro quizas los utilice al aprender como funcoinan.
+ Actulice algunas de la migraciones para aplicar relaciones y añadir otras columnas que se me ocurrio añadir, de ser necesario se iran actualizando.
+ Añadi un archivo js para las extenciones de archivos, aunque no hago uso de el esta ocacion en un furturo me resultara util.
+ Modifique un poco los componentes de mensaje para cambiar la __UI__ y añadi uno nuevo para mostrar archivos del mensaje.
+ El __ChatController__ tambien sufrio cambios.
  + Inclusion de la funcinalidad de cargar archivos.
  + Cambios en la funcion __conversacion__ para hacer uso de la relacion con la tabla __users__