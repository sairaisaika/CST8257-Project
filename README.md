# CST8257-Project

CST8257 is a photos managing system made for academic reason. Code in server side is using PHP, and MySQL required(version must be earlier than 8.0)

### Features:

- Register New Users
- Login and Logout
- Add Friends
- Create Albums for private or shared with friends
- Upload images
- Personal Gallery and Gallery Shared by Friends
- Rotate Images' View in Gallery
- Remove Images in Gallery
- Download Original Image

### Future Plan:

1. Create smaller size of thumbnail for increasing performance.
2. System robustness

### Deployment:

##### Windows:

​	Once WAMP environment installed, copy files in release archive into www directory (which is the root directory of your web server).

​	Setup MySQL configuration in **src\Lab5Common\Connection.php**, as the following:

```php
<?php
$myPdo = new PDO(
    "mysql:host=YOUR MYSQL SERVER ADDRESS;dbname=DATABASE NAME;port=PORT NUMBER;charset=utf8",
    "USER NAME",
    "PASSWORD"
);?>
```


##### Linux/BSD:

​	Debian:

​		Once LAMP environment installed, do the same steps as deployment in Windows.

​	FreeBSD:	

​		Once Http Server, MySQL installed, do the same steps as deployment in Windows.