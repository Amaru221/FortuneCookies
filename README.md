## Setup

To get it working, follow these steps:

**Download Composer dependencies**

Make sure you have [Composer installed](https://getcomposer.org/download/)
and then run:

```
composer install
```

You may alternatively need to run `php composer.phar install`, depending
on how you installed Composer.

**Database Setup**

The code comes with a `docker-compose.yaml` file and we recommend using
Docker to boot a database container. You will still have PHP installed
locally, but you'll connect to a database inside Docker. This is optional,
but I think you'll love it!

First, make sure you have [Docker installed](https://docs.docker.com/get-docker/)
and running. To start the container, run:

```
docker compose up -d
```

Next, build the database with:

```
# "symfony console" is equivalent to "bin/console"
# but it's aware of your database container
symfony console doctrine:database:create --if-not-exists
symfony console doctrine:schema:update --force
symfony console doctrine:fixtures:load
```

(If you get an error about "MySQL server has gone away", just wait
a few seconds and try again - the container is probably still booting).

If you do *not* want to use Docker, just make sure to start your own
database server and update the `DATABASE_URL` environment variable in
`.env` or `.env.local` before running the commands above.

**Start the Symfony web server**

You can use Nginx or Apache, but Symfony's local web server
works even better.

To install the Symfony local web server, follow
"Downloading the Symfony client" instructions found
here: https://symfony.com/download - you only need to do this
once on your system.

Then, to start the web server, open a terminal, move into the
project, and run:

```
symfony serve
```

(If this is your first time using this command, you may see an
error that you need to run `symfony server:ca:install` first).

Now check out the site at `https://localhost:8000`

Have fun!

**OPTIONAL: Webpack Encore Assets**

This app uses Webpack Encore for the CSS, JS and image files.
But, the built files are already included... so you don't need
to download or build anything if you don't want to!

But if you *do* want to play with the CSS/JS and build the
final files, no problem. Make sure you have [yarn](https://yarnpkg.com/lang/en/)
or `npm` installed (`npm` comes with Node) and then run:

```
yarn install
yarn encore dev --watch

# or
npm install
npm run watch
```


