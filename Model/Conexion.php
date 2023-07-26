<?php

class Conexion
{

    private static $instance = null;
    private $con;

    public function __construct()
    {
        // Conexión al servidor MySQL
        $this->con = new mysqli('localhost', 'root', '');

        // Verificar si hay errores en la conexión
        if ($this->con->connect_error) {
            die("Error de conexión: " . $this->con->connect_error);
        }

        // Nombre de la base de datos que deseas crear o utilizar
        $databaseName = 'test';

        // Crear la base de datos si no existe
        $createDbQuery = "CREATE DATABASE IF NOT EXISTS $databaseName";

        if ($this->con->query($createDbQuery) === TRUE) {
            // La base de datos se creó exitosamente o ya existía
            // Ahora, seleccionamos la base de datos recién creada o existente
            $this->con->select_db($databaseName);

            // Verificar si la base de datos ya contiene tablas
            $tablesQuery = "SHOW TABLES";
            $tablesResult = $this->con->query($tablesQuery);

            if ($tablesResult->num_rows === 0) {
                // Si no hay tablas, importar el archivo SQL
                $sqlFile = 'SQL/dump.sql';
                $sqlContent = file_get_contents($sqlFile);

                if ($this->con->multi_query($sqlContent) === TRUE) {
                    // Consumir completamente los resultados de cada consulta
                    do {
                        if ($result = $this->con->store_result()) {
                            $result->free();
                        }
                    } while ($this->con->more_results() && $this->con->next_result());
                } else {
                    // Error al importar el archivo SQL
                }
            } else {
                // Si ya hay tablas, no importar el archivo SQL
            }

            // Liberar el resultado de la consulta de las tablas
            $tablesResult->free();
        } else {
            // Error al crear la base de datos
            die("Error al crear la base de datos: " . $this->con->error);
        }
    }

