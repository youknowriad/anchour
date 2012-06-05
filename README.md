Anchour
=======

Anchour is a deployment engine for web applications

Installation
============

    wget http://anchour.rizeway.com/anchour.phar

Usage
=====

First, Add a configuration file named `.anchour` to your project.
This Files defines scripts that are a collection of steps

Example
-------
    deploy:
        connections:
            MySSH:
                type: "ssh"
                options:
                    host: "localhost"
                    username: "foo"
                    password: "bar"
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
                    destination_connection: "MySSH"

Now deploy your project by running

    php anchour.phar deploy


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
        destination_connection: "MySSH"

Ftp
---
This step type allows you to upload a local folder using a FTP connection

### Usage

    type: "ftp"
    options:
        connection: "MyFTP"
        local_dir: "src"
        remote_dir: "test"

Ssh
---
This step type allows you to execute commands in a remote server using SSH

### Usage

    type: "ssh"
    options:
        connection: "MySSH"
        commands:
            - uname -a
            - date

Git
---
This allows you to clone a GIT repository in a remote server using a SSH connection

### Usage

    type: "git"
    options:
        connection: "MySSH"
        repository: "git://github.com/jubianchi/minitwitter.git"
        remote_dir: "tmp/minitwitter"
        clean_scm: true
        remove_existing: true

MySql
-----
This step allows you to maka a Mysql Export/Import using two MySql Connections

### Usage

    type: "mysql"
    options:
        source: "MySQL1"
        destination: "MySQL2"
        create_database: true
        drop_database: true


Variables
=========
You may want to commit your .anchour file without some informations like passwords and hosts ... To do that, Anchour allows you to define some required variables in your connections like this

    deploy:
        description: Deploy using FTP
        
        connections:
            MyFTP:
                type: ftp
                options:
                    host: %my_host%
                    username: %my_username%
                    password: %my_password%

        steps:
            -
                type: ftp
                options:
                    connection: MyFTP
                    remote_dir: test

        require:
            my_host: server host
            my_username: your username
            my_password: your password

When you run the command deploy (described above), anchour will ask you to enter the parameters in the require config section and will use them in the right places (example: %my_username% will be replaced by the value of the parameter my_username)