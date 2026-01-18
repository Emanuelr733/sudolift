# SudoLift Premium Dashboard (Refined + API)

I have updated the `dashboard.php` to better align with the SudoLift "Routine Manager" focus and added dynamic content.

## Changes

### 1. Refined Metrics

- **Rotinas Salvas**: Replaced "Total Treinos". Now accurately reflects the number of routines you have stored in the database.
- **Biblioteca**: Added a quick link to "Ver Exercícios" to explore the library.

### 2. Simplified UI

- Removed the "Nível Atual" (Beginner) card.
- Removed the "Pro" promotional banner.
- Layout cleaned up and scrolling fixed.

### 3. Dynamic Quotes API

- **Database**: Created a `citacoes` table populated with motivational quotes.
- **API**: Implemented `api/citacao/index.php` that returns a random quote in JSON format.
- **Dashboard**: The quote card now fetches a fresh quote on every page load via JavaScript.

## Gallery

### Dynamic Quote Card

> [!NOTE]
> The quote is no longer hardcoded in PHP but fetched asynchronously.

```javascript
fetch(".../api/citacao/")
  .then((res) => res.json())
  .then((data) => {
    // Updates DOM with new quote
  });
```
