<!DOCTYPE html>
<meta charset="UTF-8">
<html>

<head>
    <title>FORMULARIO DE VOTACIÓN:</title>
    <link rel="stylesheet" href="styles.css">
    <script src="script.js"></script>
</head>

<body>
    <h1>FORMULARIO DE VOTACIÓN:</h1>
    <form method="POST">
        <?php
            include "Controller/C_store.php";
            // Se incluye el archivo "C_store.php" para procesar los datos enviados desde el formulario
        ?>
        <div class="form-group">
            <label for="nombre">Nombre y Apellido:</label>
            <input type="text" id="nombre" name="nombre" required><br><br>
        </div>
        <div class="form-group">
            <label for="alias">Alias:</label>
            <input type="text" id="alias" name="alias" required><br><br>
        </div>
        <div class="form-group">
            <label for="rut">RUT:</label>
            <input type="text" id="rut" name="rut" required><br><br>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required><br><br>
        </div>
        <label for="region">Región:</label>
        <select id="region" name="region" required>
            <option value="" selected disabled> -- Seleccione una opción -- </option>
            <?php
            // Se genera dinámicamente las opciones para la selección de regiones a partir de un arreglo llamado $regiones
            foreach ($regiones as $region) {
                echo "<option value=\"" . $region['id'] . "\">" . $region['name'] . "</option>";
            }
            ?>
        </select><br><br>

        <label for="comuna">Comuna:</label>
        <select id="comuna" name="comuna" required>
            <option value="" selected disabled> -- Seleccione una opción -- </option>
        </select><br><br>

        <label for="candidato">Candidato:</label>
        <select id="candidato" name="candidato" required>
            <option value="" selected disabled> -- Seleccione una opción -- </option>
            <?php
            // Se genera dinámicamente las opciones para la selección de candidatos a partir de un arreglo llamado $candidatos
            foreach ($candidatos as $candidato) {
                echo "<option value=\"" . $candidato['id'] . "\">" . $candidato['name'] . "</option>";
            }
            ?>
        </select><br><br>

        <table>
            <tr>
                <td>
                    <label>¿Cómo se enteró de nosotros?</label><br>
                </td>
                <td>
                    <!-- Opciones de selección múltiple para la pregunta "¿Cómo se enteró de nosotros?" -->
                    <input type="checkbox" id="web" name="opcion_1" value="1">
                    <span for="web">Web</span>
                    <input type="checkbox" id="tv" name="opcion_2" value="2">
                    <span for="tv">TV</span>
                    <input type="checkbox" id="redes" name="opcion_3" value="3">
                    <span for="redes">Redes Sociales</span>
                    <input type="checkbox" id="amigo" name="opcion_4" value="4">
                    <span for="amigo">Amigo</span>
                </td>
            </tr>
        </table>
        <!-- Botón para enviar el formulario -->
        <input type="submit" name="btn_registro" value="Votar" style="margin-top: 2rem;">
    </form>
</body>

</html>