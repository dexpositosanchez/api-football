# api-football

API para la gestión de clubes, entrenadores y jugadores


# Estructura del proyecto
API-FOOTBALL/  
├── docker/ # Contiene los fichero necesarios para el Dockerizado  
├── football/ # Código fuente del proyecto Symfony  
├── resources/ # Recursos extra   
│ ├── dump.sql # Volcado de la base de datos con datos de prueba   
│ ├── documentacion.pdf # Documentación funcional y técnica de la API   
│ └── collection.json # Colección de Postman para probar la API  
└── README.md # Este archivo

# Requisitos

Docker y Docker Compose instalados

# Instalación
1. Clonar el repositorio
```
git clone https://github.com/dexpositosanchez/api-football.git
```
2. Acceder al proyecto
```
cd api-football
```

# Prepara el Docker
1. Levantar los contenedores
```
cd docker
```
```
docker compose up -d --build
```

2. Obtener el nombre del contenedor php
```
docker ps
```

3. Instalar las dependencias
```
docker exec -it {nombre del contenedor php} composer install
```

4. Comprobar que el proyecto esta levantado  
En el navegador acceder a localhost:8000

# Cargar la base de datos
1. Obtener el nombre del contenedor mysql
```
docker ps
```

2. Volcado de estructura y datos
```
cd ..
```
```
docker exec -i {nombre del contenedor mysql} mysql -u root -proot football < resources/dump.sql
```

# Cambiar variables de entorno para el envío de notificaciones
1. Cambiar el email receptor de las notificaciones  
En el fichero .env, modificar la variable NOTIFICATION_EMAIL_TO

2. Cambiar el email que envía las notificaciones  
Generar en GMAIL una clave para el envío de email desde aplicaciones  
En el fichero .env, modificar la variable MAILER_DSN con el usuario y la clave generada

# Uso de la API
1. Importación de la colección en Postman  
Acceder a https://www.postman.com/  
Importar la colección que se encuentra en /resources/

2. Uso de la documentación  
Leer la documentación que se encuentra en /resources/ y usar la API

# Test unitarios
1. Obtener el nombre del contenedor php
```
docker ps
```

2. Acceder al contenedor
```
docker exec -it {nombre del contenedor php} bash
```

2. Lanzar los test
```
php ./vendor/bin/phpunit
```

## Licencia

Este proyecto está bajo la licencia [MIT](./LICENSE).  
Puedes usarlo libremente siempre que mantengas el aviso de copyright original.


