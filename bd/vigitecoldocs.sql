-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 31-10-2025 a las 17:22:05
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `vigitecoldocs`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `areas`
--

CREATE TABLE `areas` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `id_responsable` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `areas`
--

INSERT INTO `areas` (`id`, `nombre`, `descripcion`, `id_responsable`, `created_at`) VALUES
(1, 'Talento Humano', 'Área encargada de la gestión del personal y desarrollo humano.', 43, '2025-10-30 14:56:03'),
(2, 'Operaciones', 'Supervisión y coordinación de las operaciones de la empresa.', 44, '2025-10-30 14:56:03'),
(3, 'Gerencia Operativo', 'Dirección de las operaciones generales y estratégicas.', 46, '2025-10-30 14:56:03'),
(4, 'Juridica', 'Asesoría legal y gestión jurídica de la organización.', 47, '2025-10-30 14:56:03'),
(5, 'Comercial', 'Gestión de ventas, clientes y estrategias comerciales.', 48, '2025-10-30 14:56:03'),
(6, 'Publicidad', 'Encargada del diseño y promoción de la imagen institucional.', 49, '2025-10-30 14:56:03'),
(7, 'Bienestar', 'Gestión del bienestar y actividades del personal.', 50, '2025-10-30 14:56:03'),
(8, 'Licitaciones', 'Encargada de los procesos de contratación y licitaciones.', 51, '2025-10-30 14:56:03'),
(9, 'SIG', 'Sistema Integrado de Gestión y mejora continua.', 52, '2025-10-30 14:56:03'),
(10, 'SST', 'Seguridad y Salud en el Trabajo.', 53, '2025-10-30 14:56:03'),
(11, 'Sistemas', 'Soporte, infraestructura y desarrollo tecnológico.', 55, '2025-10-30 14:56:03'),
(12, 'Recepcion', 'Atención al cliente y manejo de correspondencia.', 56, '2025-10-30 14:56:03'),
(13, 'Cartera', 'Gestión de cobros y cuentas por cobrar.', 59, '2025-10-30 14:56:03'),
(14, 'Tesoreria', 'Manejo de fondos, pagos y flujo de caja.', 60, '2025-10-30 14:56:03'),
(15, 'Gerencia Financiera', 'Planeación y control financiero de la empresa.', 62, '2025-10-30 14:56:03'),
(16, 'Soporte Tecnico', 'Atención de fallos técnicos e infraestructura.', 63, '2025-10-30 14:56:03'),
(17, 'Servicios', 'Coordinación de los servicios prestados.', 64, '2025-10-30 14:56:03'),
(18, 'Poligrafo', 'Aplicación de pruebas de polígrafo y evaluaciones.', 65, '2025-10-30 14:56:03'),
(19, 'Alturas', 'Entrenamiento y certificación en trabajos en alturas.', 89, '2025-10-30 14:56:03'),
(20, 'Seguridad', 'Supervisión de protocolos y personal de seguridad.', 91, '2025-10-30 14:56:03'),
(21, 'Monitoreo', 'Supervisión de cámaras y sistemas de vigilancia.', 92, '2025-10-30 14:56:03'),
(22, 'Servicio Tecnico', 'Atención técnica a equipos e instalaciones.', 98, '2025-10-30 14:56:03'),
(23, 'Centro de Eventos', 'Administración y operación del centro de eventos.', 102, '2025-10-30 14:56:03');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cargos`
--

CREATE TABLE `cargos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cargos`
--

INSERT INTO `cargos` (`id`, `nombre`, `descripcion`, `created_at`) VALUES
(1, 'Aux. Talento Humano', NULL, '2025-10-30 14:15:50'),
(2, 'Pract. Talento Humano', NULL, '2025-10-30 14:15:50'),
(3, 'Lider Talento Humano', NULL, '2025-10-30 14:15:50'),
(4, 'Lider Operaciones', NULL, '2025-10-30 14:15:50'),
(5, 'Aux. Operaciones', NULL, '2025-10-30 14:15:50'),
(6, 'Gerente Operativo', NULL, '2025-10-30 14:15:50'),
(7, 'Abogada', NULL, '2025-10-30 14:15:50'),
(8, 'Vendedor', NULL, '2025-10-30 14:15:50'),
(9, 'Publicista', NULL, '2025-10-30 14:15:50'),
(10, 'Pact. Bienestar', NULL, '2025-10-30 14:15:50'),
(11, 'Aux. Licitaciones', NULL, '2025-10-30 14:15:50'),
(12, 'Lider de Sig', NULL, '2025-10-30 14:15:50'),
(13, 'Lider SST', NULL, '2025-10-30 14:15:50'),
(14, 'Pract. Apoyo Documental', NULL, '2025-10-30 14:15:50'),
(15, 'Lider Sistemas', NULL, '2025-10-30 14:15:50'),
(16, 'Recepcionista', NULL, '2025-10-30 14:15:50'),
(17, 'Aux. Nomina', NULL, '2025-10-30 14:15:50'),
(18, 'Aux. Contable2', NULL, '2025-10-30 14:15:50'),
(19, 'Aux. Contable 1', NULL, '2025-10-30 14:15:50'),
(20, 'Aux. Tesoreria', NULL, '2025-10-30 14:15:50'),
(21, 'Contadora', NULL, '2025-10-30 14:15:50'),
(22, 'Gerente Financiera', NULL, '2025-10-30 14:15:50'),
(23, 'Coordinadora Soporte Tecnico', NULL, '2025-10-30 14:15:50'),
(24, 'Lider Servicios', NULL, '2025-10-30 14:15:50'),
(25, 'Poligrafista', NULL, '2025-10-30 14:15:50'),
(26, 'Lider de Altras', NULL, '2025-10-30 14:15:50'),
(27, 'Instructora', NULL, '2025-10-30 14:15:50'),
(28, 'Aux. Administrativa', NULL, '2025-10-30 14:15:50'),
(29, 'Lider Monitoreo', NULL, '2025-10-30 14:15:50'),
(30, 'Equipo Camaras', NULL, '2025-10-30 14:15:50'),
(31, 'Aux. Gerencia Operativa y Monitoreo', NULL, '2025-10-30 14:15:50'),
(32, 'Equipo de apoyo y monitoreo', NULL, '2025-10-30 14:15:50'),
(33, 'Equipo de SQL', NULL, '2025-10-30 14:15:50'),
(34, 'Repetidor SQL', NULL, '2025-10-30 14:15:50'),
(35, 'Equipo Bismark', NULL, '2025-10-30 14:15:50'),
(36, 'Equipo Servidor Compartidos', NULL, '2025-10-30 14:15:50'),
(37, 'Equipo Servidor Siigo', NULL, '2025-10-30 14:15:50'),
(38, 'Pract. Sistemas', NULL, '2025-10-30 14:15:50'),
(39, 'Aux. Administrativa', NULL, '2025-10-30 14:15:50'),
(40, 'Respaldo', NULL, '2025-10-30 14:15:50');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) DEFAULT NULL,
  `tipo_documento` varchar(20) DEFAULT NULL,
  `documento` varchar(50) DEFAULT NULL,
  `celular` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `tipo_cliente` enum('Natural','Jurídico','Gubernamental') DEFAULT 'Natural',
  `fecha_registro` date DEFAULT curdate(),
  `estado` enum('Activo','Inactivo','Bloqueado') DEFAULT 'Activo',
  `notas_adicionales` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id`, `nombre`, `apellido`, `tipo_documento`, `documento`, `celular`, `email`, `direccion`, `tipo_cliente`, `fecha_registro`, `estado`, `notas_adicionales`, `created_at`, `updated_at`) VALUES
