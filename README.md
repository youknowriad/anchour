Anchour
=======

[![Build Status](https://secure.travis-ci.org/youknowriad/anchour.png?branch=master)](http://travis-ci.org/youknowriad/anchour)

Anchour is a deployment engine for web applications

Installation
============

    wget http://rizeway.com/anchour.phar

Usage
=====

First, Add a configuration file named `.anchour` to your project.
This file defines some commands composed by an ordered set of steps

Example
-------    
    anchour:
        connections:
            MySSH:
                type: "ssh"
                options:
                    host: "localhost"
                    username: "foo"
                    password: "bar"

        commands:
            deploy:
                steps:
                    -
                        type: "echo"
                        options:
                            message: "A test message <comment>with</comment> <info>formatted</info> <error>output</error>"

                    -
                        type: "rsync"
                        options:
                            key_file: "/home/username/.ssh/id_rsa_rsync"
                            source_dir: "tmp/minitwitter"
                            destination_dir: "tmp/minitwitter2"
                        connections:
                            connection: MySSH


Now deploy your project by running

    ./anchour.phar deploy


Connections
===========

The connection types allowed by Anchour are

SSH with login and password
---------------------------
    MySSH:
        type: "ssh"
        options:
            host: "localhost"
            username: "foo"
            password: "bar"

FTP
---
    MyFTP:
        type: "ftp"
        options:
            host: "host.fr"
            username: "foo"
            password: "bar"

MySQL
-----
    MySql1:
        type: "mysql"
        options:
            host: "host.fr"
            username: "foo"
            password: "bar"
            database: "db1"


Steps
=====

The step types allowed by Anchour are

Echo
----
This step type allows you to output a formatted message

### Usage

    type: "echo"
    options:
        message: "A test message <comment>with</comment> <info>formatted</info> <error>output</error>"

Rsync
-----
This step type allows you to synchronize a local folder in a distant server using a SSH connection

### Usage

    type: "rsync"
    options:
        key_file: "/home/username/.ssh/id_rsa_rsync"
        source_dir: "tmp/minitwitter"
        destination_dir: "tmp/minitwitter2"
    connections:
        destination: "MySSH"

Ftp
---
This step type allows you to upload a local folder using a FTP connection

### Usage

    type: "ftp"
    options:
        local_dir: "src"
        remote_dir: "test"
    connections:
        connection: "MyFTP"

Ssh
---
This step type allows you to execute commands in a remote server using SSH

### Usage

    type: "ssh"
    options:
        commands:
            - uname -a
            - date
    connections:
        connection: "MySSH"

Git
---
This allows you to clone a GIT repository in a remote server using a SSH connection

### Usage

    type: "git"
    options:
        repository: "git://github.com/jubianchi/minitwitter.git"
        remote_dir: "tmp/minitwitter"
        clean_scm: true
        remove_existing: true
    connections:
        connection: "MySSH"

MySql
-----
This step allows you to maka a Mysql Export/Import using two MySql Connections

### Usage

    type: "mysql"
    options:
        create_database: true
        drop_database: true
    connections:
        source: "MySQL1"
        destination: "MySQL2"

CliPhar
-----
This step allows you to build a CLI Phar archive

### Usage

    type: "cliPhar"
    options:
        directory: "."
        regexp: "^[^\.].*/a/.*|regexp/.*|used/(?!to).*|filter\.php"
        stub: "path/to/phar/stub.php"
        name: "name.phar"
        chmod: true

Variables
=========
You may want to commit your .anchour file without some informations like passwords and hosts ... To do that, Anchour allows you to define some required variables in your connections like this


    connections:
        MyFTP:
            type: ftp
            options:
                host: %my_host%
                username: %my_username%
                password: %my_password%

    commands:
        deploy:
            description: Deploy using FTP
            

            steps:
                -
                    type: ftp
                    options:
                        remote_dir: %folder%
                    connections:
                        connection: MyFTP


When you run the command deploy (described above), anchour will detect all the required variables for your command, asks you their values, and use them in the right places (example: %my_username% will be replaced by the value of the variable my_username)

Contribute
==========
Install the dependancies using composer and your ready to go

    git clone https://github.com/youknowriad/anchour.git && cd anchour
    curl -s http://getcomposer.org/installer | php
    ./composer.phar install