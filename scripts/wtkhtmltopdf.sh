#!/usr/bin/env bash

wget http://download.gna.org/wkhtmltopdf/0.12/0.12.2.1/wkhtmltox-0.12.2.1_linux-trusty-amd64.deb
sudo apt-get install xfonts-75dpi
sudo dpkg -i wkhtmltox-0.12.2.1_linux-trusty-amd64.deb
echo 'exec xvfb-run -a -s "-screen 0 640x480x16" wkhtmltopdf "$@"' | sudo tee /usr/local/bin/wkhtmltopdf.sh >/dev/null