    // Método estático para obtener la instancia única de la clase
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }


    public function getCandidates()
    {
        // Obtener todos los candidatos de la base de datos
        $query = $this->con->query('SELECT * FROM candidates');

        // Verificar si ocurrió un error durante la consulta
        if (!$query) {
            return false; // Retornar false en caso de error
        }

        $retorno = [];

        $i = 0;
        while ($fila = $query->fetch_assoc()) {
            $retorno[$i] = $fila;
            $i++;
        }

        return $retorno; // Retornar un array con los candidatos
    }

    public function getOptions()
    {
        // Obtener todos los candidatos de la base de datos
        $query = $this->con->query('SELECT * FROM options');

        // Verificar si ocurrió un error durante la consulta
        if (!$query) {
            return false; // Retornar false en caso de error
        }

        $retorno = [];

        $i = 0;
        while ($fila = $query->fetch_assoc()) {
            $retorno[$i] = $fila;
            $i++;
        }

        return $retorno; // Retornar un array con las opciones
    }

    public function getRegions()
    {
        // Obtener todas las regiones de la base de datos
        $query = $this->con->query('SELECT * FROM regions');

        // Verificar si ocurrió un error durante la consulta
        if (!$query) {
            return false; // Retornar false en caso de error
        }

        $retorno = [];

        $i = 0;
        while ($fila = $query->fetch_assoc()) {
            $retorno[$i] = $fila;
            $i++;
        }

        if (empty($retorno)) {
            return null; // Retornar null si no se obtuvo ninguna fila
        } else {
            return $retorno; // Retornar el array de filas si se obtuvieron resultados
        }
    }

    public function getCommunes()
    {
        // Obtener todas las comunas de la base de datos
        $query = $this->con->query('SELECT * FROM communes');

        // Verificar si ocurrió un error durante la consulta
        if (!$query) {
            return false; // Retornar false en caso de error
        }


        $retorno = [];

        $i = 0;
        while ($fila = $query->fetch_assoc()) {
            $retorno[$i] = $fila;
            $i++;
        }

        if (empty($retorno)) {
            return null; // Retornar null si no se obtuvo ninguna fila
        } else {
            return $retorno; // Retornar el array de filas si se obtuvieron resultados
        }
    }

    public function getCommunesByRegion($region_id)
    {
        // Escapar el valor de $region_id para evitar inyección de SQL
        $region_id = $this->con->real_escape_string($region_id);

        // Construir la consulta SQL con el valor de $region_id
        $query = "SELECT * FROM communes WHERE region_id = $region_id";

        // Ejecutar la consulta SQL
        $result = $this->con->query($query);

        // Verificar si hay errores en la consulta
        if (!$result) {
            die("Error en la consulta: " . $this->con->error);
        }

        // Inicializar un array para almacenar los datos de las comunas
        $communes = array();

        // Recorrer los resultados de la consulta y almacenarlos en el array $communes
        while ($row = $result->fetch_assoc()) {
            $communes[] = $row;
        }

        // Liberar el resultado de la consulta
        $result->free();

        // Retornar el array con los datos de las comunas
        return $communes;
    }

    function getRut()
    {
        // Obtener los ruts y dvs de la tabla registers
        $query = $this->con->query('SELECT rut, dv FROM registers');

        $retorno = [];

        $i = 0;
        while ($fila = $query->fetch_assoc()) {
            $retorno[$i] = $fila;
            $i++;
        }

        if (empty($retorno)) {
            return null; // Retornar null si no se obtuvo ninguna fila
        } else {
            return $retorno; // Retornar el array de filas si se obtuvieron resultados
        }
    }

    function insertRegions()
    {
        // contiene los datos de las regiones a insertar
        $regions = [
            [1, 'Arica y Parinacota', 'XV'],
            [2, 'Tarapacá', 'I'],
            [3, 'Antofagasta', 'II'],
            [4, 'Atacama', 'III'],
            [5, 'Coquimbo', 'IV'],
            [6, 'Valparaiso', 'V'],
            [7, 'Metropolitana de Santiago', 'RM'],
            [8, 'Libertador General Bernardo O\'Higgins', 'VI'],
            [9, 'Maule', 'VII'],
            [10, 'Ñuble', 'XVI'],
            [11, 'Biobío', 'VIII'],
            [12, 'La Araucanía', 'IX'],
            [13, 'Los Ríos', 'XIV'],
            [14, 'Los Lagos', 'X'],
            [15, 'Aisén del General Carlos Ibáñez del Campo', 'XI'],
            [16, 'Magallanes y de la Antártica Chilena', 'XII']
        ];

        $insertedRegions = [];
        foreach ($regions as $region) {
            $insertedRegions[] = [
                'id' => $region[0],
                'order_number' => $region[0],
                'name' => mysqli_real_escape_string($this->con, $region[1]), // corregir el nombre de la región
                'ordinal_symbol' => $region[2],
            ];
        }

        // se construye un array de valores $values que contiene las filas a insertar en la tabla
        $values = [];
        foreach ($insertedRegions as $region) {
            $values[] = "(" . $region['id'] . ", " . $region['order_number'] . ", '" . $region['name'] . "', '" . $region['ordinal_symbol'] . "')";
        }

        // se define la consulta SQL para insertar las regiones
        $sql = "INSERT INTO regions (id, order_number, name, ordinal_symbol) VALUES " . implode(",", $values);
        // se ejecuta la consulta SQL para insertar las regiones
        if ($this->con->query($sql) === TRUE) {
            // Mostrar alerta en el navegador con el mensaje
            echo "<script>alert('Regiones insertadas correctamente');</script>";
        } else {
            echo "<script>alert('Error al insertar las regiones');</script>";
        }

        // $this->con->close();
    }

    function insertCommunes()
    {
        // contiene los datos de las comunas a insertar
        $communes = [
            ['Arica', 1],
            ['Camarones', 1],
            ['General Lagos', 1],
            ['Putre', 1],
            ['Alto Hospicio', 2],
            ['Iquique', 2],
            ['Camiña', 2],
            ['Colchane', 2],
            ['Huara', 2],
            ['Pica', 2],
            ['Pozo Almonte', 2],
            ['Antofagasta', 3],
            ['Mejillones', 3],
            ['Sierra Gorda', 3],
            ['Taltal', 3],
            ['Calama', 3],
            ['Ollague', 3],
            ['San Pedro de Atacama', 3],
            ['María Elena', 3],
            ['Tocopilla', 3],
            ['Chañaral', 4],
            ['Diego de Almagro', 4],
            ['Caldera', 4],
            ['Copiapó', 4],
            ['Tierra Amarilla', 4],
            ['Alto del Carmen', 4],
            ['Freirina', 4],
            ['Huasco', 4],
            ['Vallenar', 4],
            ['Canela', 5],
            ['Illapel', 5],
            ['Los Vilos', 5],
            ['Salamanca', 5],
            ['Andacollo', 5],
            ['Coquimbo', 5],
            ['La Higuera', 5],
            ['La Serena', 5],
            ['Paihuaco', 5],
            ['Vicuña', 5],
            ['Combarbalá', 5],
            ['Monte Patria', 5],
            ['Ovalle', 5],
            ['Punitaqui', 5],
            ['Río Hurtado', 5],
            ['Isla de Pascua', 6],
            ['Calle Larga', 6],
            ['Los Andes', 6],
            ['Rinconada', 6],
            ['San Esteban', 6],
            ['La Ligua', 6],
            ['Papudo', 6],
            ['Petorca', 6],
            ['Zapallar', 6],
            ['Hijuelas', 6],
            ['La Calera', 6],
            ['La Cruz', 6],
            ['Limache', 6],
            ['Nogales', 6],
            ['Olmué', 6],
            ['Quillota', 6],
            ['Algarrobo', 6],
            ['Cartagena', 6],
            ['El Quisco', 6],
            ['El Tabo', 6],
            ['San Antonio', 6],
            ['Santo Domingo', 6],
            ['Catemu', 6],
            ['Llaillay', 6],
            ['Panquehue', 6],
            ['Putaendo', 6],
            ['San Felipe', 6],
            ['Santa María', 6],
            ['Casablanca', 6],
            ['Concón', 6],
            ['Juan Fernández', 6],
            ['Puchuncaví', 6],
            ['Quilpué', 6],
            ['Quintero', 6],
            ['Valparaíso', 6],
            ['Villa Alemana', 6],
            ['Viña del Mar', 6],
            ['Colina', 7],
            ['Lampa', 7],
            ['Tiltil', 7],
            ['Pirque', 7],
            ['Puente Alto', 7],
            ['San José de Maipo', 7],
            ['Buin', 7],
            ['Calera de Tango', 7],
            ['Paine', 7],
            ['San Bernardo', 7],
            ['Alhué', 7],
            ['Curacaví', 7],
            ['María Pinto', 7],
            ['Melipilla', 7],
            ['San Pedro', 7],
            ['Cerrillos', 7],
            ['Cerro Navia', 7],
            ['Conchalí', 7],
            ['El Bosque', 7],
            ['Estación Central', 7],
            ['Huechuraba', 7],
            ['Independencia', 7],
            ['La Cisterna', 7],
            ['La Granja', 7],
            ['La Florida', 7],
            ['La Pintana', 7],
            ['La Reina', 7],
            ['Las Condes', 7],
            ['Lo Barnechea', 7],
            ['Lo Espejo', 7],
            ['Lo Prado', 7],
            ['Macul', 7],
            ['Maipú', 7],
            ['Ñuñoa', 7],
            ['Pedro Aguirre Cerda', 7],
            ['Peñalolén', 7],
            ['Providencia', 7],
            ['Pudahuel', 7],
            ['Quilicura', 7],
            ['Quinta Normal', 7],
            ['Recoleta', 7],
            ['Renca', 7],
            ['San Miguel', 7],
            ['San Joaquín', 7],
            ['San Ramón', 7],
            ['Santiago', 7],
            ['Vitacura', 7],
            ['El Monte', 7],
            ['Isla de Maipo', 7],
            ['Padre Hurtado', 7],
            ['Peñaflor', 7],
            ['Talagante', 7],
            ['Codegua', 8],
            ['Coínco', 8],
            ['Coltauco', 8],
            ['Doñihue', 8],
            ['Graneros', 8],
            ['Las Cabras', 8],
            ['Machalí', 8],
            ['Malloa', 8],
            ['Mostazal', 8],
            ['Olivar', 8],
            ['Peumo', 8],
            ['Pichidegua', 8],
            ['Quinta de Tilcoco', 8],
            ['Rancagua', 8],
            ['Rengo', 8],
            ['Requínoa', 8],
            ['San Vicente de Tagua Tagua', 8],
            ['La Estrella', 8],
            ['Litueche', 8],
            ['Marchihue', 8],
            ['Navidad', 8],
            ['Peredones', 8],
            ['Pichilemu', 8],
            ['Chépica', 8],
            ['Chimbarongo', 8],
            ['Lolol', 8],
            ['Nancagua', 8],
            ['Palmilla', 8],
            ['Peralillo', 8],
            ['Placilla', 8],
            ['Pumanque', 8],
            ['San Fernando', 8],
            ['Santa Cruz', 8],
            ['Cauquenes', 9],
            ['Chanco', 9],
            ['Pelluhue', 9],
            ['Curicó', 9],
            ['Hualañé', 9],
            ['Licantén', 9],
            ['Molina', 9],
            ['Rauco', 9],
            ['Romeral', 9],
            ['Sagrada Familia', 9],
            ['Teno', 9],
            ['Vichuquén', 9],
            ['Colbún', 9],
            ['Linares', 9],
            ['Longaví', 9],
            ['Parral', 9],
            ['Retiro', 9],
            ['San Javier', 9],
            ['Villa Alegre', 9],
            ['Yerbas Buenas', 9],
            ['Constitución', 9],
            ['Curepto', 9],
            ['Empedrado', 9],
            ['Maule', 9],
            ['Pelarco', 9],
            ['Pencahue', 9],
            ['Río Claro', 9],
            ['San Clemente', 9],
            ['San Rafael', 9],
            ['Talca', 9],
            ['Bulnes', 10],
            ['Chillán', 10],
            ['Chillán Viejo', 10],
            ['Cobquecura', 10],
            ['Coelemu', 10],
            ['Coihueco', 10],
            ['El Carmen', 10],
            ['Ninhue', 10],
            ['Ñiquen', 10],
            ['Pemuco', 10],
            ['Pinto', 10],
            ['Portezuelo', 10],
            ['Quirihue', 10],
            ['Ránquil', 10],
            ['Treguaco', 10],
            ['Quillón', 10],
            ['San Carlos', 10],
            ['San Fabián', 10],
            ['San Ignacio', 10],
            ['San Nicolás', 10],
            ['Yungay', 10],
            ['Arauco', 11],
            ['Cañete', 11],
            ['Contulmo', 11],
            ['Curanilahue', 11],
            ['Lebu', 11],
            ['Los Álamos', 11],
            ['Tirúa', 11],
            ['Alto Biobío', 11],
            ['Antuco', 11],
            ['Cabrero', 11],
            ['Laja', 11],
            ['Los Ángeles', 11],
            ['Mulchén', 11],
            ['Nacimiento', 11],
            ['Negrete', 11],
            ['Quilaco', 11],
            ['Quilleco', 11],
            ['San Rosendo', 11],
            ['Santa Bárbara', 11],
            ['Tucapel', 11],
            ['Yumbel', 11],
            ['Chiguayante', 11],
            ['Concepción', 11],
            ['Coronel', 11],
            ['Florida', 11],
            ['Hualpén', 11],
            ['Hualqui', 11],
            ['Lota', 11],
            ['Penco', 11],
            ['San Pedro de La Paz', 11],
            ['Santa Juana', 11],
            ['Talcahuano', 11],
            ['Tomé', 11],
            ['Carahue', 12],
            ['Cholchol', 12],
            ['Cunco', 12],
            ['Curarrehue', 12],
            ['Freire', 12],
            ['Galvarino', 12],
            ['Gorbea', 12],
            ['Lautaro', 12],
            ['Loncoche', 12],
            ['Melipeuco', 12],
            ['Nueva Imperial', 12],
            ['Padre Las Casas', 12],
            ['Perquenco', 12],
            ['Pitrufquén', 12],
            ['Pucón', 12],
            ['Saavedra', 12],
            ['Temuco', 12],
            ['Teodoro Schmidt', 12],
            ['Toltén', 12],
            ['Vilcún', 12],
            ['Villarrica', 12],
            ['Angol', 12],
            ['Collipulli', 12],
            ['Curacautín', 12],
            ['Ercilla', 12],
            ['Lonquimay', 12],
            ['Los Sauces', 12],
            ['Lumaco', 12],
            ['Purén', 12],
            ['Renaico', 12],
            ['Traiguén', 12],
            ['Victoria', 12],
            ['Corral', 13],
            ['Lanco', 13],
            ['Los Lagos', 13],
            ['Máfil', 13],
            ['Mariquina', 13],
            ['Paillaco', 13],
            ['Panguipulli', 13],
            ['Valdivia', 13],
            ['Futrono', 13],
            ['La Unión', 13],
            ['Lago Ranco', 13],
            ['Río Bueno', 13],
            ['Ancud', 14],
            ['Castro', 14],
            ['Chonchi', 14],
            ['Curaco de Vélez', 14],
            ['Dalcahue', 14],
            ['Puqueldón', 14],
            ['Queilén', 14],
            ['Quemchi', 14],
            ['Quellón', 14],
            ['Quinchao', 14],
            ['Calbuco', 14],
            ['Cochamó', 14],
            ['Fresia', 14],
            ['Frutillar', 14],
            ['Llanquihue', 14],
            ['Los Muermos', 14],
            ['Maullín', 14],
            ['Puerto Montt', 14],
            ['Puerto Varas', 14],
            ['Osorno', 14],
            ['Puero Octay', 14],
            ['Purranque', 14],
            ['Puyehue', 14],
            ['Río Negro', 14],
            ['San Juan de la Costa', 14],
            ['San Pablo', 14],
            ['Chaitén', 14],
            ['Futaleufú', 14],
            ['Hualaihué', 14],
            ['Palena', 14],
            ['Aisén', 15],
            ['Cisnes', 15],
            ['Guaitecas', 15],
            ['Cochrane', 15],
            ['O\'higgins', 15],
            ['Tortel', 15],
            ['Coihaique', 15],
            ['Lago Verde', 15],
            ['Chile Chico', 15],
            ['Río Ibáñez', 15],
            ['Antártica', 16],
            ['Cabo de Hornos', 16],
            ['Laguna Blanca', 16],
            ['Punta Arenas', 16],
            ['Río Verde', 16],
            ['San Gregorio', 16],
            ['Porvenir', 16],
            ['Primavera', 16],
            ['Timaukel', 16],
            ['Natales', 16],
            ['Torres del Paine', 16],
            ['Cabildo', 6],
        ];

        $insertedCommunes = [];
        foreach ($communes as $commune) {
            $name = mysqli_real_escape_string($this->con, $commune[0]);
            $region_id = $commune[1];
            $insertedCommunes[] = [
                'name' => $name,
                'region_id' => $region_id,
            ];
        }

        // se construye un array de valores $values que contiene las filas a insertar en la tabla
        $values = [];
        foreach ($insertedCommunes as $commune) {
            $name = $commune['name'];
            $region_id = $commune['region_id'];
            $values[] = "('" . $name . "', '" . $region_id . "')";
        }

        // se define la consulta SQL para insertar las comunas
        $sql = "INSERT INTO communes (name, region_id) VALUES " . implode(",", $values);
        // se ejecuta la consulta SQL para insertar las comunas
        if ($this->con->query($sql) === TRUE) {
            // Mostrar alerta en el navegador con el mensaje
            echo "<script>alert('Comunas insertadas correctamente');</script>";
        } else {
            echo "<script>alert('Error al insertar las comunas');</script>";
        }

        // $this->con->close();
    }

    function insertCandidates()
    {
        // contiene los datos de los candidatos presidenciales a insertar
        $candidates = [
            ['George Washington'],
            ['John Adams'],
            ['Thomas Jefferson'],
            ['James Madison'],
            ['James Monroe']
        ];

        $insertedCandidates = [];
        foreach ($candidates as $candidate) {
            $insertedCandidates[] = [
                'name' => mysqli_real_escape_string($this->con, $candidate[0]),
            ];
        }

        // se construye un array de valores $values que contiene las filas a insertar en la tabla
        $values = [];
        foreach ($insertedCandidates as $candidate) {
            $values[] = "('" . $candidate['name'] . "')";
            // Agregar comillas simples alrededor del nombre del candidato para que la consulta sea válida.
        }

        // se define la consulta SQL para insertar los candidatos
        $sql = "INSERT INTO candidates (name) VALUES " . implode(",", $values);
        // se ejecuta la consulta SQL para insertar los candidatos
        if ($this->con->query($sql) === TRUE) {
            // Mostrar alerta en el navegador con el mensaje
            echo "<script>alert('Candidatos insertados correctamente');</script>";
        } else {
            echo "<script>alert('Error al insertar los candidatos');</script>";
        }

        // $this->con->close();
    }

    function insertOptions()
    {
        $options = [
            [1, 'Web'],
            [2, 'TV'],
            [3, 'Redes Sociales'],
            [4, 'Amigo']
        ];

        $insertedOptions = [];
        foreach ($options as $option) {
            $insertedOptions[] = [
                'id' => $option[0],
                'name' => mysqli_real_escape_string($this->con, $option[1]),
            ];
        }

        // se construye un array de valores $values que contiene las filas a insertar en la tabla
        $values = [];
        foreach ($insertedOptions as $option) {
            $values[] = "('" . $option['id'] . "', '" . $option['name'] . "')";
            // Agregar coma entre el id y el nombre del candidato
        }

        // se define la consulta SQL para insertar los candidatos
        $sql = "INSERT INTO options (id, name) VALUES " . implode(",", $values);
        // se ejecuta la consulta SQL para insertar los candidatos
        if ($this->con->query($sql) === TRUE) {
            // Mostrar alerta en el navegador con el mensaje
            echo "<script>alert('Opciones insertadas correctamente');</script>";
        } else {
            echo "<script>alert('Error al insertar las opciones');</script>";
        }
    }


    function verificarExistenciaRut($rut)
    {
        // se define la consulta SQL para obtener los rut de la bd
        $sql = "SELECT COUNT(*) AS count FROM registers WHERE rut = ?";

        $stmt = $this->con->prepare($sql);
        $stmt->bind_param("s", $rut);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();

        return $count > 0; // Devuelve true si el RUT existe, false si no existe
    }

    function guardarVoto($values, $opciones)
    {
        $rut = $values[2]; // Obtener el valor del RUT
        $exists = $this->verificarExistenciaRut($rut); // Verificar si el RUT ya existe

        if ($exists) {
            echo "Error: El RUT ya existe en la base de datos." . "<br><br>";
            return; // Terminar la función si el RUT ya existe
        }

        $sql = "INSERT INTO registers (nombre_apellido, alias, rut, dv, email, region_id, comuna_id, candidato_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->con->prepare($sql);
        $stmt->bind_param("ssssssii", ...$values);

        if ($stmt->execute()) {
            $registro_id = $stmt->insert_id; // Obtener el ID del registro creado

            foreach ($opciones as $opcion) {
                $sql_opcion = "INSERT INTO registro_has_option (registro_id, opcion_id) VALUES (?, ?)";

                $stmt_opcion = $this->con->prepare($sql_opcion);
                $stmt_opcion->bind_param("ii", $registro_id, $opcion);
                $stmt_opcion->execute();
            }
            echo "Votación creada correctamente. ID del registro: " . $registro_id . "<br>";
        } else {
            echo "Error al crear Votación: " . $stmt->error;
        }
    }
}
