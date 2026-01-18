# Quotes API & Dashboard Integration

Implementation of a dynamic Quote of the Day feature using a dedicated API and Database table.

## Proposed Changes

### Database Layer

- **New Table**: `citacoes`
  - `id` (INT AUTO_INCREMENT PK)
  - `descricao` (TEXT)
  - `autor` (VARCHAR)
- **Data**: Insert 5-10 initial motivational quotes.

### API Layer

- **New File**: `api/citacao/index.php`
  - Connect to DB using `clsConexao`.
  - Query: `SELECT * FROM citacoes ORDER BY RAND() LIMIT 1`
  - Output: JSON headers + payload.

### View Layer

#### [MODIFY] [dashboard.php](file:///c:/xampp/htdocs/web/hevy/view/dashboard.php)

- Remove the hardcoded PHP array for quotes.
- Add JavaScript `fetch('/web/hevy/api/citacao')` on page load.
- Update the `.quote-text` and `.quote-author` elements with the response.

## Verification

1. **Access API**: Go to `http://localhost/web/hevy/api/citacao` and check for JSON response.
2. **Dashboard**: Refresh dashboard and verify a new quote loads each time without error.
