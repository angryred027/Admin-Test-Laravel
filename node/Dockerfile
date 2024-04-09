FROM node:18.14-alpine

ENV NODE_ENV=production
ENV HOST 0.0.0.0

EXPOSE 3000

WORKDIR /frontend

ADD ../frontend/package.json ./
RUN yarn install

COPY ../frontend .
RUN yarn build

# # yarn install
# COPY ./next/package.json ./
# RUN yarn install
#
# # build
# COPY ./next/ ./
# RUN yarn build


CMD ["yarn", "start"]
