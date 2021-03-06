#!/usr/bin/env bash
function merge() {
    # Merges from dev branch to environment branch
    echo "Merging $1 to $2"
    git checkout $1
    git pull origin $1
    git checkout $2
    git pull origin $2
    git merge $1
    git push origin $2
    git checkout $1
}

function deploy() {
    ## Deploys app to server
    # 1 - Environment
    echo "Deploying to $1"

    echo "Enter username and press [ENTER]"
    read username

    echo "Enter host and press [ENTER]"
    read host

    echo "Enter ssh key file and press [ENTER]"
    read ssh_key_file

    ssh $username@$host -i $ssh_key_file "cd /var/www/html/$app_folder && git pull && composer update"

    migrate $1
}

function tagRelease() {
    echo "Tagging relaese"
    git checkout master
    git pull origin master
    git merge $1
    git push origin master
    echo "Enter version number and press [ENTER]"
    read version_number
    echo "Enter version message and press [ENTER]"
    read version_message
    echo "Creating tag $version_number($version_message)"
    git tag -a v$version_number -m "$version_message"
    git push --tags
    git checkout $1
}

function run() {
    echo "Deployment Starting..."

    echo "Enter the development branch and press [ENTER]"
    read dev_branch

    envs=(staging production)
    app_folder=tnt-service

    if [ -n "$dev_branch" ] ; then
        for env in "${envs[@]}"
        do
            echo "Do you want to deploy to $env? Y/N"
            read should_deploy
            if [ $should_deploy = "Y" -o $should_deploy = "y" ] ; then
                echo "Enter the $env branch and press [ENTER]"
                read branch
                if [ -n "$branch" ] ; then
                    merge $dev_branch $branch
                    deploy $env
                    if [ "$env" = "production" ]; then
                        tagRelease $dev_branch
                    fi
                else
                    echo "Invalid branch entered"
                fi
            fi
        done
    else
        echo "You can't proceed without a dev branch"
    fi
}

function migrate() {
    # Runs phinx migration
    # 1 - Environment

    echo "Do you want to run phinx database migration on $1? Y/N"
    read should_migrate

    if [ ${should_migrate} = "Y" -o ${should_migrate} = "y" ] ; then

    echo "Enter database host: "
    read db_host

    echo "Enter database name: "
    read db_name

    echo "Enter database username: "
    read db_user

    echo "Enter database password: "
    read db_password

    echo "Running phinx database migration on $1..."

    ssh ${username}@${host} -i ${ssh_key_file} "cd /var/www/html/$app_folder && PHINX_DBHOST=$db_host PHINX_DBNAME=$db_name PHINX_DBUSER=$db_user PHINX_DBPASS=$db_password php vendor/bin/phinx migrate -e $1"

    fi
}
run