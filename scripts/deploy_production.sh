#!/usr/bin/env bash
# Merge Develop into Production
dev_branch='dev'
production_branch='production'
git checkout $dev_branch
git pull origin $dev_branch
git checkout $production_branch
git pull origin $production_branch
git merge $dev_branch
git push origin $production_branch
git checkout $dev_branch

# Tag release
git checkout master
git pull origin master
git merge $dev_branch
echo "Enter version number and press [ENTER]"
read version_number
echo "Enter version message and press [ENTER]"
read version_message
git tag -a v$version_number -m "$version_message"
git push --tags

# Deploy to production
echo "Enter ssh key file and press [ENTER]"
read ssh_key_file
ssh ubuntu@tnt_be -i $ssh_key_file <<'ENDSSH'
#commands to run on remote host
cd /var/www/html/tnt-service
git pull
composer update
exit
ENDSSH