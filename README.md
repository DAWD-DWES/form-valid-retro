# form-valid-retro
Escribe un programa PHP que permita al usuario rellenar un formulario de registro con los datos de nombre, contraseña, fecha de nacimiento, telefono, tienda, edad y suscripción. 
Los datos introducidos deben de respetar ciertas reglas de validación que se comprobarán en el servidor con las funciones filter_input y filter_var. Las reglas son las siguientes:
1. El nombre nombre se compone de 3 a 25 caracteres con mayúsculas y minúsculas y espacios en blanco.
2. La contraseña contiene de 6 a 8 caracteres con mayúsculas, minúsculas, digitos y los símbolos !@#$%^&*()+
3. El correo electrónico ha de tener el formato correcto.
4. La fecha de nacimiento debe de tener el formato correcto. El elemento del formulario ya captura la edad en el formato correcto.
5. El teléfono ha de tener el formato correcto.
6. La tienda ya viene de manera correcta desde el formulario.
7. La edad debe tener un valor comprendido entre el 18 y el 120.
8. El idioma ya viene de manera correcta desde el formulario. El usuario debe escoger un valor.
9. La suscripción a la revista ya viene de manera correcta desde el formulario.

El script recibe los datos del formulario y los valida de acuerdo con las reglas de validez descritas anteriormente. En el caso en que 
algún dato no sea válido se mostrará de nuevo el formulario con una anotación cerca del campo que indique que el valor introducido no es correcto.
Cuando todos los valores son válidos el script responde con una página que muestra un mensaje de aceptación del formulario, por ejemplo, **formulario correcto**.
Si hay valores inválidos en el formulario, además de mostrar las anotaciones de los campos incorrectos, los campos aparecerán rellenos con los valores
introducidos por el usuario de manera que no tenga que volver a rellenar todos los valores de nuevo.

Para facilitar la realización del ejercicio se proporciona el formulario HTML con los campos de captura de datos.
Utiliza un array asociativo para recoger todos los datos que se van a mostrar en la tabla de la página resumen.
