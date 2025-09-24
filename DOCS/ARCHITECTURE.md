# Architecture

## Technology Stack

### Backend
- **Laravel 10.x** - PHP web framework
- **PHP 8.3+** - Server-side language
- **MySQL 8.0** - Primary database
- **SQLite** - Testing database (in-memory)

### Frontend
- **Vue.js 2.7** - JavaScript framework (not Vue 3)
- **Vite** - Build tool and dev server
- **Tailwind CSS** - Utility-first styling
- **Vuex 4** - State management
- **Axios** - HTTP client
- **Ziggy** - Laravel routes in JavaScript

### Infrastructure
- **Nginx/Apache** - Web server
- **Composer** - PHP dependency management
- **NPM** - JavaScript package management
- **Laravel Telescope** - Debug assistant (optional)

## System Design

### Core Components

**Study System**
- Research project management
- Question configuration (Pre-Sort/Post-Sort)
- Token definition and management

**Interview Engine**
- Sorting session handler
- Multiple sorting types: Network, Circle, Q-Sort
- Real-time position tracking

**Token Manager**
- Text and image token support
- Drag-and-drop positioning
- Size and appearance customization

**Sorting Storage**
- Position persistence
- Results export functionality
- Interview state management

### Data Flow

1. **Project Creation**: Researcher creates Study with configuration
2. **Token Setup**: Define sortable items (text/images)
3. **Interview Access**: Participants access via public URLs
4. **Sorting Session**: Real-time token arrangement and position tracking
5. **Data Storage**: Positions and answers saved to database
6. **Results Export**: Data extraction for analysis

## Key Design Decisions

### Backend Architecture
- **Models in `app/`**: Legacy structure maintained for compatibility
- **No queue system**: Synchronous processing for simplicity
- **SQLite for testing**: Zero-configuration test database

### Frontend Architecture
- **Global component registration**: All Vue components registered globally in `components.js`
- **Single Vuex store**: Centralized state in `store/index.js`
- **Vue 2.7**: Maintained for stability (not migrated to Vue 3)

### Custom Implementations
- **Custom Tailwind breakpoints**: `qsortxs`, `qsortsm`, `qsortmd`, `qsortlg` for Q-Sort layouts
- **Legacy naming**: "Study" internally, "Projects" in UI
- **Public URL system**: Custom short URL generation for interviews

## Technical Constraints

- PHP 8.3+ required
- MySQL 8.0+ for production
- Node.js 18+ for build tools
- Vue 2.7 (not compatible with Vue 3)
