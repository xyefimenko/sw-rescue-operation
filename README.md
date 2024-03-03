
# SW Rescue Operation

Hey young Jedi! If you've found yourself lost on an unknown planet, don't panic. This solution is designed to help you determine your location and guide you on how to save your life.

This application leverages the power of the Star Wars API to fetch data about planets, residents, and species. It provides a simple paginated listing of the planets, and an API endpoint containing aggregated data about the planets. You can filter planets by diameter, rotation period, and gravity. You can also log your experiences and feelings in a logbook.

May the Force be with you on your journey!

## Start up the project

1. Prepare docker and ddev
2. Clone the repository
3. Run `ddev start` to start the project
4. Run `composer install` to install the dependencies
5. Run `ddev import-db --src=sql/db.sql.gz` to import the database or run `ddev ssh` and `php artisan migrate` to add an empty DB.
6. Run `npm install` to install the frontend dependencies
7. Run `npm run dev` to compile the frontend assets

Now you can access the project at `http://sw-rescue-operation.ddev.site/`.
For additional site info you can run `ddev describe`.

## Features

### Planets, residents and species import

- The application fetches data from the Star Wars API and imports it into the database. Run artisan command `php artisan sync:planets-and-residents` to import the data or just visit the homepage and trigger main button to trigger the import (this feature was added for convenience).
- The sync:planets-and-residents artisan command in this application fetches data about planets and their residents from the Star Wars API. It uses Guzzle's asynchronous requests to optimize the fetching of resident data. This allows the script to continue executing while waiting for the HTTP responses, reducing the total runtime of the script. 

### Paginated listing of the planets

- Just visit the `https://sw-rescue-operation.ddev.site/planets` or go here via site navigation to see the paginated listing of the planets.
- You can filter planets by diameter, rotation period, and gravity.
- Global planets search is available too. Global search will find results by all available fields in the table.
- It is possible to combine filters with one another. For example: you can filter planets by concrete diameter and gravity at the same time and get list of planets with exact attributes.
- Reset the filters if necessary clicked on the "Reset filters" button.

### Aggregated Data API endpoint

- The application provides an API endpoint at `https://sw-rescue-operation.ddev.site/api/planets` that returns aggregated data about the planets. The endpoint returns the following data:
  - List of names of 10 largest planets
  - Distribution of the terrain - how many planets have a certain terrain type
  - Distribution of the species living in all planets with percentage

### Logbook

- The application provides a logbook where you can log your experiences and notices about planets.
- You need to create `POST` request on `https://sw-rescue-operation.ddev.site/api/logbook` and send your notices (Postman was used for testing). Logbook data example: 
```json
  [
    {
        "planet_id": 57,
        "mood": "Excited",
        "weather": "Sunny",
        "gps_location": "50.8503, 4.3517",
        "note": "Just spotted a group of Ewoks, they seem friendly!"
    },
    {
        "planet_id": 58,
        "mood": "Nervous",
        "weather": "Rainy",
        "gps_location": "51.5074, 0.1278",
        "note": "It's a bit gloomy here. I heard a Wookiee in the distance."
    },
    {
        "planet_id": 59,
        "mood": "Thrilled",
        "weather": "Cloudy",
        "gps_location": "48.8566, 2.3522",
        "note": "Found an old Jedi temple, the Force is strong here!"
    }
]
```
- You can find added logbooks in the database table `logbooks`.

## TODOS

If you have time other than survival and want to contribute to the project, here are some ideas for improvement:

- Add logbook info for each planet in the planets listing
- Write tests for the application for the case of the attack of the Sith
- Current sync script adds or updates planets and residents. It would be nice to skip iteration if data are the same. You can achieve it via adding the data hash that checks if the data is the same and skip the iteration. Also, caching can be used for that.
- Add a feature for editing all necessary data on the frontend. Logbook creating or deleting for example

## May the Force be with you!
