<!-- How to make migration? -->

<!-- Set the migration path in your database configuration -->

// knexfile.js
export default {
---development: {
------client: "mysql2", // atau pg/sqlite3
------connection: {
---------host: "127.0.0.1",
---------user: "root",
---------password: "passwordmu",
---------database: "namadb"
------},
------migrations: {
---------directory: "./migrations"
------}
---}
};

<!-- Make a migration -->

npx knex migrate:make create_users_table --knexfile knexfile.js

<!-- Migration example -->

// migrations/20250920123456_create_users_table.js
export async function up(knex) {
---return knex.schema.createTable("users", (table) => {
------table.increments("id").primary();
------table.string("name").notNullable();
------table.string("email").unique().notNullable();
------table.string("password").notNullable();
------table.timestamps(true, true); // created_at, updated_at
---});
}

export async function down(knex) {
---return knex.schema.dropTable("users");
}

<!-- Run migration -->

npx knex migrate:latest --knexfile knexfile.js

<!-- Run last migration -->

npx knex migrate:rollback --knexfile knexfile.js
