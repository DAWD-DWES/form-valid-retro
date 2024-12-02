<?php
define('NOMBRE_INVALIDO', '**Nombre inválido');
define('DNI_INVALIDO', '**DNI inválido');
define('CLAVE_INVALIDA', '**Clave inválida');
define('CORREO_INVALIDO', '**Correo inválido');
define('TELEFONO_INVALIDO', '**Teléfono inválido');
define('EDAD_INVALIDA', '**Información de edad inválida');
define('FECHANAC_INVALIDA', '**Fecha de Nacimiento inválida');
define('IDIOMA_INVALIDO', '**Idioma inválido');
define('FOTO_INVALIDA', '**Foto inválida');
define("RUTA_IMAGENES", "imagenes");

if (filter_has_var(INPUT_POST, "enviar")) {
    $datos = [];
// Lectura, saneamiento y validación del dato de nombre
// 3 a 25 caracteres en mayúsculas y minúsculas y espacio en blanco
    $nombre = [];
    $nombre['form'] = filter_input(INPUT_POST, 'nombre', FILTER_UNSAFE_RAW);
    $nombre['san'] = filter_var(trim($nombre['form']), FILTER_SANITIZE_SPECIAL_CHARS);
    $nombre['err'] = filter_var($nombre['san'], FILTER_VALIDATE_REGEXP,
                    ['options' => ['regexp' => "/^[a-z A-Záéíóúñ]{3,25}$/"]]) === false;

    $datos['nombre'] = $nombre;

    // Lectura y validación del dato de DNI
    $dni = [];
    $dni['form'] = filter_input(INPUT_POST, 'dni', FILTER_UNSAFE_RAW);
    $dni['san'] = filter_var(trim($dni['form']), FILTER_SANITIZE_SPECIAL_CHARS);
    $dni['err'] = filter_var($dni['san'], FILTER_VALIDATE_REGEXP,
                    ['options' => ['regexp' => "/^[0-9]{1,8}[A-Z]$/"]]) === false ||
            (substr($dni['san'], -1) != substr("TRWAGMYFPDXBNJZSQVHLCKE", ((int) substr($dni['san'], 0, -1) % 23), 1));
    $datos['dni'] = $dni;

// Lectura, saneamiento y validación del dato de contraseña
// 6 a 8 caracteres con mayúsculas, minúsculas, digitos y los símbolos !@#$%^&*()+

    $clave = [];
    $clave['form'] = filter_input(INPUT_POST, 'clave', FILTER_UNSAFE_RAW);
    $clave['san'] = filter_var(trim($clave['form']), FILTER_SANITIZE_SPECIAL_CHARS);
    $clave['err'] = filter_var($clave['san'], FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => "/^[\w!@#\$%\^&\*\(\)\+]{6,8}$/"]]) === false;

    $datos['clave'] = $clave;
// Lectura, saneamiento y validación del dato de correo
// Formato correcto de correo

    $correo = [];
    $correo['form'] = filter_input(INPUT_POST, 'correo', FILTER_UNSAFE_RAW);
    $correo['san'] = filter_var(trim($correo['form']), FILTER_SANITIZE_EMAIL);
    $correo['err'] = filter_var($correo['san'], FILTER_VALIDATE_EMAIL) === false;

    $datos['correo'] = $correo;
// Lectura del dato de fecha. La fecha ya viene validada del formulario

    $fechaNac = [];
    $fechaNac['form'] = filter_input(INPUT_POST, 'fechanac', FILTER_UNSAFE_RAW);
    $fechaNac['err'] = empty($fechaNac['form']);

    $datos['fecha_nac'] = $fechaNac;

// Lectura, saneamiento y validación del dato de telefono
// Números sin blancos    

    $tel = [];
    $tel['form'] = filter_input(INPUT_POST, 'tel', FILTER_UNSAFE_RAW);
    $tel['san'] = filter_var(trim($tel['form']), FILTER_SANITIZE_NUMBER_INT);
    $tel['err'] = filter_var($tel['san'], FILTER_VALIDATE_REGEXP,
                    ['options' => ['regexp' => "/^\+?[0-9]{9,15}$/"]]) === false;

    $datos['tel'] = $tel;
// Lectura del dato de tienda
    $tienda = [];
    $tienda['form'] = filter_input(INPUT_POST, 'tienda');

    $datos['tienda'] = $tienda;
// Lectura, saneamiento y validación del dato de edad. Solo pueden acceder mayores de edad

    $edad = [];

    $edad['form'] = filter_input(INPUT_POST, 'edad', FILTER_UNSAFE_RAW);
    $edad['san'] = filter_var(trim($edad['form']), FILTER_SANITIZE_NUMBER_INT);
    $edad['err'] = filter_var($edad['san'], FILTER_VALIDATE_INT, [
                "options" => [
                    "min_range" => 18,
                    "max_range" => 120,
                ]
            ]) === false;

    $datos['edad'] = $edad;

