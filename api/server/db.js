import knex from 'knex'

export const db = knex(
    {
        client: 'mysql2',
        connection: {
            host: 'localhost',
            user: 'root',
            password: '',
            database: 'rainergy'
        },
        migrations: {
            directory: "./migrations"
        }
    }
)