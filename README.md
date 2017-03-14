Laravel postgis extension
=========================

[![Build Status](https://travis-ci.org/njbarrett/laravel-postgis.svg?branch=master)](https://travis-ci.org/njbarrett/laravel-postgis.svg?branch=master)
[![Code Climate](https://codeclimate.com/github/njbarrett/laravel-postgis/badges/gpa.svg)](https://codeclimate.com/github/njbarrett/laravel-postgis)
[![Coverage Status](https://coveralls.io/repos/github/njbarrett/laravel-postgis/badge.svg?branch=master)](https://coveralls.io/github/njbarrett/laravel-postgis?branch=master)

# Solemnly declare
 * This is based on [Phaza\Laravel-Postgis](https://github.com/njbarrett/laravel-postgis) to modify Non-original;

## 修改位置

1. 修改了readme.md 关于lumen5.4 安装的注册服务，需要注册两个服务(见下文)。
2. 添加了数据迁移命令`$table->geometry('geom')`;


## Features
 * Work with geometry classes instead of arrays. (`$myModel->myPoint = new Point(1,2)`)
 * Adds helpers in migrations. (`$table->polygon('myColumn')`)
 
### Future plans
 
 * Geometry functions on the geometry classes (contains(), equals(), distance(), etc… (HELP!))

## Versions

- Use for Laravel `5.3.*`;`5.4.*`
- Use for lumen `5.3.*`;`5.4.*`


## Installation

    composer require shaozeming/lumen-postgis 'dev-master'


laravel  Next add the DatabaseServiceProvider to your `config/app.php` file.
```
    'Phaza\LaravelPostgis\DatabaseServiceProvider',
```
That's all.

Lumen  Next add the DatabaseServiceProvider to your `bootstrap/app.php` file.

```
     $app->register(Bosnadev\Database\DatabaseServiceProvider::class);   //多添加这个后，就可以解决问题。
     $app->register(Phaza\LaravelPostgis\DatabaseServiceProvider::class);
```
That's all.

## Usage

First of all, make sure to enable postgis.

    CREATE EXTENSION postgis;

To verify that postgis is enabled

    SELECT postgis_full_version();

### Migrations

Now create a model with a migration by running

    php artisan make:model Location

If you don't want a model and just a migration run

    php artisan make:migration create_locations_table

Open the created migrations with your editor.

```PHP
use Illuminate\Database\Migrations\Migration;
use Phaza\LaravelPostgis\Schema\Blueprint;

class CreateLocationsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locations', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('name');
            $table->string('address')->unique();
            $table->point('location');
            $table->geometry('geom');
            $table->polygon('polygon');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('locations');
    }

}
```

Available blueprint geometries:

 * point
 * geometry
 * multipoint
 * linestring
 * multilinestring
 * polygon
 * multipolygon
 * geometrycollection

other methods:

 * enablePostgis
 * disablePostgis

### Models

All models which are to be PostGis enabled **must** use the *PostgisTrait*.

You must also define an array called `$postgisFields` which defines
what attributes/columns on your model are to be considered geometry objects.

```PHP
use Illuminate\Database\Eloquent\Model;
use Phaza\LaravelPostgis\Eloquent\PostgisTrait;
use Phaza\LaravelPostgis\Geometries\Point;

class Location extends Model
{
    use PostgisTrait;

    protected $fillable = [
        'name',
        'address'
    ];

    protected $postgisFields = [
        'location',
        'polygon',
    ];

}

$location1 = new Location();
$location1->name = 'Googleplex';
$location1->address = '1600 Amphitheatre Pkwy Mountain View, CA 94043';
$location1->location = new Point(37.422009, -122.084047);
$location1->save();

$location2 = Location::first();
$location2->location instanceof Point // true
```

Available geometry classes:
 
 * Point
 * Geometry
 * MultiPoint
 * LineString
 * MultiLineString
 * Polygon
 * MultiPolygon
 * GeometryCollection
