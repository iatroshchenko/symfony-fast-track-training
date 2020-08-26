FROM nginx:1.17

ADD ./nginx.conf /etc/nginx/conf.d/default.conf
WORKDIR /var/www/api