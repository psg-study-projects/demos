## About 
# Anonymous SMS Demo

View the demo app at:

http://anonsms.peterg-webdeveloper.com/

## Assumptions
* for simplicity a user is only allowed one topic for the demo
* sample topics are 'colors'
* A 3rd party service such as Twilio or Plivo will be used to send the actual SMS messages (using their API)

## What was done
* Laravel App setup
* Database migrations
* Database seeders with PHP 'faker'
* New User Signup with select topic dropdown & *real* phone number (populated from 'topics' DB table)
* User Login
* Dashboard with Boostrap4 tabs
  * tab for available users (users with the same topic). Clicking on a user will take you to the conversation page...and show existing past messages if they exist.
  * tab for list of conversations, with most recently active at the top. Clicking on a conversation takes you to the conversation page.
* On the conversation page, new messages can be sent via the form at the top. Added an animation effect for demo purposes.
  * NOTE: this is not a real-time implementation, thus the receive will need to refresh their web page to view new messages since the last page load. A way to implement real-time behavior would be to use sockets and/or NodeJS.
* AWS deployment including EC2 server, MySQL database, and Route53 host/DNS configuration for subdomain

## Does not (yet) have...
* 3rd party service such as Twilio integration
* Access control. Any conversation page are viewable by any logged in user.
* Usability, help icons/messages to guide user
* Unit testing
* Password retrieval
* User Profile Page
* etc

## Other
* Real phone number is mapped to a token number via a service such as Twilio

## Database Design
![Database Design](https://github.com/peltronic/demos/blob/master/php/anonsms/docs/Database%20Design.png)

## Product Architecuture
![Product Architecuture](https://github.com/peltronic/demos/blob/master/php/anonsms/docs/Product%20Architecture.png)
