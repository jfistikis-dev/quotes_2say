
# Quotation 2Say

A project for popping out ancient Greek quotations from Greek philosopers. 
The purpose of this project was to familiarize with Google's Antigravity IDE and to create a small project without actually writting any code. 
It is developed as an spa using Laravel, Inertia and vue and mainly as an endpoint for an ios app created on XCode using webview component. .. but of course the normal web-browsing is supported. 
It runs smoothly doiing the following : 

# Registration
    a. Browser users
        Web browser users are viewing a normal registration ( username , password).
    b. IOS app users
        The registration part is same but the device's id is stored so , on reentering the app, there is an auto-login. One user can register as many devices as he requires.

# Quotations

As soon as login is finished user is taken to the dashboard and the app is showing a random quotation. 
Each time there is a refresh ( -- or re-opening the app for Ios devices ), a new quotation is shown based on the previous already shown quotations.

# New cycle
As soon as user has seen all quotations, a new cycle begins by reseting internal counters of the user and checking on new entries in the DB. 
To prevent of viewing twice the same quotation on short period, a weighing systems was added that calculates the "weight" of each quotation before displaying it.


The seeder has only 20 Delphic Maxims since this is just a proof-of-concept project


