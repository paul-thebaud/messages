#!/bin/bash

usage="$(basename "$0") [-h] [-n value] [-d value] [-u value] [-a value] [-b value] [-c value] [-e value] [-f value] [-g value] [-i value] [-j value] [-k value] [-l value] [-m value] [-o value] [-p value] [-q value] [-r value] -- program to generate a .env file for laravel

where:
    - h  Show this help text
    - n  Set the app name
    - d  Set the debug flag
    - u  Set the app url
    - a  Set the database host
    - b  Set the database port
    - c  Set the database name
    - e  Set the database username
    - f  Set the database password
    - g  Set the mail server host
    - i  Set the mail server port
    - j  Set the mail username
    - k  Set the mail password
    - l  Set the mail encryption
    - m  Set the facebook id client
    - o  Set the facebook secret
    - p  Set the google client id
    - q  Set the google secret
    - r  Set the WebSocket Port"

declare -A ENVVARIABLES

mandatory=0;

while getopts h:n:d:u:a:b:c:e:f:g:i:j:k:l:m:o:p:q:r: option
do
    case "${option}"
    in
    h) echo "$usage"
        exit
        ;;
    n) mandatory=$((mandatory+1))
        ENVVARIABLES[APP_NAME]=\"${OPTARG}\"
        ;;
    d) mandatory=$((mandatory+1))
        ENVVARIABLES[APP_DEBUG]=${OPTARG}
        ;;
    u) mandatory=$((mandatory+1))
        ENVVARIABLES[APP_URL]=${OPTARG}
        ;;
    a) mandatory=$((mandatory+1))
        ENVVARIABLES[DB_HOST]=${OPTARG}
        ;;
    b) mandatory=$((mandatory+1))
        ENVVARIABLES[DB_PORT]=${OPTARG}
        ;;
    c) mandatory=$((mandatory+1))
        ENVVARIABLES[DB_DATABASE]=${OPTARG}
        ;;
    e) mandatory=$((mandatory+1))
        ENVVARIABLES[DB_USERNAME]=${OPTARG}
        ;;
    f) mandatory=$((mandatory+1))
        ENVVARIABLES[DB_PASSWORD]=${OPTARG}
        ;;
    g) mandatory=$((mandatory+1))
        ENVVARIABLES[MAIL_HOST]=${OPTARG}
        ;;
    i) mandatory=$((mandatory+1))
        ENVVARIABLES[MAIL_PORT]=${OPTARG}
        ;;
    j) mandatory=$((mandatory+1))
        ENVVARIABLES[MAIL_USERNAME]=${OPTARG}
        ;;
    k) mandatory=$((mandatory+1))
        ENVVARIABLES[MAIL_PASSWORD]=${OPTARG}
        ;;
    l) mandatory=$((mandatory+1))
        ENVVARIABLES[MAIL_ENCRYPTION]=${OPTARG}
        ;;
    m) mandatory=$((mandatory+1))
        ENVVARIABLES[FACEBOOK_CLIENT_ID]=${OPTARG}
        ;;
    o) mandatory=$((mandatory+1))
        ENVVARIABLES[FACEBOOK_SECRET]=${OPTARG}
        ;;
    p) mandatory=$((mandatory+1))
        ENVVARIABLES[GOOGLE_CLIENT_ID]=${OPTARG}
        ;;
    q) mandatory=$((mandatory+1))
        ENVVARIABLES[GOOGLE_SECRET]=${OPTARG}
        ;;
    r) mandatory=$((mandatory+1))
        ENVVARIABLES[MIX_WS_PORT]=${OPTARG}
        ;;
    :)
        echo "L'option $OPTARG requiert un argument"
        exit 1
        ;;
    \?)
        echo "$OPTARG : option invalide"
        exit 1
        ;;
    esac
done

if [[ ${mandatory} -ne 18 ]]
then
    echo "Missing argument. $mandatory";
    echo "All arguments are mandatory.";
    echo "$usage";
    exit 2
fi

if [[ -f ./.env ]]; then
    echo ".env file found, moved to .env.old !"
    mv .env .env.old
fi

ENVVARIABLES[APP_ENV]=local
ENVVARIABLES[APP_KEY]=

ENVVARIABLES[LOG_CHANNEL]=stack

ENVVARIABLES[DB_CONNECTION]=mysql

ENVVARIABLES[BROADCAST_DRIVER]=pusher
ENVVARIABLES[CACHE_DRIVER]=file
ENVVARIABLES[SESSION_DRIVER]=file
ENVVARIABLES[SESSION_LIFETIME]=120
ENVVARIABLES[QUEUE_DRIVER]=database

ENVVARIABLES[MAIL_DRIVER]=smtp

ENVVARIABLES[PUSHER_APP_ID]=
ENVVARIABLES[PUSHER_APP_KEY]=
ENVVARIABLES[PUSHER_APP_SECRET]=
ENVVARIABLES[PUSHER_APP_CLUSTER]=mt1

ENVVARIABLES[MIX_PUSHER_APP_KEY]=\"\$\{PUSHER_APP_KEY\}\"
ENVVARIABLES[MIX_PUSHER_APP_CLUSTER]=\"\$\{PUSHER_APP_CLUSTER\}\"

ENVVARIABLES[MIX_APP_URL]=${ENVVARIABLES[APP_URL]}

ENVVARIABLES[GOOGLE_REDIRECT]=\$\{APP_URL\}/oauth/google
ENVVARIABLES[FACEBOOK_REDIRECT]=\$\{APP_URL\}/oauth/facebook

echo "Generating .env file";

for key in "${!ENVVARIABLES[@]}";
    do echo $key=${ENVVARIABLES[$key]} >> .env;
done

echo "Done.";
