
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

<h3 align="center">  
<a href="EXAMPLES.md">See examples of usage</a>  
</h3>  

<strong>  
</strong>