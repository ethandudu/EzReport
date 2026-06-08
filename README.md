# EzReport

This project is a simple web application built with PHP and MySQL, designed to collect and display test results. It uses Docker for easy deployment and management.

## Features

- Collect test results via a web interface
- Store results in a MySQL database
- Display results in a user-friendly format

## Prerequisites

- Docker

## Setup Instructions

1. Clone the repository:

   ```bash
   git clone https://github.com/ethandudu/EzReport.git
   cd EzReport
    ```

2. Build and run the Docker containers:

   ```bash
    docker-compose up --build
    ```

3. Access the application in your web browser at `http://localhost:8080`.

## Database Initialization

The database will be automatically initialized with the provided `init.sql` script when the MariaDB container starts.

## Usage

- To add a test result, you can use the web interface or send a GET request to the following endpoint:

  ```bash
  http://localhost:8080/index.php?action=add&testName=yourTestName&value=yourValue
  ```
  
An example PowerShell script (`demo.ps1`) is included to demonstrate how to send test results programmatically.
