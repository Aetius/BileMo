
# BilMeo API

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/626281b600be407f8dbab68bf7fb5b1f)](https://app.codacy.com/manual/Aetius/BileMo?utm_source=github.com&utm_medium=referral&utm_content=Aetius/BileMo&utm_campaign=Badge_Grade_Dashboard)

 
This is an API project, developped with symfony 5.0.3. 
With this API, you will have access to our list of phones, and you will be able to stock your users in our database. 

###To install this API : 
#####Install the project : 
- Click on "clone or download" : If you choose 'Open in Desktop', you will upload these files directly from github, by GitHub Desktop (from example) If you choose to copy these files in .zip,

- Launch a composer install and you run your project locally,
 
- Configure the .env.local file, in the project's base (database, mailer...), 

#####To install the database : 
- Create the database : php bin/console d:d:c (if you have to install the test database, add --env test)

- Create the database schema : php bin/console d:s:u --force

- Then upload hautelook fixtures files with php bin/console d:f:l

###To use this API :
##### General informations : 
- Contact the administrator of this API. You will have your credentials to have granted access. 
- Then go to the documentation to familiarize with the routes and options : 
    The documentation is in https://127.0.0.1:8000/api/doc. 
    If you use Postman (or another api), you will find the doc in https://127.0.0.1:8000/api/doc.json
    
- To access all of the others parts of this API, you have to be connected. 

- If you want to test this api, you can be connected with the demo customer : login = demo ; password = demo. In this case, you will have access to the John DOE user. 

##### To get the token :
-  Go to /api/login and fill the login/password. If the user exists in db, you will receive a token (1 hour valid). 

- Then to access all the others pages, you will have to add the $token in a header "Authorization": "Bearer $token"

###About the cache system
A cache system is installed. If you want to disable it, you just have to go in the config\services.yaml and comment "App\Listener\Response\CacheResponseListener:", and comment the lign 25 in public/index.php. 
The cache system put in cache all the GET request. 
