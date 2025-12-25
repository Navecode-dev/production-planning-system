# Production Planning System

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![PHP Version](https://img.shields.io/badge/PHP-8.1%2B-blue)](https://www.php.net/)
[![Laravel](https://img.shields.io/badge/Laravel-10.x-red)](https://laravel.com/)

A web-based production planning and inventory optimization system designed to support data-driven decision making in production scheduling and raw material management. The system integrates multi-criteria decision analysis and inventory control techniques to assist operational planning.

## Table of Contents

- [Features](#features)
- [Decision & Optimization Methods](#decision--optimization-methods)
- [Tech Stack](#tech-stack)
- [Prerequisites](#prerequisites)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
- [Documentation](#documentation)
- [Contributing](#contributing)
- [License](#license)
- [Author](#author)
- [Support](#support)

## Features

- **Production Scheduling** - Schedule production based on product and store prioritization
- **Multi-Criteria Decision Making** - Utilize ELECTRE method for production priorities
- **Inventory Optimization** - Calculate optimal order quantities for raw materials
- **Reorder Point Analysis** - Automated reorder point and safety stock calculations
- **Configurable Criteria** - Flexible and structured decision criteria configuration
- **Data-Driven Insights** - Generate reports and analytics for operational planning

## Decision & Optimization Methods

This system implements proven operations research techniques:

- **ELECTRE (Elimination and Choice Expressing Reality)** - Multi-criteria decision analysis method for ranking and selecting alternatives
- **Economic Order Quantity (EOQ)** - Determines the optimal order quantity that minimizes total inventory costs
- **Reorder Point (ROP)** - Calculates when to place new orders based on lead time and demand
- **Safety Stock Calculation** - Determines buffer inventory levels to prevent stockouts

## Tech Stack

- **Backend Framework:** Laravel 10.x (PHP 8.1+)
- **Database:** MySQL 5.7+ / MariaDB 10.3+
- **Frontend:** Blade Templates with Bootstrap 5
- **Version Control:** Git
- **Dependency Management:** Composer

## Prerequisites

Before you begin, ensure you have met the following requirements:

- PHP >= 8.1
- Composer >= 2.0
- MySQL >= 5.7 or MariaDB >= 10.3
- Apache/Nginx web server
- Git

## Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/yourusername/production-planning-system.git
   cd production-planning-system
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Create environment file**
   ```bash
   cp .env.example .env
   ```

4. **Generate application key**
   ```bash
   php artisan key:generate
   ```

5. **Configure database**
   
   Edit the `.env` file with your database credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=production_planning
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

6. **Run database migrations**
   ```bash
   php artisan migrate
   ```

7. **Seed the database (optional)**
   ```bash
   php artisan db:seed
   ```

8. **Start the development server**
   ```bash
   php artisan serve
   ```

9. **Access the application**
   
   Open your browser and navigate to `http://localhost:8000`

## Configuration

### Environment Variables

Key configuration options in `.env`:

- `APP_NAME` - Application name
- `APP_ENV` - Environment (local, production)
- `APP_DEBUG` - Debug mode (true/false)
- `DB_*` - Database connection settings

### Additional Configuration

- Cache configuration: `config/cache.php`
- Database settings: `config/database.php`
- Application settings: `config/app.php`

## Usage

### Basic Workflow

1. **Set up products and stores** - Define your product catalog and store locations
2. **Configure decision criteria** - Set up the criteria weights for ELECTRE analysis
3. **Input demand data** - Enter or import demand forecasts
4. **Run production planning** - Generate optimal production schedules
5. **Calculate inventory levels** - Determine EOQ, ROP, and safety stock
6. **Review reports** - Analyze results and export data

### Example Use Case

The system helps production managers answer questions like:
- Which products should be prioritized for production?
- How much raw material should be ordered and when?
- What safety stock levels should be maintained?
- How can we optimize production scheduling across multiple stores?

## Documentation

Additional documentation is available in the `/docs` directory:

- System architecture diagrams
- Database schema documentation
- API documentation (if applicable)
- User manual
- Screenshots and feature guides

## Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

Please ensure your code follows the existing style and includes appropriate tests.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Author

**Evan Dian Fadilah**

- Email: hinavecode.dev@gmail.com
- LinkedIn: [Evan Dian Fadilah](https://www.linkedin.com/in/evan-dian-fadilah-1a2207265)

## Support

If you encounter any issues or have questions:

- Open an [issue](https://github.com/yourusername/production-planning-system/issues)
- Contact the author via email
- Check existing documentation in `/docs`

## Acknowledgments

This project demonstrates the practical implementation of decision support systems and inventory optimization techniques in production planning contexts. It was developed as part of my thesis.

---

**Note:** This is an academic/demonstration project showcasing operations research methods in production planning. For production use, additional security hardening and performance optimization may be required.
