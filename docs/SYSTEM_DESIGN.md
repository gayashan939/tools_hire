# Shelton Tool-Hire: System Design & Documentation

## 1. Project Overview
Shelton Tool-Hire is a prototype web application designed for a tool-rental company. It features a public-facing equipment catalogue with advanced search/filtering, a dynamic rental cost calculator, and a multi-criteria review system. The backend provides administrators with tools to manage inventory, pricing, and moderate customer content, ensuring a secure and professional user experience.

## 2. Functional Requirements
- **FR1: Public Catalogue**: Users can browse tools by category and search by name.
- **FR2: Rental Calculator**: System calculates costs based on hourly, daily, and weekly rates for user-selected dates.
- **FR3: Review System**: Users can rate tools across 7 criteria and leave comments.
- **FR4: Content Moderation**: Admin must approve reviews and comments before they appear publicly.
- **FR5: Admin Dashboard**: CRUD operations for tools, categories, and user management.
- **FR6: Analytics**: Admin can view metrics such as top-rated equipment and pending reviews.

## 3. Non-Functional Requirements
- **NFR1: Security**: Protection against SQLi, XSS, and CSRF; secure password hashing (BCRYPT).
- **NFR2: Performance**: Page load times < 2 seconds for the catalogue.
- **NFR3: Responsiveness**: Mobile-first design using CSS Flexbox/Grid or Bootstrap.
- **NFR4: Usability**: Intuitive navigation and clear error messaging for the calculator.

## 4. User Roles and Permissions
| Role | Permissions |
| --- | --- |
| **Guest** | Browse catalogue, search tools, view approved reviews, use rental calculator. |
| **Customer** | All Guest features + submit reviews and comments (requires login). |
| **Admin** | Manage tools/categories, moderate reviews/comments, view statistics, manage users. |

## 5. Database Schema (ERD Explanation)
The database follows a relational structure:
- **`categories`** (1:M) **`tools`**: One category contains many tools.
- **`tools`** (1:M) **`tool_images`**: A tool can have multiple images.
- **`tools`** (1:M) **`reviews`**: A tool receives multiple reviews.
- **`reviews`** (1:M) **`review_ratings`**: Each review stores ratings for 7 specific criteria.
- **`reviews`** (1:M) **`review_comments`**: Reviews can have nested comments (replies).
- **`users`** (1:M) **`reviews`**: Users author reviews.

## 6. SQL Table Creation Scripts
```sql
CREATE DATABASE shelton_hire;
USE shelton_hire;

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT
);

CREATE TABLE tools (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    hourly_price DECIMAL(10, 2),
    daily_price DECIMAL(10, 2),
    weekly_price DECIMAL(10, 2),
    availability_status ENUM('Available', 'Rented', 'Maintenance') DEFAULT 'Available',
    featured BOOLEAN DEFAULT FALSE,
    image_path VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin', 'customer') DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tool_id INT,
    user_id INT,
    overall_rating DECIMAL(3, 2),
    comment TEXT,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (tool_id) REFERENCES tools(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE review_ratings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    review_id INT,
    criterion VARCHAR(50),
    rating INT CHECK (rating BETWEEN 1 AND 5),
    FOREIGN KEY (review_id) REFERENCES reviews(id) ON DELETE CASCADE
);

CREATE TABLE review_comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    review_id INT,
    user_id INT,
    parent_comment_id INT NULL,
    comment TEXT,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (review_id) REFERENCES reviews(id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (parent_comment_id) REFERENCES review_comments(id)
);
```

## 7. Folder Structure
```text
/
├── assets/             # CSS, JS, Images
├── config/             # Database connection
├── includes/           # Shared components (Header, Footer, Helpers)
├── admin/              # Admin-only modules
│   ├── dashboard.php
│   ├── manage-tools.php
│   └── moderate-reviews.php
├── sql/                # SQL scripts
├── docs/               # System documentation
├── catalogue.php       # Tool listing
├── tool-detail.php     # Specific tool info + calculator
└── index.php           # Landing page
```

## 8. Frontend Page Descriptions
- **Homepage**: Hero section with search bar, "Featured" tool cards, and category quick-links.
- **Catalogue**: Sidebar filters (category, price range, rating) and a grid of tool listings.
- **Tool Detail**: Tabbed interface for "Description", "Hire Rates", and "Customer Reviews".
- **Rental Calculator**: Sticky sidebar element that updates total cost in real-time.

## 9. Backend/Admin Module Descriptions
- **Tool Manager**: Form for adding/editing tools with multi-image upload support.
- **Moderation Panel**: List of pending reviews/comments with "Approve" and "Reject" buttons.
- **User Management**: View and manage customer accounts and admin roles.

## 10. PHP Feature Implementation Examples
*Using PDO for secure queries:*
```php
$stmt = $pdo->prepare("SELECT * FROM tools WHERE category_id = :cat_id AND availability_status = 'Available'");
$stmt->execute(['cat_id' => $categoryId]);
$tools = $stmt->fetchAll();
```

## 11. Rental Calculator Logic
The logic determines the best price by combining weekly, daily, and hourly rates:
1. Calculate total hours between start and end.
2. `weeks = floor(total_hours / 168)`
3. `remaining_hours = total_hours % 168`
4. `days = floor(remaining_hours / 24)`
5. `hours = remaining_hours % 24`
6. `total_cost = (weeks * weekly_rate) + (days * daily_rate) + (hours * hourly_rate)`

## 12. Review Moderation Workflow
1. Customer submits review -> `status` set to `pending`.
2. Admin notified via dashboard alert.
3. Admin reviews content for profanity/relevance.
4. Admin clicks "Approve" -> `status` updated to `approved`.

## 13. Security Measures
- **SQLi**: All queries use PDO prepared statements.
- **XSS**: `htmlspecialchars()` used on all user-generated content.
- **Passwords**: `password_hash()` with `PASSWORD_DEFAULT`.
- **Sessions**: Secure session handling with ID regeneration.

## 14. Testing Documentation
- **Unit Tests**: Calculator logic (edge cases: same start/end time, month-end dates).
- **Functional Tests**: Review submission flow, image upload validation.
- **Security Tests**: Attempting XSS injection in review comments.

## 15. Suggested Future Improvements
- **Online Booking**: Integration with a payment gateway.
- **Live Inventory**: Real-time tracking of tool availability.
- **Email Notifications**: Automated alerts for review approvals.
