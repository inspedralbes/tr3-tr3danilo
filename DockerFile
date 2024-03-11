# Utiliza la imagen base de Node.js para construir el front-end de Nuxt.js
FROM node:14 AS nuxt_builder

# Establece el directorio de trabajo para el front-end dentro del contenedor
WORKDIR /app/web

# Copia el archivo package.json y package-lock.json para instalar las dependencias
COPY web/package*.json ./

# Instala las dependencias del proyecto
RUN npm install

# Copia todos los archivos de la aplicación front-end al contenedor
COPY web .

# Construye la aplicación Nuxt.js
RUN npm run build

# Utiliza la imagen base de PHP con Apache para el back-end de Laravel
FROM php:8.0.0-apache AS laravel_php

# Establece el directorio de trabajo para el back-end dentro del contenedor
WORKDIR /app/back

# Copia la carpeta Laravel completa al contenedor
COPY back .

# Copia los archivos generados previamente del front-end de Nuxt.js al directorio público de Laravel
COPY --from=nuxt_builder /app/web/dist /app/back/public

# Cambia los permisos de los archivos en la carpeta de almacenamiento writable
RUN chown -R www-data:www-data /app/back/storage /app/back/bootstrap/cache

# Copia el archivo de configuración de Apache para Laravel
COPY laravel.conf /etc/apache2/sites-available/000-default.conf

# Habilita los módulos de Apache necesarios para Laravel
RUN a2enmod rewrite

# Expon el puerto 80 para el servidor web de Apache
EXPOSE 80

# CMD que arranca Apache en primer plano para mantener el contenedor en funcionamiento
CMD ["apache2-foreground"]