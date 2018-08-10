This repository contains code to allow demonstartion of a Cross-Site Requesty Forgery (CSRF)
and how to stop that from occuring. It is part of a video series touching on Web Application
Security. You can find more tutorial videos at http://nilfactor.com/videos.html

Folders and whats in them
attack - example html that would allow you to execute the CSRF Attack
csrftoken - php class for handling CSRF Tokens with requests
lab - contains the php code that would allow a CSRF Attack

Watch the tutorial here: https://youtu.be/eS74r_N7KY8<

This code was written and developed by Richard Williamson of NilFactor unless otherwise stated

Creating the docker container
    cd lab
    ./run
    ./build

you might have to run docker as sudo depending on your environment
    cd lab
    sudo ./run
    sudo ./build

open a web browser and go to http://127.0.0.1:8000/

Upload the attack folder to a webserver somewhere or even open the funny_meme.html in a browser
