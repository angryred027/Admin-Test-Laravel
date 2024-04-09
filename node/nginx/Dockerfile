# FROM nginx:1.19-alpine
# FROM nginx:1.23-alpine
FROM nginx:1.25-alpine

RUN apk update && \
  apk add --update --no-cache linux-headers --virtual=.build-dependencies \
  jq

# alp
RUN curl -L -O https://github.com/tkuchiki/alp/releases/download/v1.0.17/alp_linux_amd64.zip && \
  unzip alp_linux_amd64.zip && \
  mv alp /usr/local/bin/alp

CMD ["nginx", "-g", "daemon off;","-c","/etc/nginx/nginx.conf"]
