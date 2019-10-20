# DB-connection-in-php-oops
MySQL database connection using OOPS in PHP

## Usage

Download `db.class.php` file and include in your project

### Creating Object of DB class

```php
<?php
  include('db.class.php'); // call db.class.php
  $db = new DB();  
```

### Getting one specific data

```php
<?php
  $user = $db->getOne('SELECT * FROM users WHERE name = "Rahul"'); // 1 line selection, return 1 line
  echo $User['id'].'<br>'; // display the id
  echo $User['name'].'<br>'; // display the name
  echo $User['email']; // display the email
```
### Getting multiple data

```php
<?php
  $users = $db->getAll('SELECT * FROM users'); // select ALL from users

  $userCount = count($users); // return the number of lines

  echo $userCount.' users in the database<br />';

  foreach($users as $user) { // display the list
    echo $user['id'].' - '.$user['name'].' - '.$user['email'];	
  }
```

### Inserting data by ` execute() ` method of DB Class

```php
<?php
  $query = $db->execute('INSERT INTO users (name, email) VALUES ("Rahul", "rahul@email.com")');
```

### Updating data by ` execute() ` method of DB Class

```php
<?php
  $query = $db->execute('UPDATE users SET name="Rahul Kumar", email="rahulk@email.com" WHERE id=1');
```

### Inserting data by ` insert() ` method of DB Class

```php
<?php
  //parameter 1 : table name; parameter 2 : query argument of array type
  $queryArgs = array(
                   'name' => 'Rahul Kumar',
                   'email' => 'rahulk@email.com'
              );
  $query = $db->insert('users', $queryArgs);
```

### Updating data by ` update() ` method of DB Class

```php
<?php
  //parameter 1 : table name; parameter 2 : query argument of array type
  $queryArgs = array(
                   'name' => 'Rahul',
                   'email' => 'rahul@email.com'
              );
  $query = $db->update('users', $queryArgs);
```

## Security

If you discover any security related issues, please email rahulverma.rkv@gmail.com.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
