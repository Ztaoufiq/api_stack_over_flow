
# Symfony 6 StackOverFlow REST API
<br/>  
Requirements: [docker min . version 20.10.8, docker-compose min . version 1.21.0]  
<br/>   
## Quick start  

**Clone repository**

```  
git clone git@github.com:Ztaoufiq/_stack_over_flow  
```  

**Install dependencies**

```  
composer install  
```

**Build and Run docker images**

```  
docker-compose up --build
```  

**Prepare fixtures database**

```  
docker exec -it php-app sh
composer prepare
exit
``` 
**Configure the SSL keys path and passphrase in your env**
```  
bin/console lexik:jwt:generate-keypair
```
**Exemple of .env local `To copy from : .env`**
```
DATABASE_URL="mysql://sof:sof@mysql-db:3306/stack_over_flow"
JWT_SECRET_KEY=%kernel.project_dir%/config/jwt/private.pem
JWT_PUBLIC_KEY=%kernel.project_dir%/config/jwt/public.pem
JWT_PASSPHRASE=d95efd5b83f19313c2c1565829e0ebbf
```

<h3 align="center">  
<a href="EXAMPLES.md">See examples of usage !!!!!</a>  
</h3>  

<strong>  
</strong>