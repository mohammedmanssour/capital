# Helilabs\Capital
Laravel package that provides set of classes to make use of common design patterns like (Repository Pattern, Factory Pattern).

### Why Built?
This package was built to get Helilabs start developing projects fast. and force a custom and smart way when building laravel applications.

### Why bother?


### Installation
* just require this package in your `composer file` like this
```
"helilabs/Capital" : "0.3.1"
```

### A Word about docmentation
This classes were mainly made for [Helilabs team](http://helilabs.com/) (because we think they're nice and effective) and you're free to use them in any way you want
but we offer no guarantee when you use them.

Right now we offer no documentaion for these classes so its on you to explore but we're working to write documentaion as soon as possible and we think it will be available on our site on 15-06-2017

Classes Are:

* **BaseRepository** : `Helilabs\Capital\Repository\BaseRepository` This Class was built to make use of Repository Pattern.

* **CurdFactory** : `Helilabs\Capital\CURD\CurdFactory` This Class was built to make use of Proxy Pattern, And Factory Pattern.

* **CurdCreator** : `Helilabs\Capital\CURD\CurdCreator` This Class was built to make use of Proxy Pattern Factory Pattern.

* **BaseApiTransformer** : `Helilabs\Capital\ApiHelper\BaseApiHelper` Create a “barrier” between source data and output, so schema changes do not affect users.

* **CurdController** : `Helilabs\Capital\Controllers\CurdController` This Class was built utilize all previous classes when building CURD application.