# Laravel PHP Framework

[![Build Status](https://travis-ci.org/laravel/framework.svg)](https://travis-ci.org/laravel/framework)
[![Total Downloads](https://poser.pugx.org/laravel/framework/d/total.svg)](https://packagist.org/packages/laravel/framework)
[![Latest Stable Version](https://poser.pugx.org/laravel/framework/v/stable.svg)](https://packagist.org/packages/laravel/framework)
[![Latest Unstable Version](https://poser.pugx.org/laravel/framework/v/unstable.svg)](https://packagist.org/packages/laravel/framework)
[![License](https://poser.pugx.org/laravel/framework/license.svg)](https://packagist.org/packages/laravel/framework)

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable, creative experience to be truly fulfilling. Laravel attempts to take the pain out of development by easing common tasks used in the majority of web projects, such as authentication, routing, sessions, queueing, and caching.

Laravel is accessible, yet powerful, providing tools needed for large, robust applications. A superb inversion of control container, expressive migration system, and tightly integrated unit testing support give you the tools you need to build any application with which you are tasked.

## Official Documentation

Documentation for the framework can be found on the [Laravel website](http://laravel.com/docs).

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](http://laravel.com/docs/contributions).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell at taylor@laravel.com. All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).

---

## FUTURE US

#### APP-WIDE
1. Switch 'GET_PointsFromPolygon1' from using '@embay_area = ea.polygon' --> 'COMPOSITE @subembays_area' for that embayment.
2. Where polygonal technologies are using the slider to set a percentage ('ground_percent'), there is currently a vue warning.
3. Need to explicitly define 'Show_In_wMVP' numbers in README.
4. During clicking for placement or population of technologies, disable reference layer ID-ing.

#### N ENTRANCE POINT SPECIFIC
1. Stormwater point technologies: parcels affected become additive, along with GET requests.
2. Stormwater point technologies: not displayed at the click location on the map after applied, but are removed from map after deleted.
3. Stormwater point technologies: For all sw point techs, on the 8th-10th instance added = 500 Internal Server Error.

#### EMBAYMENT-SPECIFIC
1. Pleasant Bay: Many subemebayments aren't selectable, masked by PB subembayment geometry.
