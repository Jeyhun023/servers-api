# Steps to up Docker containers for the NinaCare App on Windows

### 1. Need to install docker to the Ubuntu WSL (skip it if you already have)
> sudo apt install curl
>
> curl -fsSL https://get.docker.com -o get-docker.sh
>
> chmod +x get-docker.sh
>
> sudo ./get-docker.sh

### 2. Clone the project
> git clone https://github.com/Jeyhun023/servers-api.git
>
> cd servers-api
>
> cp .env .env.local

### 3. Start the project

Run init
> make init
>
> make console migrate

Go to `http://localhost:8000/` address in browser

All available `make` commands can be seen by running the following:
> make help
