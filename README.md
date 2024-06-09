# Secret Server API in Laravel 11 <!-- omit from toc -->

## Table of Contents <!-- omit from toc -->

- [Project Overview](#project-overview)
- [Features](#features)
- [Requirements](#requirements)
- [Installation](#installation)
- [Usage](#usage)
- [Endpoints](#endpoints)
- [API availability](#api-availability)

## Project Overview

This is an implementation of a secret server in Laravel API. You can store and view a certain secret's details, but restricted by a few limitations.

## Features

- Add a secret with a POST request
- Get a secret's details with a GET request
- A secret has an expiry date, after that it is not possible to view it
- A secret has a certain amount of times it can be opened
 
## Requirements

- PHP 8.2 or higher
- Laravel framework 11.9 or higher

## Installation

- Clone the repository
- Configure Database inside .env
- Run migrations and populate the database with fakedata
  ```
  php artisan migrate:refresh --seed
  ```
- Serve the application
  ```
  php artisan serve
  ```

## Usage

- I recommend using Postman or other application to test API endpoints

## Endpoints

- Endpoint: 'api/secret'
  - Method: 'POST'
  - Parameters:
    - 'secret': string|required
    - 'expireAfterViews': numeric|required
    - 'expireAfter': numeric|required
  - Response: 201, success message in xml or json format based on the 'Accept' header of the request

- Endpoint: 'api/secret/{hash}'
  - Method: 'GET'
  - Parameters:
    - 'hash': string|required
  - Response as json:
  ```json
  {
    "hash": "180b8d7f90fa4a91a0bc81f5ba10f5ec",
    "secretText": "Rerum unde et expedita provident dignissimos quo dolorem.",
    "createdAt": "2024-06-09T17:04:42.643Z",
    "expiresAt": "2024-06-10T00:59:42.643Z",
    "remainingViews": 8
  }
  ```
  - Response as xml:
  ```xml
  <?xml version="1.0"?>
  <root>
      <hash>180b8d7f90fa4a91a0bc81f5ba10f5ec</hash>
      <secretText>Rerum unde et expedita provident   dignissimos quo dolorem.</secretText>
      <createdAt>2024-06-09T17:04:42.643Z</createdAt>
      <expiresAt>2024-06-10T00:59:42.643Z</expiresAt>
      <remainingViews>8</remainingViews>
  </root>
  ```

## API availability

<http://secret-server-production.up.railway.app>