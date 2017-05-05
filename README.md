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

### A Word about docmentation
This classes were mainly made for Helilabs team (because we're think they're nice and effective) and you're free to use them in any way you want
but we offer no guarantee when you use them.
Right now we offer no documentaion for these classes so its on you to explore but we're working to write documentaion as soon as possible and we think it will be available on our site on 15-05-2017
Classes Are:
**BaseRepository** : `Helilabs\HDH\Repository\BaseRepository` This Class was built to make use of Repository Pattern.
**CurdFactory** : `Helilabs\HDH\CURD\CurdFactory` This Class was built to make use of Proxy Pattern, And Factory Pattern.
**CurdCreator** : `Helilabs\HDH\CURD\CurdCreator` This Class was built to make use of Proxy Pattern Factory Pattern.
**BaseApiHelper** : `Helilabs\HDH\ApiHelper\BaseApiHelper` This Class was built to make provide more robust way when dealing with api.
**CurdController** : `Helilabs\HDH\Controllers\CurdController` This Class was built utilize all previous classes when building CURD application.