(1, 'CARLOS ANDRES', 'GUTIERREZ', 'CC', '1234567890', '3102223344', 'carlos.gutierrez@empresa.com', 'Calle 100 #15-20, Bogotá', 'Jurídico', '2023-01-15', 'Activo', 'Cliente corporativo importante', '2025-10-30 15:02:16', '2025-10-30 15:02:16'),
(2, 'MARIA FERNANDA', 'RODRIGUEZ', 'CC', '0987654321', '3156677889', 'maria.rodriguez@gmail.com', 'Carrera 45 #80-10, Medellín', 'Natural', '2023-02-20', 'Activo', 'Prefiere contacto por WhatsApp', '2025-10-30 15:02:16', '2025-10-30 15:02:16'),
(3, 'JOSE LUIS', 'MARTINEZ', 'CE', 'AB1234567', '3204445566', 'jose.martinez@outlook.com', 'Av. 68 #25-40, Cali', 'Natural', '2023-03-10', 'Activo', 'Cliente frecuente', '2025-10-30 15:02:16', '2025-10-30 15:02:16'),
(4, 'ANA ISABEL', 'LOPEZ', 'CC', '1122334455', '3178899001', 'ana.lopez@hotmail.com', 'Calle 72 #10-15, Barranquilla', 'Natural', '2023-04-05', 'Inactivo', 'No ha renovado contrato', '2025-10-30 15:02:16', '2025-10-30 15:02:16'),
(5, 'PEDRO ANTONIO', 'SILVA', 'NIT', '9001234567', '6013324455', 'pedro.silva@industrias.com', 'Carrera 60 #25-35, Bogotá', 'Jurídico', '2023-05-12', 'Activo', 'Empresa manufacturera', '2025-10-30 15:02:16', '2025-10-30 15:02:16'),
(6, 'LAURA PATRICIA', 'GARCIA', 'CC', '5566778899', '3181122334', 'laura.garcia@gmail.com', 'Av. Boyacá #15-25, Medellín', 'Natural', '2023-06-18', 'Activo', 'Solicita factura electrónica', '2025-10-30 15:02:16', '2025-10-30 15:02:16'),
(7, 'ROBERTO CARLOS', 'HERNANDEZ', 'CC', '6677889900', '3192233445', 'roberto.hernandez@yahoo.com', 'Calle 85 #45-50, Cartagena', 'Natural', '2023-07-22', 'Bloqueado', 'Pendiente de pago', '2025-10-30 15:02:16', '2025-10-30 15:02:16'),
(8, 'SANDRA MILENA', 'RAMIREZ', 'CC', '7788990011', '3143344556', 'sandra.ramirez@empresa.com', 'Carrera 30 #70-80, Bucaramanga', 'Jurídico', '2023-08-30', 'Activo', 'Cliente nuevo', '2025-10-30 15:02:16', '2025-10-30 15:02:16'),
(9, 'DIEGO ALEJANDRO', 'TORRES', 'CE', 'CD9876543', '3214455667', 'diego.torres@gmail.com', 'Av. Las Américas #25-30, Cali', 'Natural', '2023-09-14', 'Activo', 'Interesado en nuevos servicios', '2025-10-30 15:02:16', '2025-10-30 15:02:16'),
(10, 'CATALINA', 'VARGAS', 'CC', '8899001122', '3165566778', 'catalina.vargas@outlook.com', 'Calle 50 #40-55, Bogotá', 'Natural', '2023-10-08', 'Activo', 'Cliente preferencial', '2025-10-30 15:02:16', '2025-10-30 15:02:16'),
(11, 'ALBERTO', 'DIAZ', 'NIT', '8009876543', '6016677889', 'alberto.diaz@constructora.com', 'Carrera 15 #90-10, Medellín', 'Jurídico', '2023-11-25', 'Activo', 'Empresa constructora grande', '2025-10-30 15:02:16', '2025-10-30 15:02:16'),
(12, 'GLORIA ESTHER', 'CASTRO', 'CC', '9900112233', '3137788990', 'gloria.castro@hotmail.com', 'Av. 26 #15-45, Barranquilla', 'Natural', '2023-12-03', 'Inactivo', 'Cambió de proveedor', '2025-10-30 15:02:16', '2025-10-30 15:02:16'),
(13, 'FERNANDO', 'RUIZ', 'CC', '1011121314', '3228899001', 'fernando.ruiz@gmail.com', 'Calle 100 #25-35, Cartagena', 'Natural', '2024-01-10', 'Activo', 'Cliente ocasional', '2025-10-30 15:02:16', '2025-10-30 15:02:16'),
(14, 'PATRICIA', 'MORALES', 'CC', '1213141516', '3189900112', 'patricia.morales@empresa.com', 'Carrera 45 #25-15, Bogotá', 'Jurídico', '2024-02-15', 'Activo', 'Solicita capacitación', '2025-10-30 15:02:16', '2025-10-30 15:02:16'),
(15, 'JUAN CARLOS', 'ORTIZ', 'CE', 'EF2468135', '3170011223', 'juan.ortiz@yahoo.com', 'Av. 68 #40-50, Medellín', 'Natural', '2024-03-20', 'Activo', 'Cliente satisfecho', '2025-10-30 15:02:16', '2025-10-30 15:02:16'),
(16, 'LUCIA', 'MENDOZA', 'CC', '1415161718', '3201122334', 'lucia.mendoza@gmail.com', 'Calle 72 #30-25, Cali', 'Natural', '2024-04-05', 'Activo', 'Prefiere email para comunicaciones', '2025-10-30 15:02:16', '2025-10-30 15:02:16'),
(17, 'RICARDO', 'GUZMAN', 'NIT', '8901234567', '6012233445', 'ricardo.guzman@comercial.com', 'Carrera 60 #35-45, Bucaramanga', 'Jurídico', '2024-05-12', 'Activo', 'Distribuidor autorizado', '2025-10-30 15:02:16', '2025-10-30 15:02:16'),
(18, 'MONICA', 'SALAZAR', 'CC', '1617181920', '3153344556', 'monica.salazar@outlook.com', 'Av. Caracas #25-30, Bogotá', 'Natural', '2024-06-18', 'Activo', 'Cliente desde hace 2 años', '2025-10-30 15:02:16', '2025-10-30 15:02:16'),
(19, 'OSCAR', 'VEGA', 'CC', '1819202122', '3194455667', 'oscar.vega@hotmail.com', 'Calle 85 #50-60, Medellín', 'Natural', '2024-07-22', 'Bloqueado', 'Problemas con pagos', '2025-10-30 15:02:16', '2025-10-30 15:02:16'),
(20, 'ADRIANA', 'ROJAS', 'CC', '2021222324', '3145566778', 'adriana.rojas@empresa.com', 'Carrera 30 #80-90, Cartagena', 'Jurídico', '2024-08-30', 'Activo', 'Cliente corporativo', '2025-10-30 15:02:16', '2025-10-30 15:02:16');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documentos`
--

CREATE TABLE `documentos` (
  `id` int(11) NOT NULL,
  `nombre_doc` varchar(255) NOT NULL,
  `tipo_doc` varchar(50) DEFAULT NULL,
  `version` varchar(20) DEFAULT NULL,
  `area_id` int(11) NOT NULL,
  `cargo_id` int(11) DEFAULT NULL,
  `pesantante_id` int(11) DEFAULT NULL,
  `fecha_documento` date DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `nivel_confidencial` enum('Bajo','Medio','Alto','Crítico') DEFAULT 'Medio',
  `codigo_acceso` varchar(100) DEFAULT NULL,
  `usuario_acceso_id` int(11) DEFAULT NULL,
  `area_acceso_id` int(11) DEFAULT NULL,
  `operativo` tinyint(1) DEFAULT 0,
  `fecha_vigencia` date DEFAULT NULL,
  `fecha_revision` date DEFAULT NULL,
  `notas` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `formatos`
--

CREATE TABLE `formatos` (
  `id` int(11) NOT NULL,
  `nombre_formato` varchar(255) NOT NULL,
  `version` varchar(20) NOT NULL,
  `area_id` int(11) NOT NULL,
  `cargo_id` int(11) DEFAULT NULL,
  `pesantante_id` int(11) DEFAULT NULL,
  `cargo_responsable` varchar(100) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `documento_relacionado_id` int(11) DEFAULT NULL,
  `fecha_vigencia` date DEFAULT NULL,
  `fecha_revision` date DEFAULT NULL,
  `codigo_acceso` varchar(100) DEFAULT NULL,
  `area_acceso_id` int(11) DEFAULT NULL,
  `notas` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) DEFAULT NULL,
  `cargo_id` int(11) DEFAULT NULL,
  `tipo_documento` varchar(20) DEFAULT NULL,
  `documento` varchar(50) DEFAULT NULL,
  `celular` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `tipo_usuario` enum('Administrativo','Operativo') NOT NULL,
  `fecha_registro` date DEFAULT NULL,
  `estado` enum('Activo','Inactivo') DEFAULT 'Activo',
  `notas_adicionales` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `password` varchar(255) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `login_attempts` int(11) DEFAULT 0,
  `locked_until` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `apellido`, `cargo_id`, `tipo_documento`, `documento`, `celular`, `email`, `direccion`, `tipo_usuario`, `fecha_registro`, `estado`, `notas_adicionales`, `created_at`, `updated_at`, `password`, `username`, `last_login`, `login_attempts`, `locked_until`) VALUES
(1, 'DIANA MARCELA', 'VALENCIA', 1, 'CC', '1012345678', '3101234567', 'diana.valencia@gmail.com', 'Calle 123 #45-67', 'Administrativo', '2023-01-15', 'Activo', NULL, '2025-10-30 14:32:49', '2025-10-30 21:25:10', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, 0, NULL),
(2, 'PRACTICANTE', 'TALENTO HUMANO', 2, 'CC', '1012345679', '3101234568', 'practicante.th@gmail.com', 'Carrera 78 #12-34', 'Operativo', '2024-03-01', 'Activo', NULL, '2025-10-30 14:32:49', '2025-10-30 21:25:10', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, 0, NULL),
(3, 'MARTHA JANET', 'GONZALES', 3, 'CC', '1012345680', '3101234569', 'martha.gonzales@vigitecol.com', 'Av 5 #23-45', 'Administrativo', '2022-05-20', 'Activo', NULL, '2025-10-30 14:32:49', '2025-10-31 13:36:01', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'martha.gonzales', '2025-10-31 08:36:01', 0, NULL),
(4, 'JULIAN DAVID', 'GIRALDO', 4, 'CC', '1012345681', '3101234570', 'julian.giraldo@gmail.com', 'Calle 67 #89-10', 'Operativo', '2023-08-10', 'Activo', NULL, '2025-10-30 14:32:49', '2025-10-30 21:25:10', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, 0, NULL),
(5, 'LEIDY JOHANA', 'VERA', 5, 'CC', '1012345682', '3101234571', 'leidy.vera@gmail.com', 'Carrera 45 #67-89', 'Operativo', '2023-11-05', 'Activo', NULL, '2025-10-30 14:32:49', '2025-10-30 21:25:10', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, 0, NULL),
(6, 'CRHISTIAN', 'LOPEZ', 6, 'CC', '1012345683', '3101234572', 'christian.lopez@vigitecol.com', 'Av 8 #12-34', 'Administrativo', '2021-12-01', 'Activo', NULL, '2025-10-30 14:32:49', '2025-10-31 13:49:12', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'christian.lopez', '2025-10-31 08:49:12', 0, NULL),
(7, 'MARIA DE LOS ANGELES', 'RIVERA', 7, 'CC', '1012345684', '3101234573', 'maria.rivera@gmail.com', 'Calle 90 #11-22', 'Administrativo', '2023-02-28', 'Activo', NULL, '2025-10-30 14:32:49', '2025-10-30 21:25:10', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, 0, NULL),
(8, 'FABER LEANDRO', 'MORA', 8, 'CC', '1012345685', '3101234574', 'faber.mora@gmail.com', 'Carrera 33 #44-55', 'Operativo', '2024-01-10', 'Activo', NULL, '2025-10-30 14:32:49', '2025-10-30 21:25:10', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, 0, NULL),
(9, 'FERNANDO', 'GOMEZ', 9, 'CC', '1012345686', '3101234575', 'fernando.gomez@gmail.com', 'Av 15 #67-89', 'Operativo', '2023-09-15', 'Activo', NULL, '2025-10-30 14:32:49', '2025-10-30 21:25:10', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, 0, NULL),
(10, 'MANUELA', 'TOBON', 10, 'CC', '1012345687', '3101234576', 'manuela.tobon@gmail.com', 'Calle 25 #36-47', 'Administrativo', '2023-07-20', 'Activo', NULL, '2025-10-30 14:32:49', '2025-10-30 21:25:10', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, 0, NULL),
(11, 'SANDRA VIVIANA', 'OCAMPO', 11, 'CC', '1012345688', '3101234577', 'sandra.ocampo@gmail.com', 'Carrera 89 #10-11', 'Administrativo', '2022-11-30', 'Activo', NULL, '2025-10-30 14:32:49', '2025-10-30 21:25:10', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, 0, NULL),
(12, 'LINA MARCELA', 'MURCIA', 12, 'CC', '1012345689', '3101234578', 'lina.murcia@gmail.com', 'Av 20 #30-40', 'Administrativo', '2023-04-12', 'Activo', NULL, '2025-10-30 14:32:49', '2025-10-30 21:25:10', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, 0, NULL),
(13, 'PAULA CRISTINA', 'ESCOBAR', 13, 'CC', '1012345690', '3101234579', 'paula.escobar@gmail.com', 'Calle 50 #60-70', 'Administrativo', '2022-09-18', 'Activo', NULL, '2025-10-30 14:32:49', '2025-10-30 21:25:10', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, 0, NULL),
(14, 'PRACTICANTE', 'APOYO DOCUMENTAL', 14, 'CC', '1012345691', '3101234580', 'practicante.documental@gmail.com', 'Carrera 22 #33-44', 'Operativo', '2024-02-14', 'Activo', NULL, '2025-10-30 14:32:49', '2025-10-30 21:25:10', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, 0, NULL),
(15, 'MICHAEL STEVEN', 'OCAMPO HENAO', 15, 'CC', '1012345692', '3101234581', 'admin@vigitecol.com', 'Av 10 #25-35', 'Administrativo', '2021-06-15', 'Activo', NULL, '2025-10-30 14:32:49', '2025-10-30 15:11:49', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', NULL, 0, NULL),
(16, 'ESTEFANIA', 'VICTORIA', 16, 'CC', '1012345693', '3101234582', 'estefania.victoria@gmail.com', 'Calle 40 #50-60', 'Operativo', '2023-10-22', 'Activo', NULL, '2025-10-30 14:32:49', '2025-10-30 21:25:10', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, 0, NULL),
(17, 'LEIDY ALEJANDRA', 'OTALVARO', 17, 'CC', '1012345694', '3101234583', 'leidy.otalvaro@gmail.com', 'Carrera 55 #66-77', 'Administrativo', '2023-03-08', 'Activo', NULL, '2025-10-30 14:32:49', '2025-10-30 21:25:10', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, 0, NULL),
(18, 'MARIA ALEJANDRA', 'CASTAÑO', 18, 'CC', '1012345695', '3101234584', 'maria.castaño@gmail.com', 'Av 18 #28-38', 'Administrativo', '2022-07-25', 'Activo', NULL, '2025-10-30 14:32:49', '2025-10-30 21:25:10', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, 0, NULL),
(19, 'MARIA CAMILA', 'GONZALES ECHEVERRY', 19, 'CC', '1012345696', '3101234585', 'maria.gonzales@gmail.com', 'Calle 35 #45-55', 'Administrativo', '2023-12-10', 'Activo', NULL, '2025-10-30 14:32:49', '2025-10-30 21:25:10', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, 0, NULL),
(20, 'TATIANA CAROLINA', 'OCAMPO FRANCO', 20, 'CC', '1012345697', '3101234586', 'tatiana.ocampo@gmail.com', 'Carrera 40 #50-60', 'Administrativo', '2022-08-14', 'Activo', NULL, '2025-10-30 14:32:49', '2025-10-30 21:25:10', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, 0, NULL),
(21, 'VIVIANA MARCELA', '', 21, 'CC', '1012345698', '3101234587', 'viviana.marcela@gmail.com', 'Av 25 #35-45', 'Administrativo', '2021-11-05', 'Activo', NULL, '2025-10-30 14:32:49', '2025-10-30 21:25:10', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, 0, NULL),
(22, 'TATIANA ROCIO', 'RIVEROS', 22, 'CC', '1012345699', '3101234588', 'tatiana.riveros@gmail.com', 'Calle 70 #80-90', 'Administrativo', '2020-09-12', 'Activo', NULL, '2025-10-30 14:32:49', '2025-10-30 21:25:10', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, 0, NULL),
(23, 'NATALIA', 'ARROYABE', 23, 'CC', '1012345700', '3101234589', 'natalia.arroyabe@gmail.com', 'Carrera 60 #70-80', 'Administrativo', '2022-04-20', 'Activo', NULL, '2025-10-30 14:32:49', '2025-10-30 21:25:10', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, 0, NULL),
(24, 'PAULA ANDREA', 'QUINTERO HIGUITA', 24, 'CC', '1012345701', '3101234590', 'paula.quintero@gmail.com', 'Av 30 #40-50', 'Operativo', '2023-06-30', 'Activo', NULL, '2025-10-30 14:32:49', '2025-10-30 21:25:10', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, 0, NULL),
(25, 'LORENA', 'ALZATE', 25, 'CC', '1012345702', '3101234591', 'lorena.alzate@gmail.com', 'Calle 85 #95-105', 'Operativo', '2023-05-15', 'Activo', NULL, '2025-10-30 14:32:49', '2025-10-30 21:25:10', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, 0, NULL),
(26, 'JULIETH', 'MARIN VASQUEZ', 26, 'CC', '100000001', NULL, NULL, NULL, 'Operativo', '2025-10-30', 'Activo', NULL, '2025-10-30 14:37:38', '2025-10-30 14:37:38', NULL, NULL, NULL, 0, NULL),
(27, 'JULIANA', 'LOPEZ QUICENO', 27, 'CC', '100000002', NULL, NULL, NULL, 'Operativo', '2025-10-30', 'Activo', NULL, '2025-10-30 14:37:38', '2025-10-30 14:37:38', NULL, NULL, NULL, 0, NULL),
(28, 'LEIDY JOHANA', 'FRANCO TREJOS', 28, 'CC', '100000003', NULL, NULL, NULL, 'Administrativo', '2025-10-30', 'Activo', NULL, '2025-10-30 14:37:38', '2025-10-30 14:37:38', NULL, NULL, NULL, 0, NULL),
(29, 'SOL ANGEL', 'HENAO', 29, 'CC', '100000004', NULL, NULL, NULL, 'Operativo', '2025-10-30', 'Activo', NULL, '2025-10-30 14:37:38', '2025-10-30 14:37:38', NULL, NULL, NULL, 0, NULL),
(30, 'SOL ANGEL', 'HENAO', 30, 'CC', '100000005', NULL, NULL, NULL, 'Operativo', '2025-10-30', 'Activo', NULL, '2025-10-30 14:37:38', '2025-10-30 14:37:38', NULL, NULL, NULL, 0, NULL),
(31, 'XIMENA', 'USMA ARROYABE', 31, 'CC', '100000006', NULL, NULL, NULL, 'Operativo', '2025-10-30', 'Activo', NULL, '2025-10-30 14:37:38', '2025-10-30 14:37:38', NULL, NULL, NULL, 0, NULL),
(32, 'OPERADORAS DE MEDIOS', 'TECNOLOGICOS', 32, 'CC', '100000007', NULL, NULL, NULL, 'Operativo', '2025-10-30', 'Activo', NULL, '2025-10-30 14:37:38', '2025-10-30 14:37:38', NULL, NULL, NULL, 0, NULL),
(33, 'SOL ANGEL', 'HENAO', 33, 'CC', '100000008', NULL, NULL, NULL, 'Operativo', '2025-10-30', 'Activo', NULL, '2025-10-30 14:37:38', '2025-10-30 14:37:38', NULL, NULL, NULL, 0, NULL),
(34, 'SOL ANGEL', 'HEAO', 34, 'CC', '100000009', NULL, NULL, NULL, 'Operativo', '2025-10-30', 'Activo', NULL, '2025-10-30 14:37:38', '2025-10-30 14:37:38', NULL, NULL, NULL, 0, NULL),
(35, 'NATALIA', 'ARROYABE', 35, 'CC', '100000010', '3101111111', 'julieth.marin@gmail.com', 'Carrera 80 #25-30', 'Administrativo', '2025-10-30', 'Activo', NULL, '2025-10-30 14:37:38', '2025-10-30 21:25:10', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, 0, NULL),
(36, 'MICHAEL STEVEN', 'OCAMPO HENAO', 36, 'CC', '100000011', '3101111112', 'juliana.lopez@gmail.com', 'Calle 45 #67-89', 'Administrativo', '2025-10-30', 'Activo', NULL, '2025-10-30 14:37:38', '2025-10-30 21:25:10', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, 0, NULL),
(37, 'MICHAEL STEVEN', 'OCAMPO HENAO', 37, 'CC', '100000012', '3101111113', 'leidy.franco@gmail.com', 'Av 68 #12-34', 'Administrativo', '2025-10-30', 'Activo', NULL, '2025-10-30 14:37:38', '2025-10-30 21:25:10', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, 0, NULL),
(38, 'ALEJANDRO', 'VALLEJO ESCOBAR', 38, 'CC', '100000013', '3101111114', 'solange.henao@gmail.com', 'Carrera 25 #35-45', 'Operativo', '2025-10-30', 'Activo', NULL, '2025-10-30 14:37:38', '2025-10-30 21:25:10', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, 0, NULL),
(39, 'NATALIA', 'FLOREZ', 39, 'CC', '100000014', '3101111115', 'solange.henao2@gmail.com', 'Carrera 25 #35-45', 'Administrativo', '2025-10-30', 'Activo', NULL, '2025-10-30 14:37:38', '2025-10-30 21:25:10', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, 0, NULL),
(40, 'MICHAEL STEVEN', 'OCAMPO HENAO', 40, 'CC', '100000015', '3101111116', 'ximena.usma@gmail.com', 'Av 40 #50-60', 'Administrativo', '2025-10-30', 'Activo', NULL, '2025-10-30 14:37:38', '2025-10-30 21:25:10', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, 0, NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `areas`
--
ALTER TABLE `areas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `cargos`
--
ALTER TABLE `cargos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `documento` (`documento`);

--
-- Indices de la tabla `documentos`
--
ALTER TABLE `documentos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `formatos`
--
ALTER TABLE `formatos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `documento` (`documento`),
  ADD UNIQUE KEY `idx_usuarios_email` (`email`),
  ADD UNIQUE KEY `idx_usuarios_username` (`username`),
  ADD KEY `idx_usuarios_estado` (`estado`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `areas`
--
ALTER TABLE `areas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `cargos`
--
ALTER TABLE `cargos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `documentos`
--
ALTER TABLE `documentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `formatos`
--
ALTER TABLE `formatos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
