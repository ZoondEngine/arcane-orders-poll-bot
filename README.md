## How to start it

All short instructions list below

- installing ngrok
- run: ```composer install```
- run: ```php artisan migrate```
- open the @BotFather dm and create new bot for get token
- run: ```php artisan telegraph:new-bot```
- follow command instructions
- run: ```php artisan serve```
- run: ```ngrok http http://localhost:8000```
- open one more console window
- run: ```php artisan schedule:work```

## Description

It's a telegram bot for polling orders
from remote server and notify subscribed user automatically in telegram chat with that bot

## Fundamental

That's application uses telegraph package for Laravel and ngrok for https webhook workflow.
For polling remote data uses the Job Laravel mechanisms, for starting them please use the 
``php artisan schedule:work`` command
