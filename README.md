# FlickFacts

## Introduction

FlickFacts is a **Franchise** with locations throughout **California**, with most franchises opening on **12/30/2024**.
The system tracks sales data for multiple theaters, allows the addition of new franchises and movies, and offers
flexible pricing for movies across different theaters. Each movie can be sold at various prices depending on the theater
location.

## Database Schema

The database schema is designed to handle core business operations around theaters, movies, tickets, and sales. The
schema is as follows:

### Core Entities:

- **Theaters**: Stores information about each theater.
- **Movies**: Contains data about the movies, such as title.
- **Tickets**: Represents ticket containers, where each ticket is tied to a specific movie and theater. It contains
  information on the number of tickets available for sale and how many tickets have been sold.
- **Sales**: Records the actual sale transactions of tickets, including quantities sold, date of sale, and related
  theater/movie.

### Ticket-Sales Relationship:

- **Tickets** act as a container for potential sales, indicating how many tickets are available for a given movie in a
  specific theater.
- **Sales** represent the transaction when a ticket is sold, and they track the actual sale data.

To optimize common queries, an additional index is applied to the **Tickets** table, which supports efficient lookups
based on the `TheaterId`, `MovieId`, and `Available` fields. This ensures fast retrieval of tickets that are available
for sale and associated with specific theaters and movies.

---

## About the Project

This system provides a comprehensive solution for tracking movie ticket sales across theaters. It’s designed with a
clean separation of concerns, allowing for scalability, flexibility, and maintainability. Key architectural choices
include:

- **Frontend**: The frontend provides a minimalistic approach to user interactions and relies on backend validation to
  ensure data integrity. Frontend validation serves as an optimization, but the enforcement of business rules is a
  concern handled within the Domain layer on the backend.

- **Backend**: All validation logic is enforced on the backend within the domain layer, ensuring that business rules are
  consistently applied regardless of the frontend.

- **Data Layer**: The data layer is divided into two main components:
    - **Repositories**: These operate on **Aggregates** to manage the core entities, ensuring consistency and business
      logic adherence.
    - **ReadModels**: Optimized for queries and read-heavy operations, allowing efficient retrieval of data for display
      and reporting purposes.

- **Single Database**: The system uses a single PostgreSQL database for both transactional data (via Repositories) and
  read-optimized views (via ReadModels). This setup simplifies deployment and management, though it could be extended to
  support more complex architectures where repositories and read models are isolated into different databases if needed.

This architecture strikes a balance between consistency (via domain logic in Repositories) and performance (via
optimized ReadModels), making it suitable for both small-scale and large-scale applications.

## TLDR

---

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

The backend of **FlickFacts** is implemented in **PHP** and follows **Domain-Driven Design (DDD)** principles. The
project is organized into clear domains that focus on the business logic for theaters, movies, and sales. This
architecture allows for flexibility and scalability while maintaining a clean separation of concerns.

### Theater

The **Theater** module is responsible for managing all logic related to theater franchises within the **FlickFacts**
system. This includes defining entities, value objects, and handling the business rules associated with each theater.

#### Key components:

- **Entity**:
    - `Theater`: The main entity that represents a theater in the FlickFacts system. It holds business-critical
      information about the theater, such as its name, location, and unique identifier.

- **Value Object**:
    - `TheaterId`: An immutable value object that represents the unique identifier for each theater. It ensures that the
      identifier is consistent and cannot be changed once assigned.

- **Interactor**:
    - `CreateTheater`: The interactor handles the process of creating a new theater. It validates the necessary data and
      interacts with the repository to persist the new theater in the database.

- **Infrastructure**:
    - **Repository**: The `PostgresTheaterRepository` and `DynamoDbTheaterRepository` provide implementations for
      storing and retrieving theater data from different databases (PostgreSQL and DynamoDB, respectively).
    - **ReadModel**: The `PostgresTheaterReadModel` and `DynamoDbTheaterReadModel` are used for optimized queries,
      fetching data in the appropriate format for the UI or reporting purposes.

### Movie

The **Movie** module handles all logic related to movies, including adding new movies, managing their attributes, and
associating them with theaters for sale.

#### Key components:

- **Entity**:
    - `Movie`: Represents a movie in the system, storing key information such as the title, release date, and unique
      identifier.

- **Value Object**:
    - `MovieId`: An immutable value object that represents the unique identifier for each movie, ensuring data integrity
      and consistency across the application.

- **Interactor**:
    - `CreateMovie`: This interactor is responsible for the creation of a new movie, validating the movie details, and
      interacting with the repository to persist the new movie.

- **Infrastructure**:
    - **Repository**: The `PostgresMovieRepository` and `DynamoDbMovieRepository` handle data persistence for movies.
      They interact with the respective databases (PostgreSQL or DynamoDB) to store and retrieve movie data.
    - **ReadModel**: The `PostgresMovieReadModel` and `DynamoDbMovieReadModel` are used for optimized read operations,
      providing efficient querying of movie data for display and reporting.

### Key Features of the Backend:

- **Separation of concerns**: Each domain (Theater and Movie) is well encapsulated, allowing for easy expansion or
  modification without impacting the other domains.
- **Flexibility**: The system supports different databases (PostgreSQL and DynamoDB) for both storage and read models,
  making it adaptable to different environments and use cases.
- **Scalability**: By applying Domain-Driven Design, the backend can easily accommodate additional modules or features
  in the future, such as integrating with new data sources or adding new business logic.

## Frontend

The frontend uses **Vue.js** to provide an interactive user interface for interacting with the system.