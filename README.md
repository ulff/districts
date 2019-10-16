# FIND YOUR DISTRICT

You can see my apllication on Heroku here: https://safe-sierra-72627.herokuapp.com or download and install from git.
 ___

## Setup

Clone project from GitHub:

`git clone git@github.com:Dorkis/districts.git`

Go into project directory and run docker 

`docker-compose up -d`

after that you need to log to machine

`docker exec -ti -u dev yourdistrict_php_1 bash`

and install dependencies with composer 

`composer install -n`

and udate database

`php vendor/bin/doctrine orm:schema-tool:update -f`

then you can visit 

`http://localhost:8080/api/download/all`

and download all districts from Gdańsk and Kraków.


## Using application 

After install visit http://localhost:8080/districts and enjoy.

You can use this application also like API.
All requests must be sent with header 'Accept: application/json'

To insert all districts from Gdańsk and Kraków you have to go to url:

`http:://localhost:8080/api/download/all`

To add new district you have to go to url:

`http:://localhost:8080/api/cities/{cityName}/districts`

where cityName is Kraków or Gdańsk. You must use POST method and in body you have to put for example:

```
{
    "districts": {
        "name": "New district name ",
        "population": 2000,
        "area": 2.3,
        "city": "Gdańsk"
    }
}
```

To update district you have to go to url:

`http:://localhost:8080/api/cities/{cityName}/districts`

where cityName is Kraków or Gdańsk. You must use PUT method and in body you have to put for example:

```
{
    "districts": {
        "name": "Update name",
        "population": 2000,
        "area":2.3,
        "city": "Gdańsk",
        "districtId" : 54
    }
}
```

To delete district you have to go to url:

`http:://localhost:8080/api/cities/{cityName}/districts/{districtId}`

where cityName is Kraków or Gdańsk. You must use DELETE method districtId is id witch you want to delete.

To sort districts you have to go to url:

`http://localhost:8080/api/cities/{cityName}/districts?population=10|15&area=3|5&sort=population&order=asc`

where cityName is Kraków or Gdańsk. In parameters you can choose options.
sort: population, area, name
order: asc, desc

Population and area you can use like this:
population=FROM|TO
area=FROM|TO
