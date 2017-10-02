# Helilabs\Capital

Laravel package that provides set of classes to make use of common design patterns like (Repository Pattern, Factory Pattern).

### Why Built?

This package was built to get Helilabs start developing projects fast. and force a custom and smart way when building laravel applications.

### Why bother?

we've seen many packages that provide what we provides here but the main problem is that other packages has so many configuration with less flexibility.

we at Helilabs build ERPs and a lot of CurdApplications so we think that making use of theses classes will make your development workflow faster and more organized.

### Installation
* just require this package in your `composer file` like this
```
"helilabs/Capital" : "0.4"
```

### A Word about docmentation
This classes were mainly made for [Helilabs team](http://helilabs.com/) (because we think they're nice and effective) and you're free to use them in any way you want
but we offer no guarantee when you use them.

Right now we offer no documentaion for these classes so its on you to explore ( There are tests you can read to understand how classes work ) but we're working to write documentaion as soon as possible and we think it will be available on our site on 15-10-2017

Classes Are:

* **BaseRepository** : `Helilabs\Capital\Repository\BaseRepository` helps developers to use `Repository Pattern` effectivly.

* **CurdFactory** : `Helilabs\Capital\CURD\CurdFactory` helps to make use of `Model Factory Pattern` with a lot of flexibility provided in the form of `CallbackHandler`.

* **CurdController** : `Helilabs\Capital\Controllers\CurdController` Makes building CURD Controllers fast and easy.

*  **StdClass** :  generates stdClass objectss from an array with an eye on performance 'because we love objects more than arrays'.

* **CallbackHandler** : makes use of callbacks to provide better flexibility for the Single Responsibility Classes.

