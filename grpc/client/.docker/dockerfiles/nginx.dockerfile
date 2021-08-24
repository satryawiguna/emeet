FROM nginx:1.15.8-alpine

COPY ./.docker/config/nginx/default.conf /etc/nginx/conf.d/default.conf
