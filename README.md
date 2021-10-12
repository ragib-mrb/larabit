<p  align="center"><img  src="https://user-images.githubusercontent.com/36569722/136689176-8f0d31aa-f543-461c-a545-5c425231bb4e.png"  width="400"></p>

## About LaraBit

Have you ever wonder to create Laravel views (Blade Templates) using the same type of artisan commands that you usually use to create new controllers, models and migrations etc.

LaraBit extends the power of Laravel Artisan tool and give you the command line interface to create new blade templates really easy.

## Installation

install it with [composer](https://getcomposer.org/)

    composer require furious-developer/lara-bit

## Getting Started

### Create new blade template:

To create new blade template at the root of the view folder use the command mention below.

    php artisan make:view <NameOfTheTemplate>

**Example:**

if we want to create the "index.blade.php" inside the view folder then use this command and just specify the file name without any file extension.

    php artisan make:view index

This command will create the index.blade.php file inside view folder and LaraBit will also output the Absolute Path of the file on the console.

### Define template type option:

LaraBit comes with 2 type of Templates
- **basic** (Default Type) : It will generate the template with basic HTML code.
- **advance** : It will generate the template with HTML code and also include some useful HTML tags as well.

**commands:**

    php artisan make:view index1 --type basic
---- or ----

    php artisan make:view index2 --type advance

**or use shortcut**

    php artisan make:view index3 -t basic
---- or ----

    php artisan make:view index4 -t advance

### Create new Blade Template inside Single/Multiple Folders

In order to create new blade template inside folder prefix the filename with the folder name and dot (.)

LaraBit use the same syntax that **view()** function use to call a view.
  
    php artisan make:view layout.index

-- define more folder lavel in same way

    php artisan make:view folder1.folder2.index

If the Folder doesn't exists then It will create the new Folder(s). If you receive any error after running the above command then change the file permission of the views folder or Manually create the require folders and then run the command again.

## Modify Templates

Sometimes we wish to Modify the templates that comes with the LaraBit. we can easily do that by follow the bellow steps.

**run this command**

    php artisan vendor:publish

**Then find the line mention below on the console and press the number that is written in-front of it**

    Provider: FuriousDeveloper\LaraBit\LaraBitServiceProvider

It will generate the basic.blade.php and advance.blade.php inside the "\resources\views\vendor\larabit" folder. Modify the template as per your requirements.

## Add & Use Custom Templates

Sometimes we wish to use our own templates instead of using the templates that comes with LaraBit.

Follow the **Modify Template** Steps then inside "\resources\views\vendor\larabit" place your blade template that you want to use.

**Example:**

if we wanted to use example.blade.php then we have to place/create this template inside "\resources\views\vendor\larabit" folder then to generate new blade file using your template use the command mention below.

    php artisan make:view index6 --type example
---- or ----

    php artisan make:view index7 -t example



