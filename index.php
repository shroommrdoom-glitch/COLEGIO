<?php
include_once("conexion.php");

$conexion_exitosa = false;
$alumnos = [];
$total_registros = 0;

if ($conn) {
    $conexion_exitosa = true;

    try {
        // Consulta para obtener los 100 primeros registros
        $stmt = $conn->query("SELECT id, nombre, apellido, correo, telefono, fecha_nacimiento, ciudad, promedio FROM personas ORDER BY id DESC LIMIT 100");
        $alumnos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $total_registros = count($alumnos);
    } catch (PDOException $e) {
        echo "Error al consultar la base de datos: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gestión - Colegio</title>
    <link rel="icon" href="logo27.jpg">
    <style>
        /* Fondo general */
        body {
            margin: 0;
            font-family: 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(to bottom right, #ffffff, #d6e9ff);
            color: #333;
            min-height: 100vh;
        }

        /* Contenedor principal */
        .container {
            width: 95%;
            max-width: 1400px;
            min-height: 95vh;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            margin: 2.5vh auto;
            overflow: hidden;
        }

        /* Encabezado */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #0a4ea3;
            color: white;
            padding: 15px 40px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.2);
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo-img {
            width: 80px;
            height: 80px;
            object-fit: contain;
            animation: pulse 2s ease-in-out infinite;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
        }

        .logo h1 {
            font-size: 2.2em;
            margin: 0;
            font-weight: 500;
            color: white;
        }

        .estado-conexion {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: bold;
        }

        .indicador {
            width: 12px;
            height: 12px;
            background-color: #00ff00;
            border-radius: 50%;
            box-shadow: 0 0 5px #00ff00;
        }

        /* Botones */
        .actions {
            background-color: #f5f8fc;
            padding: 20px 30px;
            border-bottom: 1px solid #e0e0e0;
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .actions button {
            background-color: #0a4ea3;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 16px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }

        .actions button:hover {
            background-color: #082f6b;
            transform: scale(1.05);
        }

        /* Contenido */
        .content {
            padding: 30px;
            overflow-y: auto;
            flex: 1;
        }

        /* Barra de estado */
        .status-bar {
            background-color: #0a4ea3;
            color: white;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 2px 8px rgba(10, 78, 163, 0.3);
        }

        .status-indicator {
            display: inline-block;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background-color: #00ff00;
            box-shadow: 0 0 5px #00ff00;
        }

        /* Estadísticas */
        .estadisticas {
            display: flex;
            gap: 20px;
            margin-bottom: 25px;
            flex-wrap: wrap;
        }

        .estadistica-card {
            background: linear-gradient(135deg, #0a4ea3, #0d63d4);
            color: white;
            padding: 20px;
            border-radius: 10px;
            flex: 1;
            min-width: 200px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        }

        .estadistica-card h3 {
            margin: 0 0 10px 0;
            font-size: 1.1em;
            opacity: 0.9;
        }

        .estadistica-card .numero {
            font-size: 2.5em;
            font-weight: bold;
            margin: 0;
        }

        /* Tabla */
        .data-table h2 {
            color: #0a4ea3;
            margin-bottom: 20px;
            font-size: 1.8em;
        }

        .data-table table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-radius: 10px;
            overflow: hidden;
        }

        .data-table th,
        .data-table td {
            padding: 12px 15px;
            text-align: center;
            border-bottom: 1px solid #e0e0e0;
        }

        .data-table th {
            background-color: #0a4ea3;
            font-weight: 600;
            color: white;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .data-table tbody tr:nth-child(even) {
            background-color: #f2f6ff;
        }

        .data-table tbody tr:hover {
            background-color: #e6f0ff;
            transition: background-color 0.2s;
        }

        /* Promedio destacado */
        .promedio-alto {
            background-color: #2ecc71;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            font-weight: bold;
        }

        .promedio-medio {
            background-color: #f39c12;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            font-weight: bold;
        }

        .promedio-bajo {
            background-color: #e74c3c;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            font-weight: bold;
        }

        /* Footer */
        footer {
            text-align: center;
            padding: 15px;
            background-color: #0a4ea3;
            color: white;
            margin-top: auto;
        }

        /* Animación */
        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }

        /* Scrollbar personalizado */
        .content::-webkit-scrollbar {
            width: 8px;
        }

        .content::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .content::-webkit-scrollbar-thumb {
            background: #0a4ea3;
            border-radius: 10px;
        }

        .content::-webkit-scrollbar-thumb:hover {
            background: #082f6b;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                gap: 15px;
                padding: 20px;
            }
            
            .logo h1 {
                font-size: 1.5em;
            }
            
            .data-table table {
                font-size: 14px;
            }
            
            .estadistica-card {
                min-width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">
                <img src="logo27.jpg" alt="Logo del Colegio" class="logo-img">
                <h1>Sistema de Gestión - Colegio</h1>
            </div>
            <div class="estado-conexion">
                <span class="indicador"></span>
                <?php echo $conexion_exitosa ? 'BD Conectada' : 'BD Desconectada'; ?>
            </div>
        </div>

        <div class="actions">
            <button onclick="mostrarMensajeConexion()">Verificar Conexión</button>
            <button onclick="location.reload()">Actualizar Datos</button>
            <button onclick="window.print()">Imprimir</button>
        </div>

        <div class="content">
            <div class="status-bar" id="status-bar">
                <span class="status-indicator"></span>
                <?php echo $conexion_exitosa ? 'Conectado a AlwaysData' : 'Error de conexión'; ?>
            </div>

            <?php if ($conexion_exitosa): ?>
            <div class="estadisticas">
                <div class="estadistica-card">
                    <h3>Total de Registros</h3>
                    <p class="numero"><?php echo $total_registros; ?></p>
                </div>
                <div class="estadistica-card">
                    <h3>Promedio General</h3>
                    <p class="numero">
                        <?php 
                        $suma = array_sum(array_column($alumnos, 'promedio'));
                        echo $total_registros > 0 ? number_format($suma / $total_registros, 2) : '0.00';
                        ?>
                    </p>
                </div>
                <div class="estadistica-card">
                    <h3>Base de Datos</h3>
                    <p class="numero">AlwaysData</p>
                </div>
            </div>
            <?php endif; ?>

            <div class="data-table">
                <h2>Listado de Alumnos - 100 Registros</h2>
                <?php if ($total_registros > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Correo</th>
                            <th>Teléfono</th>
                            <th>Fecha de Nacimiento</th>
                            <th>Ciudad</th>
                            <th>Promedio</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($alumnos as $alumno): 
                            $promedio = $alumno['promedio'];
                            $clase_promedio = '';
                            if ($promedio >= 9.0) {
                                $clase_promedio = 'promedio-alto';
                            } elseif ($promedio >= 7.5) {
                                $clase_promedio = 'promedio-medio';
                            } else {
                                $clase_promedio = 'promedio-bajo';
                            }
                        ?>
                            <tr>
                                <td><?php echo htmlspecialchars($alumno['id']); ?></td>
                                <td><?php echo htmlspecialchars($alumno['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($alumno['apellido']); ?></td>
                                <td><?php echo htmlspecialchars($alumno['correo']); ?></td>
                                <td><?php echo htmlspecialchars($alumno['telefono']); ?></td>
                                <td><?php echo htmlspecialchars($alumno['fecha_nacimiento']); ?></td>
                                <td><?php echo htmlspecialchars($alumno['ciudad']); ?></td>
                                <td>
                                    <span class="<?php echo $clase_promedio; ?>">
                                        <?php echo number_format($promedio, 2); ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                    <p style="text-align: center; padding: 20px; color: #666;">
                        <?php echo $conexion_exitosa ? 'No hay registros en la base de datos.' : 'No se pudo conectar a la base de datos. Verifica tu archivo conexion.php'; ?>
                    </p>
                <?php endif; ?>
            </div>
        </div>

        <footer>
            <p>© 2025 Sistema de Gestión - Colegio | AlwaysData Database</p>
        </footer>
    </div>

    <script>
        var conexionExitosa = <?php echo json_encode($conexion_exitosa); ?>;

        // Actualizar indicador al cargar la página
        window.onload = function() {
            var indicador = document.querySelector('.indicador');
            if (conexionExitosa) {
                indicador.style.backgroundColor = '#00ff00';
                indicador.style.boxShadow = '0 0 5px #00ff00';
            } else {
                indicador.style.backgroundColor = '#ff0000';
                indicador.style.boxShadow = '0 0 5px #ff0000';
            }
        };

        function mostrarMensajeConexion() {
            var statusBar = document.getElementById('status-bar');
            var statusIndicator = document.querySelector('.status-indicator');

            if (conexionExitosa) {
                statusIndicator.textContent = '✔';
                statusIndicator.style.color = 'white';
                statusBar.innerHTML = '<span class="status-indicator">✔</span> Conexión exitosa a AlwaysData';
                statusBar.style.backgroundColor = '#2ecc71';
            } else {
                statusIndicator.textContent = '✖';
                statusIndicator.style.color = 'white';
                statusBar.innerHTML = '<span class="status-indicator">✖</span> Error al conectar a AlwaysData - Verifica tus credenciales';
                statusBar.style.backgroundColor = '#e74c3c';
            }
        }
    </script>
</body>
</html>