# Sistema de Reporte y Gestión de Incidencias
### Departamento de Sistemas — UAM Azcapotzalco

Sistema web para el reporte y gestión de incidencias en las aulas 
del Departamento de Sistemas de la Universidad Autónoma Metropolitana 
Unidad Azcapotzalco. Permite a alumnos y profesores reportar fallas 
en los equipos mediante códigos QR y al personal del departamento 
gestionar y dar seguimiento a las incidencias reportadas.

---

## Características principales

- Reporte de incidencias mediante escaneo de códigos QR
- Clasificación automática de incidencias como urgentes o normales
- Notificaciones en tiempo real al personal vía bot de Telegram
- Gestión de incidencias pendientes con actualización automática
- Historial de incidencias resueltas con soluciones documentadas
- Módulo de estadísticas con exportación a PDF
- Control de acceso por módulo mediante sistema de permisos
- Generación de códigos QR individuales y por aula

---

## Tecnologías utilizadas

| Tecnología | Uso |
|---|---|
| PHP | Backend y lógica del servidor |
| MySQL | Base de datos relacional |
| Bootstrap 5 | Interfaz de usuario responsiva |
| JavaScript | Interactividad del frontend |
| Chart.js | Generación de gráficas estadísticas |
| jsPDF | Exportación de reportes en PDF |
| PHP QR Code | Generación de códigos QR |
| API de Telegram | Notificaciones en tiempo real |

---

## Requisitos

- PHP 7.4 o superior
- MySQL 5.7 o superior
- Servidor web Apache (XAMPP recomendado para entorno local)
- Certificado SSL (requerido para el Webhook de Telegram)
- Cuenta de Telegram y token de bot generado con @BotFather

---

## Instalación

- Dentro de la carpeta configuracion/Configuracion de XAMPP.txt

- Crear la base de datos con los archivos CrearBD.txt e
Insertar Datos Principales.txt dentro de configuracion

---

## Autor

**Jorge Mendoza**  
Ingeniería en Computacion — UAM Azcapotzalco  

---

## Licencia

Este proyecto fue desarrollado como proyecto terminal académico 
para el Departamento de Sistemas de la UAM Azcapotzalco.
