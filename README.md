# Heli Developement Helpers
Laravel package that provides set of classes to make use of common design patterns like (Repository Pattern, Factory Pattern).

### Why Built?
This package was built to get Helilabs start developing projects fast. and force a custom and smart way when building laravel applications.

### Why bother?


### Installation
* just require this package in your `composer file` like this
```
"helilabs/heli-developement-helpers" : "0.1.0"
```
and now you can use it as follows
| HelperClass | use in file | Why Built |
|---|---|---|
| Repository | extend from `Helilabs\HDH\Repository\BaseRepository` |This Class was built to make use of Repository Pattern|
| CurdFactory | extend from `Helilabs\HDH\CURD\CurdFactory` | This Class was built to make use of Proxy Pattern, And Factory Pattern|
| CurdCreator |extend from `Helilabs\HDH\CURD\CurdCreator`| This Class was built to make use of Proxy Pattern Factory Pattern|
| ApiHelper | extend from `Helilabs\HDH\ApiHelper\BaseApiHelper`| This Class was built to make provide more robust way when dealing with api|
| CurdController | extend from `Helilabs\HDH\Controllers\CurdController`| This Class was built utilize all previous classes when building CURD application|


### Usage
Since this package uses some basic & advanced software engineering concepts we though it would be good if we provided comrehensive documentations that discuss the concepts and the implementation
all documentation will be available on out blog and this will be updated regularly