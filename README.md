# Symfony Project

This is a Symfony project that allows you to manage users and groups. It provides functionality for creating, editing, and deleting users and groups, as well as assigning users to groups and removing them from groups.

## Domain Model

The domain model for this project consists of two main entities: User and Group. Users can be associated with multiple groups, and groups can have multiple users. Here is a simplified representation of the domain model:

                    +------------+
                    |   User     |
                    +------------+
                    | - id       |
                    | - name     |
                    | - groups   |
                    +------------+
                         |        
                         |
                         |  N       
                         |
                    +------------+
                    |   Group    |
                    +------------+
                    | - id       |
                    | - name     |
                    | - users    |
                    +------------+

## Database Model

The database model for this project is implemented using Doctrine ORM, which maps the entities to database tables. The database schema includes two tables: `user` and `group`. Here is a brief explanation of the tables and their columns:

- `user` table:
    - `id`: Primary key column for the user.
    - `name`: Column for the name of the user.

- `group` table:
    - `id`: Primary key column for the group.
    - `name`: Column for the name of the group.

- `user_group` table (join table):
    - This table is automatically created by Doctrine to establish a many-to-many relationship between users and groups.
    - It has two columns: `user_id` and `group_id`, which are foreign keys referencing the `id` columns of the `user` and `group` tables respectively.

## Installation and Setup

1. Clone the repository.
2. Install the project dependencies by running `composer install`.
3. Configure your database connection in the `.env` file.
4. Create the database by running `php bin/console doctrine:database:create`.
5. Run the database migrations to create the necessary tables by running `php bin/console doctrine:migrations:migrate`.
6. Start the development server by running `symfony server:start`.
7. Access the application in your browser at `http://localhost:8000`.

## Usage

- Navigate to the user management page to create, edit, and delete users.
- Navigate to the group management page to create, edit, and delete groups.
- Users can be assigned to groups or removed from groups using the provided functionality.

## Contributing

Contributions are welcome! If you find any issues or have suggestions for improvements, please open an issue or submit a pull request.

## License

This project is not licensed (:.
