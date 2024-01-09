# Stageboard

Task management app just like kanban board

## Overview

Stageboard is a productivity tool app that can help you manage your task activity, like [kanban board](https://en.wikipedia.org/wiki/Kanban_board). Also, you can collaborate and share your tasks to others.

This project was built using [Laravel](https://laravel.com) and [ReactJS](https://react.dev/).

### Feature
- Board: You need a board to manage your tasks
- Column: Just like kanban, column is used to group your task into different state
- Card: This is your task that could be written to it.
![Board illustration](docs/img/board-illustration.png)
- Move Card
![Move card illustration](docs/img/move-card-illustration.png)
- Swap Column Order
![Swap column illustration](docs/img/swap-column-illustration.png)
- Collaboration with **realtime** changes and permission management
![Permission level](docs/img/permission-level.png)
![Permission table](docs/img/permission-table.png)

## Installation
This project was built using Laravel 10, so this requires at least **PHP 8.1** version to running perfectly. And need these things to support its features:
- NodeJS v20
- Composer
- RDBMS server. Preferred: [MySQL](https://www.mysql.com/) or [MariaDB](https://mariadb.org/)
- WebSocket server. For example: [Soketi](https://soketi.app/)
- Mail server. Real or test.

You can follow this [Laravel deployment guide](https://laravel.com/docs/10.x/deployment) and make sure to compile Javascript assets for frontend stuff with `npm run build`.

## Screenshot Preview

- Board page
![Board page screenshot](docs/img/board-page_screenshot.png)
- Home page
![Home page screenshot](docs/img/home-page_screenshot.png)
- Creating new board
![Creating new board screenshot](docs/img/showing-create-board-modal-in-board-page_screenshot.png)
- Collaboration settings
![Collaboration settings screenshot](docs/img/board-collaborations-settings-page_screenshot.png)
- Profile settings
![Profile settings screenshot](docs/img/profile-settings-page_screenshot.png)
