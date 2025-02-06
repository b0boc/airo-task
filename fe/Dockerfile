FROM node:22.13.1

WORKDIR /app

COPY package*.json .

RUN npm install

COPY . .

RUN npm run build 

FROM nginx:alpine

COPY --from=0 /app/dist/fe/browser /usr/share/nginx/html

EXPOSE 4200

CMD ["nginx", "-g", "daemon off;"]