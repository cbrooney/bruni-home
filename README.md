# Usage
## dev:
- takes base images
- mount code by compose.yml
- see [run-dev.sh](run-dev.sh)
## prod:
- testing the final image with complete env as it should be used
- takes prod base image and mounts code
- can also be tagged
- no build should be needed here
- however history should be mounted at least
- 