Lilliydoo test application
========================

Create an an address book CRUD.

**The address book should contain the following data:** 
- Firstname
- Lastname
- Street and number
- Zip
- City
- Country
- Phonenumber
- Birthday
- Email address
- Picture (optional)

**The following techniques were used:** 
- Symfony 3.4
- Doctrine with SQLite
- Twig
- PHP7.4+

**Steps to check the working application**
- php bin/console server:run - run the application
- php bin/console doctrine:database:create - command to create the database
- php bin/console doctrine:schema:update --force - to create addressbook table

- /addressbook - the list of addresses
- /addressbook/add - to add  new address
- /addressbook/{id} - to view address
- /addressbook/edit/{id} - to edit address

- all uploaded pictures you can find in web/uploads/pictures

You can delete address from the list of addresses, edit page or view page.