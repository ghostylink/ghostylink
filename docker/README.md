# Ghostylink project embed in docker image

## Build the image
sudo docker build -t [repository-name]/ghostylink .

## Launch the image
sudo docker run -d -p [binding-port]:80 [repository-name]/ghostylink

## Use ghostylink
You just need to go on your favorite browser at : http://localhost:[binding-port] and try Ghostylink !

## Soon available
The image will be push on docker hub and will be directly available without have to build it.
