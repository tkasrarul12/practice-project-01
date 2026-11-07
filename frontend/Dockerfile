

FROM nginx
COPY nginx.conf /etc/nginx/conf.d/default.conf
COPY index.html /var/www/html/
COPY dashboard.html /var/www/html/
COPY style.css /var/www/html/
COPY script.js /var/www/html/
CMD ["nginx", "-g", "daemon off;"]



