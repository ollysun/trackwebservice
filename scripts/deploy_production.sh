#!/usr/bin/env bash
# Merge Develop into Production
git checkout dev
git pull origin dev
git checkout production
git pull origin production
git merge dev
git push origin production
git checkout dev

echo "Enter ssh key file and press [ENTER]"
read ssh_key_file
ssh ubuntu@tnt_be -i $ssh_key_file <<'ENDSSH'
#commands to run on remote host
cd /var/www/html/tnt-service
git pull
composer update
exit
ENDSSH