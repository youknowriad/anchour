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
                    source_dir: "/tmp/minitwitter"
                    destination_dir: "/tmp/minitwitter2"
                    destination_connection: "MySSH"

Now deploy your project by running

    php anchour.phar deploy