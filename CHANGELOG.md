##  2.2 (mayo, 2021)
### Quitamos el ShortCode y los pasamos al nuevo plugins
* Refactor de funciones cambiando el shortcode a nuevo plugin.

##  2.1 (mayo, 2021)
###  Corrección de listado en perfiles
* El funcionamiento del filtro por perfil del usuario no era el correcto. Los resultados que se obtenían en algunos casos eran correctos pero en otros no.
El retorno del valor de ponente era un Objecto.

```
//$profileUserID es el ID del perfil que se está visitando.

$args = array(
	'post_type' => 'sesiones',
	'meta_query' => array(
		array(
			'key' => 'ponente',
			'value' => $profileUserID,
			'compare' => 'REGEXP'
		)
	)
);

```
* Agregamos a la vista los avatares y link del usuario que fue agregado como ponente o participante de la sesión.
* Cambio en formato de fechas.

##  2.0 (Marzo, 2021)
###  Paginación de sesiones
* Agregamos paginación al listado de sesiones.