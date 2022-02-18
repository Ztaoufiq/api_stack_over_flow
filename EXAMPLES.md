
# Examples of Usage

**Notice:** Don't forget to add `Content-Type: application/json` to your requests.


**Get JWT token:**

```
{
	"username": "email+1@email.com",
	"password": "password"
}
```
**login_check**

```
[POST] http://[host]/api/login_check

**Get token string**

```
**Get list of all questions**

```
[GET] http://[host]/api/questions
```
**Create new question**
```
[POST] http://[host]/api/questions

**Pass raw json body like : {"title": "New title", "content": "New content"}**
```
**Update question**
```
[PUT] http://[host]/api/questions/{id}

**Pass raw json body like : {"title": "Updated title", "content": "Updated content"}**
```
**Delete question**

```
[DELETE] http://[host]/api/questions/{id}
```
