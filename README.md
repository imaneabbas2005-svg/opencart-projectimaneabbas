OpenCart 1.5.6.4 Customization Project
Project Overview
This project involved customizing OpenCart 1.5.6.4 (a legacy e-commerce system) to add new administrative features, analytical capabilities, and UI enhancements for better product and order management.

Environment Details
XAMPP Version: win32-1.8.1 v9

PHP Version: 5.4.7

MySQL Version: 5.5.27

OpenCart Version: 1.5.6.4

Database Setup
Before implementing the code changes, execute the following SQL query in phpMyAdmin to add the cost field to the product table:

sql
ALTER TABLE oc_product ADD cost DECIMAL(15,4) NOT NULL DEFAULT '0.0000' AFTER price;
Project Tasks Implementation
Task 1 — Product Page Modifications (Admin)
Goal: Enhanced product management with cost/profit tracking and automatic category assignment.

Files Modified:
/admin/controller/catalog/product.php - Main controller logic

/admin/model/catalog/product.php - Model with database operations

/admin/language/english/catalog/product.php - Language strings

/admin/view/template/catalog/product_list.tpl - Product listing view

/admin/view/template/catalog/product_form.tpl - Product form view

What Was Implemented:
Cost Column:

Added cost database field (via SQL query)

Displayed in product list with proper formatting

Added filter supporting inequality operators (<, >, <=, >=, !=)

Enabled sorting functionality

Profit Column:

Calculated dynamically as price - cost

Displayed in product list with currency formatting

Added filter with inequality operator support

Enabled sorting capability

Automatic Parent Category Assignment:

When adding/editing a product in a category

Automatically assigns to all parent categories recursively

Works for both product creation and editing

Category (Full Path) Column:

Shows full category hierarchy (e.g., Parent > Sub1 > Sub2)

Handles multiple category assignments

Added dropdown filter with full-path format

Challenges Faced:
Handling legacy OpenCart MVC structure

Implementing recursive parent category assignment

Properly formatting and displaying full category paths

Ensuring backward compatibility with existing code

Task 2 — Category Page Modifications (Admin)
Goal: Enhanced category management with analytical insights.

Files Modified:
/admin/controller/catalog/category.php - Category controller

/admin/model/catalog/category.php - Category model

/admin/language/english/catalog/category.php - Language strings

/admin/view/template/catalog/category_list.tpl - Category listing view

What Was Implemented:
Product Count Column:

Counts products assigned to each category

Displays count in category list table

Added filter with inequality operators

Enabled sorting

Parent Category (Full Path) Column:

Displays full parent path for each category

Shows hierarchy from top-most to immediate parent

Example: For "Appliances" in "Home & Kitchen > Appliances", shows "Home & Kitchen"

Challenges Faced:
Efficiently counting products per category without performance issues

Building proper parent path queries for complex category hierarchies

Task 3 — Order Page Modification (Admin)
Goal: Enhanced order management with product viewing capabilities.

Files Modified:
/admin/controller/sale/order.php - Order controller

/admin/model/sale/order.php - Order model

/admin/language/english/sale/order.php - Language strings

/admin/view/template/sale/order_list.tpl - Order listing view

What Was Implemented:
View Products Button:

Added "View Products" button in Action column

Opens modal popup with order products

Modal loads data dynamically via jQuery/AJAX

Modal Popup Features:

Shows Product ID, Product Name, Price, Quantity, Total

Clean, responsive modal design

Loading indicator during data fetch

Error handling for failed requests

Challenges Faced:
Implementing modal in legacy OpenCart without modern JavaScript frameworks

Ensuring proper AJAX request handling with OpenCart's routing system

Maintaining consistent UI with existing admin interface

Technical Implementation Details
Database Design
Added cost column to oc_product table with appropriate data type

Maintained data integrity with default value 0.0000

No breaking changes to existing database structure

Code Quality
Followed OpenCart's MVC pattern strictly

Proper separation of concerns (controllers, models, views)

Added comprehensive error handling

Maintained backward compatibility

Clean, readable code with comments

UI/UX Improvements
All modifications blend seamlessly with default OpenCart admin interface

Responsive modal design

Intuitive filter interfaces with operator support

Consistent styling and user experience

Performance Considerations
Optimized database queries for large datasets

Implemented proper pagination

Used efficient algorithms for recursive operations

Minimized database calls through strategic caching

Key Features
Enhanced Product Analytics: Track cost, profit, and category relationships

Advanced Filtering: Inequality operators for numerical fields

Smart Category Management: Automatic parent assignment and full-path displays

Order Insights: Quick access to order products via modal

User-Friendly Interface: Intuitive filters and clear data presentation

Installation Instructions
Install OpenCart 1.5.6.4 on XAMPP 1.8.1 (PHP 5.4.7)

Execute the SQL query to add the cost column

Replace the modified files in their respective directories

Clear OpenCart cache if needed

Testing
All features were tested with:

Various product/category hierarchies

Different filter combinations

Edge cases (empty categories, zero-cost products)

Cross-browser compatibility

Conclusion
This project successfully extended OpenCart 1.5.6.4's admin capabilities while maintaining the existing system's architecture and compatibility. The modifications provide valuable analytical tools for e-commerce management without compromising performance or user experience.
