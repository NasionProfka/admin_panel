# Symfony Project

This Symfony project is a simple application that manages users and groups. It allows you to create, edit, and delete users and groups, as well as assign users to multiple groups and view the groups associated with each user.

## Table of Contents

- [Installation](#installation)
- [Domain Model](#domain-model)
- [Database Model](#database-model)

## Installation

To run this Symfony project locally, follow these steps:

1. Clone the repository to your local machine.
2. Ensure you have PHP and Composer installed.
3. Run `composer install` in the project root directory to install the project dependencies.
4. Configure your database connection in the `.env` file.
5. Run `php bin/console doctrine:database:create` to create the database.
6. Run `php bin/console doctrine:migrations:migrate` to apply the database migrations.
7. Start the local development server by running `symfony server:start`.
8. Access the application in your browser at `http://localhost:8000`.

## Domain Model

The domain model for this project includes two main entities: User and Group. Users can belong to multiple groups, and groups can have multiple users. The many-to-many relationship between User and Group is represented as follows:

             +------------+
             |   User     |
             +------------+
             | - id       |
             | - name     |
             | - groups   |
             +------------+
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

The database model represents the many-to-many relationship between User and Group using a junction table. Here's an explanation of the tables and their columns:

- `user` table:
    - `id`: Primary key column for the user.
    - `name`: Column for the name of the user.

- `group` table:
    - `id`: Primary key column for the group.
    - `name`: Column for the name of the group.

- `user_group` table (junction table):
    - This table is automatically created by Doctrine to establish the many-to-many relationship between users and groups.
    - It has two columns: `user_id` and `group_id`, which are foreign keys referencing the `id` columns of the `user` and `group` tables respectively.

The junction table `user_group` allows you to associate users with groups and vice versa, enabling the many-to-many relationship between User and Group.

Feel free to explore the project and use it as a starting point for your own Symfony applications!

---

Update the README file with any additional information specific to your project, such as usage instructions, features, or deployment details.

Remember to keep the README file updated as the project evolves.
