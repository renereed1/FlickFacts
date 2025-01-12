# Flick Facts Franchise

## Introduction

Flick Facts is a **Franchise** with locations throughout **California**, with the first theater opening on
**12/30/2024**. The system tracks sales data for multiple theaters, supports the addition of new theaters and movies,
and offers flexible pricing for movies across different theater locations. Each movie can be sold at varying prices
depending on the theater. Additionally, a pre-configured **10% discount** can be applied to ticket sales.

## Architecture ##

FlickFacts is designed using **Domain-Driven Design (DDD)** principles and implemented with **Clean Architecture** and
**Test-Driven Development (TDD)**. The system is modular and organized around the following domains:

### Core Domain ###

The **Theater** domain serves as the core of FlickFacts, encompassing the essential business logic and operations.

#### Subdomains ####

The **Theater** domain is further divided into two subdomains:

- **Sales**: Handles ticket sales and related transactions.
- **Tickets**: Manages ticket generation, validation, and tracking.

### Supporting Domains ###

FlickFacts also includes two supporting domains that complement the core domain:

- **Movie**: Focuses on movie metadata.
- **Reporting**: Provides analytics and reporting features for tracking performance and sales.

This architectural design ensures scalability, flexibility, and a clear separation of concerns, enabling seamless
expansion and maintenance of the system.

## Project Structure

```plaintext
FlickFacts
├── backend
│   ├── src
│   │   ├── Common
│   │   ├── Movie
│   │   ├── Reporting
│   │   └── Theater
│   │       ├── Application
│   │       │   └── Service
│   │       ├── Sales
│   │       └── Tickets
├── frontend
│   └── Vue3
├── sql
└── config
```

## Database Schema

The database schema is designed to handle core business operations around theaters, movies, tickets, and sales. The
schema is as follows:

### Core Entities:

- **Theaters**: Stores information about each theater.
- **Movies**: Contains data about the movies, such as title.
- **Tickets**: Represents ticket containers, where each ticket is tied to a specific movie and theater. It contains
  information on the number of tickets available for sale and how many tickets have been sold.
- **Sales**: Records the actual sale transactions of tickets, including quantities sold, date of sale, related
  theater/movie and applied discount.

### Ticket-Sales Relationship:

- **Tickets** act as a container for potential sales, indicating how many tickets are available for a given movie in a
  specific theater.
- **Sales** represent the transaction when a ticket is sold, and they track the actual sale data.

To optimize common queries, an additional index is applied to the **Tickets** table, which supports efficient lookups
based on the `TheaterId`, `MovieId`, and `Available` fields. This ensures fast retrieval of tickets that are available
for sale and associated with specific theaters and movies.

## About the Project

This system provides a comprehensive solution for tracking movie ticket sales across theaters. It is designed with a
clean separation of concerns, leveraging both **Application-specific business logic** and **Domain-independent business
logic** to ensure flexibility, scalability, and maintainability.

### Separation of Concerns

- **Application-specific Business Logic**:  
  Application-specific business rules are implemented within the **Interactors** and **Application Services**. These
  layers orchestrate the interaction between the domain layer and other system components, ensuring that business
  workflows, such as ticket sales and reporting, are handled consistently and efficiently.

- **Domain-independent Business Logic**:  
  Core business logic that is independent of the application flow is encapsulated within the **Domain Layer**. This
  includes entities, value objects, and aggregates that embody the fundamental rules and constraints of the system. By
  isolating this logic, the domain remains reusable and adaptable to different contexts without modification.

### Support for Optimistic Locking

The system is designed with the foundational logic to support **Optimistic Locking** by incorporating versioning
mechanisms in the **AggregateRoot**. While the locking logic is not fully implemented in this example, the groundwork is
laid for handling concurrent updates to aggregates, ensuring data consistency in future extensions of the system.

### Rich Domain Model

FlickFacts employs a **Rich Domain Model**, where entities and aggregates encapsulate both data and behavior. Instead of
treating domain objects as passive data containers, the system ensures that all domain-specific operations and state
transitions are governed by the entities themselves. This approach results in:

- A more intuitive and expressive representation of the business domain.
- Improved consistency by enforcing domain rules directly within the entities.
- Enhanced testability, as domain logic is isolated and can be validated independently of the application flow.

### Architectural Choices

The architectural design emphasizes the following principles:

- **Frontend-Backend Separation**: While the frontend offers basic validation for an improved user experience, all
  critical validation and enforcement of business rules occur in the backend's domain layer. This ensures consistency
  and integrity, regardless of the client implementation.
- **Repositories and ReadModels**: Repositories manage aggregates and enforce domain rules, while ReadModels are
  optimized for query operations. This separation enables high performance and flexibility for both transactional and
  analytical workflows.
- **Single Database**: A single PostgreSQL database serves both transactional needs and read-heavy operations. While
  simple, this setup can be extended to support more advanced architectures, such as separate databases for write and
  read models, as the system scales.
- **Discount Services**: The current implementation uses a hard-coded 10% discount applied to ticket sales. This design
  is modular and could be extended in the future to include a more flexible discount system, allowing the creation and
  management of discounts dynamically. This could include, but is not limited to:
    - Time of day discounts (Monday - Friday before 5PM)
    - Discounts for elderly people
    - Children under the age of 2 are free
    - Disabled discounts
    - Coupons, promotional codes, and other flexible discount types.

This carefully crafted architecture balances simplicity, performance, and robustness, making it a strong foundation for
future growth and feature development.

## Frontend

The frontend uses **Vue3** to provide an interactive user interface for interacting with the system.