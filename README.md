## Local development

### Build
`docker compose build`

### Run locally
`docker compose up -d`

### Recreate local database
`docker compose down db --volumes && docker compose up db -d`

### Environment
create `.env` from `.env.example` 