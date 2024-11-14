# Books Library Plugin

**Description:**  
The Books Library Plugin is a WordPress plugin that allows users to manage books through a custom post type, a custom taxonomy, custom meta boxes, shortcodes, widgets, and more. This plugin helps create a structured way to manage and display books, authors, and other related information within WordPress.

## Table of Contents
- [Purpose](#purpose)
- [Features](#features)
- [Installation](#installation)
- [Usage](#usage)
  - [Shortcode Usage](#shortcode-usage)
  - [Widget Usage](#widget-usage)
  - [Custom Post Type Usage](#custom-post-type-usage)
- [Security & Code Quality](#security--code-quality)
- [Development Status](#development-status)
- [Contributing](#contributing)
- [Changelog](#changelog)

## Purpose  
The purpose of the Books Library Plugin is to provide a structured and customizable way to manage books, authors, and their associated metadata. It allows the admin to categorize books into genres, add additional details through custom meta boxes, display books on the frontend with a shortcode, and much more.

## Features  
- **Custom Post Type (CPT)**: Registers a custom post type called **Books** for managing book data.
- **Custom Taxonomy**: Implements a hierarchical taxonomy called **Genres** (e.g., Fiction, Non-fiction, Mystery, Science Fiction) for categorizing books.
- **Custom Meta Boxes**: Adds custom fields for books, including:
  - Author Name
  - Publication Date (with a date picker)
  - ISBN Number
  - Publisher Name
  - Price (number input field)
- **Admin Custom Columns**: Customizes the "Books" admin table to show columns for Author Name, Publication Date, and Genres. These columns are sortable and filterable by genre.
- **Shortcode**: Displays a list of books on any page or post with the `[book_list]` shortcode, with optional filters for genre and author.
- **Pagination & Sorting**: Allows sorting by title, publication date, or price and includes pagination for large lists of books.
- **Sidebar Widget**: A widget that displays recent books added to the "Books" post type. The admin can choose the number of books to display and whether to show the book price.
- **Bulk and Quick Edit**: Extends the bulk and quick edit functionality to include the Author Name and Publication Date fields for the "Books" post type.
- **Admin Notices**: Displays a custom admin notice when a new book is published, visible to users with Editor or Administrator roles.

## Installation  
To install the Books Library Plugin, follow these steps:

1. **Download the Plugin Files**: Download the plugin from the repository.
2. **Upload the Plugin**: Upload the plugin folder to the `/wp-content/plugins/` directory.
3. **Activate the Plugin**: Go to the WordPress admin panel, find the plugin under **Plugins**, and activate it.
4. **Create Custom Post Type**: The plugin automatically registers the **Books** custom post type and **Genres** custom taxonomy.

## Usage  

### Shortcode Usage  
The plugin provides a shortcode to display a list of books:

```[book_list genre="Fiction" author="John Doe"]```

- **genre** (optional): Filter books by genre.
- **author** (optional): Filter books by author.

The shortcode will display a simple list format with the following information for each book:
- Title
- Author
- Publication Date
- Genre

### Widget Usage  
To add the recent books widget to your sidebar:

1. Go to **Appearance > Widgets**.
2. Add the **Recent Books** widget to your sidebar.
3. Configure the widget settings, including the number of books to display and whether to show the price.

### Custom Post Type Usage  
To add a new book:

1. Go to **Books > Add New** in the WordPress admin.
2. Fill in the required fields for the book, including title, author, publication date, ISBN, publisher, and price.
3. Choose the appropriate genre(s) for the book.
4. Save the book.

To view and manage books, go to **Books > All Books** in the WordPress admin.

## Security & Code Quality  
- **Data Sanitization & Validation**: All inputs (custom fields, shortcodes, etc.) are sanitized and validated to ensure secure data handling.
- **Nonce Verification**: Nonce verification is implemented for form submissions in meta boxes and quick/bulk edits to prevent CSRF attacks.
- **Coding Standards**: The plugin follows WordPress coding standards for PHP, JavaScript, HTML, and CSS, with proper inline comments and documentation for maintainability.

## Development Status  
**Current Version**: 1.0.0  
**Last Updated**: YYYY-MM-DD  
**Status**: The plugin is fully functional and ready for use. Future updates will focus on adding additional features and improvements based on user feedback.

## Contributing  
Contributions are welcome! If you find a bug or have suggestions for new features, please submit an issue or pull request on GitHub.

## Changelog  
- **1.0.0**: Initial release with core features including custom post type, taxonomy, meta boxes, shortcode, and more.