// Lectura del dato de idioma.
    $idioma = [];

    $idioma['form'] = filter_input(INPUT_POST, 'idioma', FILTER_UNSAFE_RAW);
    $idioma['err'] = empty($idioma['form']);

    $datos['idioma'] = $idioma;

    // Lectura del dato de intereses
    $intereses = [];
    $intereses['form'] = implode(', ', filter_input(INPUT_POST, 'intereses', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY) ?? []);
    $datos['intereses'] = $intereses;

// Validación del dato de suscripcion. Se convierte la respuesta a un valor booleano
    $suscripcion = [];

    $suscripcion['form'] = filter_input(INPUT_POST, 'suscripcion', FILTER_VALIDATE_BOOLEAN) ?? false;
    $suscripcion['san'] = $suscripcion['form'] ? 'si' : 'no';

    $datos['suscripcion'] = $suscripcion;

    // Validación y carga de la imagen
    $foto = $_FILES['foto'];
    $foto['err'] = false;
// Verifica que el archivo sea de tipo JPEG o JPG
    if ($foto['error'] === UPLOAD_ERR_OK) {
        $foto['form'] = $foto['name'];
        $fileType = strtolower(pathinfo($foto['name'], PATHINFO_EXTENSION));
        $foto['err'] = !in_array($fileType, ['jpg', 'jpeg']);
    } else {
        $foto['err'] = true; // Error si hubo algún problema con la carga
    }

    $datos['foto'] = $foto;

    // Compruebo si se han producido errores
    // $formError = array_sum(array_column($datos, 'err')) > 0;

    $formError = false;
    foreach ($datos as $dato => $valores) {
        if ($valores['err'] ?? false) {
            $formError = true;
            break;
        }
    }

    // Si no se han producido errores subo descargo el fichero de la foto en la carpeta de imágenes

    if (!$formError) {
        $destino = RUTA_IMAGENES . "/" . $dni['san'] . ".$fileType";
        move_uploaded_file($foto['tmp_name'], $destino);
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Formulario de Registro</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width">
        <link rel="stylesheet" href="stylesheet.css">
    </head>
    <body>
        <?php if (!filter_has_var(INPUT_POST, "enviar") || $formError): ?> <!-- Si se solicita el formulario -->
            <div class="flex-page">
                <h1>Registro de cliente</h1>
                <form class="capaform" name="registerform" 
                      action="<?= $_SERVER['PHP_SELF'] ?>" method="POST" 
                      enctype="multipart/form-data" novalidate>
                    <div class="flex-outer">
                        <div class="form-section">
                            <label for="nombre">Nombre:</label>
                            <input id="nombre" type="text" name="nombre" placeholder="Introduce el nombre" 
                                   value="<?= $datos['nombre']['san'] ?? '' ?>">
                            <span class="error <?= ($datos['nombre']['err'] ?? false) ? 'error-visible' : '' ?>">
                                <?= NOMBRE_INVALIDO ?>
                            </span>                       
                        </div>
                        <div class="form-section">
                            <label for="dni">DNI:</label>
                            <input id="dni" type="text" name="dni" placeholder="Introduce el DNI (12345678A)" 
                                   value="<?= $datos['dni']['san'] ?? '' ?>">
                            <span class="error <?= ($datos['dni']['err'] ?? false) ? 'error-visible' : '' ?>">
                                <?= DNI_INVALIDO ?>
                            </span>                       
                        </div>
                        <div class="form-section">
                            <label for="clave">Clave:</label>
                            <input id="clave" type="password" name="clave" placeholder="Introduce la clave"
                                   value="<?= $datos['clave']['san'] ?? '' ?>">
                            <span class="error <?= ($datos['clave']['err'] ?? false) ? 'error-visible' : '' ?>">
                                <?= CLAVE_INVALIDA ?>
                            </span>
                        </div>
                        <div class="form-section">
                            <label for="correo">Correo:</label>
                            <input id="correo" type="email"  name="correo" placeholder="Introduce el correo"
                                   value="<?= $datos['correo']['san'] ?? '' ?>">
                            <span class="error <?= ($datos['correo']['err'] ?? false) ? 'error-visible' : '' ?>">
                                <?= CORREO_INVALIDO ?>
                            </span>
                        </div>
                        <div class="form-section">
                            <label for="telefono">Teléfono:</Label> 
                            <input id="telefono" type="tel" name="tel" placeholder="Introduce el teléfono"
                                   value="<?= $datos['tel']['san'] ?? '' ?>" />
                            <span class="error <?= ($datos['tel']['err'] ?? false) ? 'error-visible' : '' ?>">
                                <?= TELEFONO_INVALIDO ?>
                            </span>
                        </div>
                        <div class="form-section">
                            <label for="edad">Edad:</label> 
                            <input id="edad" type="number" name="edad" placeholder="Introduce tu edad"
                                   value="<?= $datos['edad']['san'] ?? '' ?>">
                            <span class="error <?= ($datos['edad']['err'] ?? false) ? 'error-visible' : '' ?>">
                                <?= EDAD_INVALIDA ?>
                            </span>
                        </div>
                        <div class="form-section">
                            <label for="fechanac">Fecha de nacimiento:</Label>
                            <input id="fechanac" type="date" name="fechanac"
                                   value="<?= ($datos['fecha_nac']['form']) ?? '' ?>">
                            <span class="error <?= ($datos['fecha_nac']['err'] ?? false) ? 'error-visible' : '' ?>">
                                <?= FECHANAC_INVALIDA ?>
                            </span>
                        </div>
                        <div class="form-section">
                            <label for="tienda">Tienda Preferida:</Label> 
                            <select id="tienda" name="tienda">
                                <option value="Madrid" <?= ($datos['tienda']['form'] ?? '') === 'Madrid' ? 'selected' : '' ?>>Madrid</option>
                                <option value="Barcelona" <?= ($datos['tienda']['form'] ?? '') === 'Barcelona' ? 'selected' : '' ?>>Barcelona</option>
                                <option value="Valencia" <?= ($datos['tienda']['form'] ?? '') === 'Valencia' ? 'selected' : '' ?>>Valencia</option>
                            </select> 
                        </div>
                        <div class="form-section">
                            <label>Idioma preferido:</label>
                            <div class="select-section">
                                <div>
                                    <input id="español" type="radio" name="idioma" value="español" 
                                           <?= ($datos['idioma']['form'] ?? '') === 'español' ? 'checked' : '' ?>> 
                                    <label for="español">Español</label>
                                </div>
                                <div>
                                    <input id="inglés" type="radio" name="idioma" value="inglés" 
                                           <?= ($datos['idioma']['form'] ?? '') === 'inglés' ? 'checked' : '' ?>> 
                                    <label for="inglés">Inglés</label>
                                </div>
                            </div>
                            <span class="error <?= ($datos['idioma']['err'] ?? false) ? 'error-visible' : '' ?>">
                                <?= IDIOMA_INVALIDO ?>
                            </span>
                        </div>
                        <div class="form-section">
                            <label>Intereses:</label>
                            <div class="select-section">
                                <div>
                                    <input id="deportes" type="checkbox" name="intereses[]" value="deportes" 
                                           <?= str_contains($datos['intereses']['form'] ?? '', 'deportes') ? 'checked' : '' ?>>
                                    <label for="deportes">Deportes</label>
                                </div>
                                <div>
                                    <input id="musica" type="checkbox" name="intereses[]" value="musica" 
                                           <?= str_contains($datos['intereses']['form'] ?? '', 'musica') ? 'checked' : '' ?>>
                                    <label for="musica">Música</label>
                                </div>
                                <div>
                                    <input id="lectura" type="checkbox" name="intereses[]" value="lectura" 
                                           <?= str_contains($datos['intereses']['form'] ?? '', 'lectura') ? 'checked' : '' ?>>
                                    <label for="lectura">Lectura</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-section">
                            <label for="suscripcion">Suscripción revista:</label>
                            <input id="suscripcion" type="checkbox"  name="suscripcion" 
                                   <?= ($datos['suscripcion']['form'] ?? '') === 'si' ? 'checked' : '' ?>> 
                        </div>
                        <div class="form-section">
                            <label for="foto">Foto:</label>
                            <input id="foto" type="file" name="foto" accept=".jpg, .jpeg" />
                            <span class="error <?= ($datos['foto']['err'] ?? false) ? 'error-visible' : '' ?>">
                                <?= FOTO_INVALIDA ?>
                            </span>
                        </div>
                        <div class="form-section">
                            <div class="submit-section">
                                <input class="submit" type="submit" 
                                       value="Enviar" name="enviar"> 
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        <?php else: ?> <!-- Si se solicita el resultado de validar los datos introducidos en el formulario -->
            <div class="summary-section">
                <h1>Datos del cliente</h1>
                <?php if (!$dni['err'] ?? false): ?>
                    <div class="foto-container">
                        <img src= "<?= $destino ?>" alt="Foto del usuario" class="foto-usuario">
                    </div>
                <?php endif ?>
                <table>
                    <tr>
                        <th>Campo</th>
                        <th>Valor formulario</th>
                        <th>Valor saneado entrada</th>
                        <th>Valor saneado salida</th>
                    </tr>
                    <?php foreach ($datos as $dato => $valores): ?>
                        <tr>
                            <td><?= $dato ?></td>
                            <td><?= $valores['form'] ?? '' ?></td>
                            <td><?= $valores['san'] ?? '' ?></td>
                            <td><?= htmlspecialchars($valores['form'] ?? '') ?></td>     
                        </tr>
                    <?php endforeach ?>
                </table>
                <div class="submit-section">
                    <a href="<?= $_SERVER['PHP_SELF'] ?>" class="submit">Volver al formulario</a>
                </div>
            </div>
        <?php endif ?>
    </body>
</html>