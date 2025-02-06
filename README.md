# Usage

To get started, make sure you have [Docker](https://docs.docker.com/desktop/) installed on your system, and then clone this repository.


I am using ports the: 4200, 8000 and 27017. Please make sure they are available.

```
git clone https://github.com/b0boc/airo-task.git
cd airo-task
docker-compose up -d
```

In few minutes docker will be up and running:
http://localhost:4200

When using the login form, upon 1st entry we are going to register the user. Next logins will check for the same password.

Upon successful login, the token is stored in local storage. Which is used to set the authorization header within the interceptor for further calls.

The quotation form can be used to generate the quote as requested in the task. If it's successful, the quotation total can be seen on the screen, otherwise a message alert informing the user what is wrong.

For the quote calculation, I am computing how much it is per day, then multiply with period and finally making the rate exchange if needed, as it seems quite easy to read the code as such. The quote is saved in BE and the response is returned in FE.

Validations and exceptions should be correctly handled by both BE and FE apps.


### To run the app without docker, please see the README of each folder (fe,api)