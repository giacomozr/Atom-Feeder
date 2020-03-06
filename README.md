# Atom Feeder

**This is a proof of concept**

Atom Feeder is a simple PHP script that will allow to scrape a HTML page to find articles who will be transformed in an Atom Feed.

**Require:**
* PHP 7.3

## Getting started

Clone the project 

```sh
$ git clone https://github.com/giacomozr/Atom-Feeder.git
```

Download dependencies
```sh
$ composer install
```

Launch scripts
```sh
$ cd atom-feeder
$ php ./app/index.php [options]
```

## Options
Here the supported options:

| Option           | Description                                                                   |
| ---------------- | ----------------------------------------------------------------------------- |
| -u OR --url      | Url to be parsed                                                              |
| -e OR --elements | Elements which contains anchor tags to the articles (Default: "article h1 a") |
| -o OR --output   | Output file path                                                              |
| -h OR --help     | Commands                                                                      |

## Docker
A Docker image is bundled with this repository, it will allow to launch the Atom Feeder in a virtualized environment.

Build the image
```sh
$ cd atom-feeder
$ docker build -t atom-feeder .
```

Run the container, make sure to create a volume to retrieve the output file.

Use something like
```sh
$ docker run -v ~/host/:/usr/src/app/feeds/:rw -it --rm --name running-atom-feeder atom-feeder -uhttps://example.invalid -e"article h1 a"
```

## Test
Basic tests to check everything is working fine

```sh
$ ./vendor/bin/phpunit tests
```

