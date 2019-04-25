# Watershed MVP 3.1

> The Cape Cod Commission developed the WatershedMVP application for professionals, municipal officials and community members in order to assist in creating the most cost-effective and efficient solutions to Cape Cod’s wastewater problem. The application is an informational resource intended to provide regional estimates for planning purposes. WatershedMVP is an initiative of the Cape Cod Commission’s Strategic Information Office (SIO). 

## Dependencies
* [Docker](https://www.docker.com/)
* [Laravel](https://laravel.com/)
* [ArcGIS Javascript API](https://developers.arcgis.com/javascript/)
* [jquery](https://jquery.com/)
* [D3js](https://d3js.org/)

## Docker Build Setup
```bash
# Change working directory to project path
cd /path/to/project

# Run docker-compose to start apache/php container
sudo docker-compose up
```


## Manual Build Setup
```bash
# SSH into Apache server
sudo ssh user@host

# CD to exposed directory
cd /var/www/html

# Pull project code into directory
git pull https://github.com/capecodcommission/watershed-mvp.git
```

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

#### Treatment Technology Stack
1. Any updated or deleted tech should reapply downstream techs.
    Example 1: User applies 5 techs, then updates tech #2. Techs 3-5 should reapply based on tech 2's updated nload totals.
    Example 2: User applies 8 techs, then deletes tech #1. Remaining 7 should reapply.

#### N ENTRANCE POINT SPECIFIC
1. Stormwater point technologies: parcels affected become additive, along with GET requests.
2. Stormwater point technologies: not displayed at the click location on the map after applied, but are removed from map after deleted.
3. Stormwater point technologies: For all sw point techs, on the 8th-10th instance added = 500 Internal Server Error.

#### EMBAYMENT-SPECIFIC
1. Pleasant Bay: Many subemebayments aren't selectable, masked by PB subembayment geometry.
