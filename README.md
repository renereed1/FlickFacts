# FlickFacts  

## Introduction  
FlickFacts is a project designed to assist a movie research company in analyzing trends in the movie industry. This solution helps the company better understand box office performance by tracking theater and movie sales data.  

## Project Structure  
```plaintext
FlickFacts  
├── backend  
│   ├── src  
│   │   ├── Theater  
│   │   │   ├── Interactor  
│   │   │   │   └── CreateTheater  
│   │   │   │       ├── CreateTheater.php  
│   │   │   │       └── CreateTheaterRequest.php  
│   │   │   ├── Domain  
│   │   │   │   ├── Entity  
│   │   │   │   │   └── Theater  
│   │   │   │   │       └── Theater.php  
│   │   │   │   └── ValueObject  
│   │   │   │       └── TheaterId.php  
│   │   │   ├── Infrastructure  
│   │   │   │   ├── Persistence  
│   │   │   │   │   ├── Repository  
│   │   │   │   │   │   ├── PostgresTheaterRepository.php  
│   │   │   │   │   │   └── DynamoDbTheaterRepository.php  
│   │   │   │   │   └── ReadModel  
│   │   │   │   │       ├── PostgresTheaterReadModel.php  
│   │   │   │   │       └── DynamoDbTheaterReadModel.php  
│   │   ├── Movie  
│   │   │   ├── Interactor  
│   │   │   │   └── CreateMovie  
│   │   │   │       ├── CreateMovie.php  
│   │   │   │       └── CreateMovieRequest.php  
│   │   │   ├── Domain  
│   │   │   │   ├── Entity  
│   │   │   │   │   └── Movie  
│   │   │   │   │       └── Movie.php  
│   │   │   │   └── ValueObject  
│   │   │   │       └── MovieId.php  
│   │   │   ├── Infrastructure  
│   │   │   │   ├── Persistence  
│   │   │   │   │   ├── Repository  
│   │   │   │   │   │   ├── PostgresMovieRepository.php  
│   │   │   │   │   │   └── DynamoDbMovieRepository.php  
│   │   │   │   │   └── ReadModel  
│   │   │   │   │       ├── PostgresMovieReadModel.php  
│   │   │   │   │       └── DynamoDbMovieReadModel.php  
│   └── tests  
├── frontend  
│   └── Vue3  
├── composer.json  
├── vendor  
└── phpunit.php  

```

## Backend
The backend is implemented using PHP and organized with Domain-Driven Design principles.
- **Theater**: Handles theater-specific logic, including entities and value objects.
- **Movie**: Handles movie-specific logic, including entities and value objects.

## Frontend
The frontend uses **Vue.js** to provide an interactive user interface for querying box office trends.