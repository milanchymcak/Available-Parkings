# PHP - Test Project
1. Create a database of parking spots, which you can gather at http://opendata.praha.eu/dataset/parkoviste
2. Get the address of the visitor by the IP and cache it. API endpoint -  http://ip-api.com
3. Create a function that will return places in the radius of your address (haversine formula)
4. Create an API endpoint that will return the places from 3.  
+ Allow sorting by available parking spots  
+ Allow sorting by the distance of the visitor  
+ Allow adding custom GPS or IP  
+ Allow filtering by the name  

## Problem

## Required
+ PHP 8.1

## Install
+ gh repo clone milanchymcak/testproject-parking
+ Run the index.php

## API
+ Run at /api.php
+ Method: GET

#### API Params
+ customIP - custom IP
+ lat - latitude
+ lon - Longtitude
