# Astro Notifier

With the thought that getting a notification in my inbox that there is a decent day for amateur astronomy coming up might actually spur me into doing some observations, I made this bit of software. It is a simple website that will pull in data from Dark Skies API for any locations that it is interested in, and parse the data to see if any times fulfil the requirements.

Initially it was just going to be for myself, but I decided to open it up so that multiple users can register and also get notifications.

# How it works.

It will make a call daily to the Dark Skies API for all locations that it is interested in, and store these in the database. Then later that day another scheduled task will run to parse that data and send emails. Currently the criteria that are used wind speed, cloud cover, and of course that the sun is down. You can also specify how many days ahead to look (up to 8) and how many consecutive hours of good viewing you need. 


