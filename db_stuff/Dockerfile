FROM node:11
WORKDIR /app
COPY . ./
RUN apt-get update && \
apt-get install -y postgresql-client && \
npm i
EXPOSE 80
CMD ["/bin/bash","/app/startup.sh"]